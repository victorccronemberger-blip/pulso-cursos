[CmdletBinding()]
param(
    [Parameter()]
    [string] $SourceRoot = 'C:\Users\victo\Desktop\vidoes',

    [Parameter()]
    [string] $CatalogPath = 'resources/course-imports/source-catalog-2026.json',

    [Parameter()]
    [string] $OutputRoot = 'storage/app/course-imports/sources'
)

$ErrorActionPreference = 'Stop'
Set-StrictMode -Version Latest

function Resolve-ProjectPath {
    param([string] $Path)

    if ([System.IO.Path]::IsPathRooted($Path)) {
        return [System.IO.Path]::GetFullPath($Path)
    }

    return [System.IO.Path]::GetFullPath((Join-Path (Get-Location) $Path))
}

function Convert-ToRelativeUnixPath {
    param(
        [string] $BasePath,
        [string] $TargetPath
    )

    $baseUri = [Uri]((Resolve-Path -LiteralPath $BasePath).Path.TrimEnd('\') + '\')
    $targetUri = [Uri](Resolve-Path -LiteralPath $TargetPath).Path
    return [Uri]::UnescapeDataString($baseUri.MakeRelativeUri($targetUri).ToString())
}

function Get-VideoDuration {
    param(
        [object] $Shell,
        [System.IO.FileInfo] $File
    )

    $folder = $Shell.Namespace($File.DirectoryName)
    $item = $folder.ParseName($File.Name)
    $raw = $folder.GetDetailsOf($item, 27)

    if ([string]::IsNullOrWhiteSpace($raw)) {
        return $null
    }

    $value = ($raw -replace '[^0-9:]', '').Trim()
    if ($value -match '^\d{1,2}:\d{2}:\d{2}$') {
        return $value.PadLeft(8, '0')
    }

    return $null
}

function Get-LessonMetadata {
    param(
        [System.IO.FileInfo] $File,
        [switch] $AllowMissingNumber
    )

    if ($File.BaseName -notmatch '^(?<number>\d+)_+(?<remainder>.+)$') {
        if (-not $AllowMissingNumber) {
            throw "Nome de vídeo sem prefixo numérico: $($File.Name)"
        }
        $lessonNumber = 0
        $remainder = $File.BaseName
    } else {
        $lessonNumber = [int] $Matches.number
        $remainder = $Matches.remainder
    }
    $part = 1
    if ($remainder -match '__(?<part>\d+)$') {
        $part = [int] $Matches.part
        $remainder = $remainder -replace '__\d+$', ''
    }

    $separator = $remainder.IndexOf('_')
    if ($separator -lt 0) {
        $code = $remainder.Trim()
        $title = $remainder.Trim()
    } else {
        $code = $remainder.Substring(0, $separator).Trim()
        $title = $remainder.Substring($separator + 1).Trim()
    }

    $title = ($title -replace '\s+', ' ').Trim(' ', '.', '_')
    if ([string]::IsNullOrWhiteSpace($title)) {
        $title = $code
    }

    return [pscustomobject]@{
        lesson_number = $lessonNumber
        code = $code
        title = $title
        part = $part
    }
}

function Get-SourceKey {
    param([string] $Code)

    return ([regex]::Replace($Code.ToUpperInvariant(), '[^A-Z0-9]', ''))
}

$resolvedSourceRoot = Resolve-ProjectPath $SourceRoot
$resolvedCatalogPath = Resolve-ProjectPath $CatalogPath
$resolvedOutputRoot = Resolve-ProjectPath $OutputRoot

if (-not (Test-Path -LiteralPath $resolvedSourceRoot -PathType Container)) {
    throw "Diretório-fonte não encontrado: $resolvedSourceRoot"
}

if (-not (Test-Path -LiteralPath $resolvedCatalogPath -PathType Leaf)) {
    throw "Catálogo não encontrado: $resolvedCatalogPath"
}

$catalog = Get-Content -LiteralPath $resolvedCatalogPath -Raw -Encoding UTF8 | ConvertFrom-Json
New-Item -ItemType Directory -Path $resolvedOutputRoot -Force | Out-Null
$utf8NoBom = New-Object System.Text.UTF8Encoding($false)
$shell = New-Object -ComObject Shell.Application
$audit = [System.Collections.Generic.List[object]]::new()

foreach ($course in $catalog.courses) {
    Write-Verbose "Iniciando $($course.key)"
    $outputPath = Join-Path $resolvedOutputRoot ($course.key.ToLowerInvariant() + '.json')
    $cachedDurationByFile = @{}
    if (Test-Path -LiteralPath $outputPath -PathType Leaf) {
        $previousManifest = Get-Content -LiteralPath $outputPath -Raw -Encoding UTF8 | ConvertFrom-Json
        foreach ($previousLesson in @($previousManifest.lessons)) {
            if ($previousLesson.duration) {
                $cachedDurationByFile[[string] $previousLesson.source_file] = [string] $previousLesson.duration
            }
        }
    }

    $courseRoot = Join-Path $resolvedSourceRoot $course.key
    $videoRoot = Join-Path $courseRoot 'Videos'
    if (-not (Test-Path -LiteralPath $videoRoot -PathType Container)) {
        throw "Diretório de vídeos não encontrado para $($course.key): $videoRoot"
    }

    $excludedNames = @{}
    foreach ($name in @($course.exclude_files)) {
        $excludedNames[[string] $name] = $true
    }

    $sectionByNumber = @{}
    foreach ($section in $course.sections) {
        foreach ($number in @($section.lesson_numbers)) {
            if ($sectionByNumber.ContainsKey([int] $number)) {
                throw "Aula $number foi mapeada para mais de uma seção em $($course.key)."
            }
            $sectionByNumber[[int] $number] = [string] $section.key
        }
    }

    $videoRows = [System.Collections.Generic.List[object]]::new()
    $unresolved = [System.Collections.Generic.List[string]]::new()
    $missingDuration = [System.Collections.Generic.List[string]]::new()

    $videoFiles = Get-ChildItem -LiteralPath $videoRoot -File -Filter '*.mp4' |
        Where-Object { -not $excludedNames.ContainsKey($_.Name) }

    $textRoot = Join-Path $courseRoot 'Textos'
    $descriptionsByCode = @{}
    $textFiles = @(Get-ChildItem -LiteralPath $textRoot -File -Filter '*.txt' -ErrorAction SilentlyContinue)
    foreach ($textFile in $textFiles) {
        $textMetadata = Get-LessonMetadata $textFile -AllowMissingNumber
        $textKey = Get-SourceKey $textMetadata.code
        if (-not $descriptionsByCode.ContainsKey($textKey)) {
            $descriptionsByCode[$textKey] = Get-Content -LiteralPath $textFile.FullName -Raw -Encoding UTF8
        }
    }
    Write-Verbose "$($course.key): $($textFiles.Count) textos indexados"

    foreach ($file in $videoFiles) {
        Write-Verbose "$($course.key): lendo $($file.Name)"
        $metadata = Get-LessonMetadata $file
        if (-not $sectionByNumber.ContainsKey($metadata.lesson_number)) {
            $unresolved.Add($file.Name)
            continue
        }

        $duration = $cachedDurationByFile[$file.Name]
        if (-not $duration) {
            $duration = Get-VideoDuration -Shell $shell -File $file
        }
        if ($null -eq $duration) {
            $missingDuration.Add($file.Name)
        }

        $videoRows.Add([pscustomobject]@{
            file = $file
            lesson_number = $metadata.lesson_number
            code = $metadata.code
            base_title = $metadata.title
            part = $metadata.part
            duration = $duration
            section = $sectionByNumber[$metadata.lesson_number]
        })
    }

    $groupSizes = @{}
    Write-Verbose "$($course.key): agrupando partes"
    foreach ($group in ($videoRows | Group-Object { "$($_.lesson_number)|$($_.code)" })) {
        $groupSizes[$group.Name] = $group.Count
    }

    $lessons = [System.Collections.Generic.List[object]]::new()
    foreach ($section in ($course.sections | Sort-Object sort)) {
        Write-Verbose "$($course.key): montando seção $($section.key)"
        $sectionRows = @($videoRows | Where-Object section -eq $section.key |
            Sort-Object lesson_number, part, @{ Expression = { $_.file.Name } })
        $sort = 0
        foreach ($row in $sectionRows) {
            $sort++
            $groupKey = "$($row.lesson_number)|$($row.code)"
            $title = $row.base_title
            if ($groupSizes[$groupKey] -gt 1) {
                $title = "$title · Parte $($row.part)"
            }

            $lessons.Add([ordered]@{
                source_file = $row.file.Name
                source_path = Convert-ToRelativeUnixPath -BasePath $resolvedSourceRoot -TargetPath $row.file.FullName
                provider_id = $null
                title = $title
                section = [string] $section.key
                sort = $sort
                duration = $row.duration
                is_free = $false
                size_bytes = $row.file.Length
            })
        }
    }

    $sections = @($course.sections | Sort-Object sort | ForEach-Object {
        [ordered]@{ key = [string] $_.key; title = [string] $_.title; sort = [int] $_.sort }
    })

    $manifest = [ordered]@{
        version = 1
        source = [ordered]@{
            key = [string] $course.key
            root = $resolvedSourceRoot
            generated_at = [DateTimeOffset]::Now.ToString('o')
        }
        course = [ordered]@{ slug = $course.course_slug }
        provider = [ordered]@{
            driver = 'bunny_stream'
            library_id = [int] $catalog.library_id
            collection_id = [string] $course.collection_id
        }
        curriculum = [ordered]@{ sort_step = 100 }
        content = [ordered]@{
            quiz_attempts = [int] $course.content.quiz_attempts
            final_section = [string] $course.content.final_section
            section_overrides = $course.content.section_overrides
            pdf_path = "$($course.key)/PDFs"
            questions_path = "$($course.key)/Questoes"
            texts_path = "$($course.key)/Textos"
        }
        sections = $sections
        lessons = @($lessons)
    }

    Write-Verbose "$($course.key): serializando manifesto"
    [System.IO.File]::WriteAllText($outputPath, ($manifest | ConvertTo-Json -Depth 8), $utf8NoBom)
    Write-Verbose "$($course.key): manifesto salvo"

    $audit.Add([pscustomobject]@{
        course = $course.key
        videos = $lessons.Count
        excluded = $excludedNames.Count
        unresolved = $unresolved.Count
        missing_duration = $missingDuration.Count
        pdfs = @(Get-ChildItem -LiteralPath (Join-Path $courseRoot 'PDFs') -File -Filter '*.pdf').Count
        json = @(Get-ChildItem -LiteralPath (Join-Path $courseRoot 'Questoes') -File -Filter '*.json').Count
        texts = $textFiles.Count
        matched_texts = @($videoRows | ForEach-Object { Get-SourceKey $_.code } | Select-Object -Unique | Where-Object { $descriptionsByCode.ContainsKey($_) }).Count
        output = $outputPath
    })

    if ($unresolved.Count -gt 0) {
        Write-Warning "$($course.key): vídeos sem seção: $($unresolved -join '; ')"
    }
    if ($missingDuration.Count -gt 0) {
        Write-Warning "$($course.key): vídeos sem duração válida: $($missingDuration -join '; ')"
    }
}

$audit | Format-Table -AutoSize

$failed = @($audit | Where-Object { $_.unresolved -gt 0 -or $_.missing_duration -gt 0 })
if ($failed.Count -gt 0) {
    throw "A geração terminou com pendências. Corrija os arquivos/mapeamentos indicados antes do upload."
}
