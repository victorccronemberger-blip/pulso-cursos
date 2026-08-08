<?php

use Illuminate\Support\Str;

require dirname(__DIR__, 2).'/vendor/autoload.php';

$sourceRoot = $argv[1] ?? 'storage/app/course-imports/sources';

$sourceKey = static function (string $path): string {
    $name = pathinfo($path, PATHINFO_FILENAME);
    $name = preg_replace('/^\d+_/', '', $name);
    $code = explode('_', (string) $name, 2)[0];

    return Str::upper(preg_replace('/[^A-Z0-9]+/i', '', $code));
};

$normal = static function (string $value): string {
    $value = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');

    return trim(preg_replace('/\s+/', ' ', Str::upper(preg_replace('/[^\pL\pN]+/u', ' ', Str::ascii($value)))));
};

$failed = false;
foreach (glob(rtrim($sourceRoot, '/\\').'/*.json') ?: [] as $manifestPath) {
    $manifest = json_decode((string) file_get_contents($manifestPath), true, 512, JSON_THROW_ON_ERROR);
    $courseRoot = rtrim($manifest['source']['root'], '/\\').DIRECTORY_SEPARATOR.$manifest['source']['key'];
    $lessonKeys = array_fill_keys(array_map($sourceKey, array_column($manifest['lessons'], 'source_file')), true);
    $overrides = array_change_key_case($manifest['content']['section_overrides'] ?? [], CASE_UPPER);
    $sections = array_fill_keys(array_column($manifest['sections'], 'key'), true);
    $unresolved = [];

    foreach (glob($courseRoot.'/PDFs/*.pdf') ?: [] as $file) {
        $key = $sourceKey($file);
        if (! isset($lessonKeys[$key]) && ! isset($overrides[$key])) {
            $unresolved[] = basename($file);
        }
    }

    foreach (glob($courseRoot.'/Questoes/*.json') ?: [] as $file) {
        $payload = json_decode((string) file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
        $key = $sourceKey($file);
        $title = $normal((string) ($payload['titulo'] ?? ''));
        $section = $overrides[$key] ?? null;
        if (! $section && preg_match('/\bM([1-8])\b/u', $title, $match)) {
            $section = 'm'.$match[1];
        }
        if (! $section && str_contains($title, 'PRE PROVA')) {
            $section = $manifest['content']['final_section'] ?? null;
        }

        if (! isset($lessonKeys[$key]) && (! $section || ! isset($sections[$section]))) {
            $unresolved[] = basename($file);
        }
    }

    printf(
        "%-10s vídeos=%3d PDFs=%3d bancos=%3d não_resolvidos=%2d\n",
        $manifest['source']['key'],
        count($manifest['lessons']),
        count(glob($courseRoot.'/PDFs/*.pdf') ?: []),
        count(glob($courseRoot.'/Questoes/*.json') ?: []),
        count($unresolved),
    );
    foreach ($unresolved as $file) {
        echo "  - {$file}\n";
    }
    $failed = $failed || $unresolved !== [];
}

exit($failed ? 1 : 0);
