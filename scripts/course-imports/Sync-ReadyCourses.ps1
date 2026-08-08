[CmdletBinding()]
param(
    [Parameter()]
    [string] $SourceRoot = 'C:\Users\victo\Desktop\vidoes',

    [Parameter()]
    [string] $PhpExecutable = 'php',

    [Parameter()]
    [switch] $Force
)

$ErrorActionPreference = 'Stop'
$catalog = Get-Content -Raw -Encoding UTF8 'resources/course-imports/source-catalog-2026.json' | ConvertFrom-Json
$stateRoot = 'storage/app/course-imports/upload-state'
$sourceManifestRoot = 'storage/app/course-imports/sources'
$syncStateRoot = 'storage/app/course-imports/sync-state'
New-Item -ItemType Directory -Force -Path $syncStateRoot | Out-Null

function Invoke-ArtisanWithRetry {
    param(
        [string[]] $Arguments,
        [string] $Label
    )

    for ($attempt = 1; $attempt -le 3; $attempt++) {
        & $PhpExecutable artisan @Arguments
        if ($LASTEXITCODE -eq 0) {
            return
        }
        if ($attempt -lt 3) {
            $delay = $attempt * 3
            Write-Warning "$Label falhou (tentativa $attempt/3); nova tentativa em ${delay}s."
            Start-Sleep -Seconds $delay
        }
    }

    throw "$Label falhou após 3 tentativas"
}

# O finalizador retorna código não-zero enquanto alguma outra fila ainda está ativa.
& node 'scripts/course-imports/finalize-manifests.mjs'

foreach ($course in $catalog.courses) {
    if (-not $course.course_slug) {
        continue
    }

    $key = $course.key.ToLowerInvariant()
    $uploadStatePath = Join-Path $stateRoot "$key.json"
    $sourceManifestPath = Join-Path $sourceManifestRoot "$key.json"
    $finalManifestPath = "resources/course-imports/$key-2026.json"
    if (-not (Test-Path $uploadStatePath) -or -not (Test-Path $finalManifestPath)) {
        Write-Host "$($course.key): aguardando upload"
        continue
    }

    $uploadState = Get-Content -Raw -Encoding UTF8 $uploadStatePath | ConvertFrom-Json
    $sourceManifest = Get-Content -Raw -Encoding UTF8 $sourceManifestPath | ConvertFrom-Json
    $uploaded = @($uploadState.files.PSObject.Properties.Value | Where-Object status -eq 'uploaded').Count
    if ($uploaded -ne $sourceManifest.lessons.Count) {
        Write-Host "$($course.key): $uploaded/$($sourceManifest.lessons.Count) enviados"
        continue
    }

    $contentRoot = Join-Path $SourceRoot $course.key
    $materials = Join-Path $contentRoot 'PDFs'
    $questions = Join-Path $contentRoot 'Questoes'
    $contentMetadata = Get-ChildItem -File $materials, $questions | Sort-Object FullName |
        ForEach-Object { "$($_.FullName)|$($_.Length)|$($_.LastWriteTimeUtc.Ticks)" }
    $fingerprintInput = (Get-FileHash $finalManifestPath -Algorithm SHA256).Hash + ($contentMetadata -join "`n")
    $sha = [System.Security.Cryptography.SHA256]::Create()
    $fingerprint = ([BitConverter]::ToString($sha.ComputeHash([Text.Encoding]::UTF8.GetBytes($fingerprintInput)))).Replace('-', '')
    $markerPath = Join-Path $syncStateRoot "$key.json"
    if (-not $Force -and (Test-Path $markerPath)) {
        $marker = Get-Content -Raw -Encoding UTF8 $markerPath | ConvertFrom-Json
        if ($marker.fingerprint -eq $fingerprint) {
            Write-Host "$($course.key): sincronização já confirmada"
            continue
        }
    }

    Write-Host "$($course.key): validando e sincronizando currículo"
    Invoke-ArtisanWithRetry @('courses:sync-videos', $finalManifestPath, '--dry-run') "$($course.key): dry-run de vídeos"
    Invoke-ArtisanWithRetry @('courses:sync-videos', $finalManifestPath) "$($course.key): sincronização de vídeos"

    Write-Host "$($course.key): validando e sincronizando materiais e simulados"
    Invoke-ArtisanWithRetry @('courses:sync-content', $finalManifestPath, "--materials=$materials", "--questions=$questions", '--dry-run') "$($course.key): dry-run de conteúdo"
    Invoke-ArtisanWithRetry @('courses:sync-content', $finalManifestPath, "--materials=$materials", "--questions=$questions", '--prune') "$($course.key): sincronização de conteúdo"

    $marker = [ordered]@{
        course = $course.key
        fingerprint = $fingerprint
        synchronized_at = [DateTimeOffset]::Now.ToString('o')
        videos = $sourceManifest.lessons.Count
    }
    [System.IO.File]::WriteAllText(
        (Join-Path (Get-Location) $markerPath),
        ($marker | ConvertTo-Json -Depth 4),
        (New-Object System.Text.UTF8Encoding($false))
    )
}
