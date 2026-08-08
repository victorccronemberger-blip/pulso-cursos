<?php

use App\Domain\CourseImports\QuestionBankNormalizer;

require dirname(__DIR__, 2).'/vendor/autoload.php';

$root = $argv[1] ?? 'C:/Users/victo/Desktop/vidoes';
$normalizer = new QuestionBankNormalizer;

foreach (glob(rtrim($root, '/\\').'/*', GLOB_ONLYDIR) ?: [] as $courseDirectory) {
    $files = $raw = $valid = 0;
    foreach (glob($courseDirectory.'/Questoes/*.json') ?: [] as $file) {
        $payload = json_decode((string) file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
        $questions = is_array($payload['questions'] ?? null) ? $payload['questions'] : [];
        $files++;
        $raw += count($questions);
        $valid += count($normalizer->normalize($questions));
    }

    printf(
        "%-10s arquivos=%3d brutas=%4d válidas=%4d ignoradas=%3d\n",
        basename($courseDirectory),
        $files,
        $raw,
        $valid,
        $raw - $valid,
    );
}
