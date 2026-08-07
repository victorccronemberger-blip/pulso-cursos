<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportCfpQuestions extends Command
{
    protected $signature = 'cfp:import-questions
        {source : Pasta que contém os arquivos JSON do CFP}
        {--course=2 : ID do curso CFP}
        {--user=4 : ID do autor/instrutor}
        {--dry-run : Apenas valida os arquivos, sem gravar}';

    protected $description = 'Importa questões CFP em quizzes nativos, sem embeds legados de vídeo.';

    public function handle(): int
    {
        $source = rtrim((string) $this->argument('source'), DIRECTORY_SEPARATOR);
        if (! is_dir($source)) {
            $this->error("Pasta não encontrada: {$source}");
            return self::FAILURE;
        }

        $courseId = (int) $this->option('course');
        $userId = (int) $this->option('user');
        $sections = DB::table('sections')->where('course_id', $courseId)->pluck('id', 'title');
        if ($sections->isEmpty()) {
            $this->error('O curso não possui módulos; crie-os antes de importar as questões.');
            return self::FAILURE;
        }

        $files = glob($source . DIRECTORY_SEPARATOR . '*.json') ?: [];
        sort($files, SORT_NATURAL | SORT_FLAG_CASE);
        $quizCount = 0;
        $questionCount = 0;

        foreach ($files as $file) {
            $payload = json_decode((string) file_get_contents($file), true);
            if (! is_array($payload) || empty($payload['questions']) || empty($payload['titulo'])) {
                $this->warn('Ignorado: ' . basename($file));
                continue;
            }

            $sectionId = $this->sectionFor((string) ($payload['titulo'] ?? ''), $sections->all());
            if (! $sectionId) {
                $this->warn('Sem módulo correspondente: ' . basename($file));
                continue;
            }

            $title = trim(strip_tags((string) $payload['titulo']));
            if ($this->option('dry-run')) {
                $this->line("{$title}: " . count($payload['questions']) . ' questões');
                $quizCount++;
                $questionCount += count($payload['questions']);
                continue;
            }

            DB::transaction(function () use ($payload, $title, $sectionId, $userId, &$quizCount, &$questionCount) {
                $quiz = DB::table('quizzes')->where('section_id', $sectionId)->where('title', $title)->first();
                $quizId = $quiz?->id;
                if (! $quizId) {
                    $quizId = DB::table('quizzes')->insertGetId([
                        'user_id' => $userId, 'title' => $title, 'section_id' => $sectionId,
                        'duration' => '00:30:00', 'total_mark' => count($payload['questions']),
                        'pass_mark' => max(1, (int) ceil(count($payload['questions']) * .7)),
                        'sort' => 999, 'created_at' => now(), 'updated_at' => now(),
                    ]);
                    $quizCount++;
                }

                foreach ($payload['questions'] as $index => $question) {
                    $answers = collect($question['answers'] ?? []);
                    $options = $answers->pluck('resposta')->map(fn ($value) => trim(strip_tags((string) $value)))->values();
                    $correct = $answers->filter(fn ($answer) => (int) ($answer['correta'] ?? 0) === 1)
                        ->map(fn ($answer) => trim(strip_tags((string) ($answer['resposta'] ?? ''))))->values();
                    if ($options->isEmpty() || $correct->isEmpty()) continue;

                    $title = (string) ($question['enunciado'] ?? '');
                    if (DB::table('questions')->where('quiz_id', $quizId)->where('title', $title)->exists()) continue;
                    DB::table('questions')->insert([
                        'quiz_id' => $quizId, 'title' => $title, 'type' => 'mcq',
                        'answer' => $correct->toJson(JSON_UNESCAPED_UNICODE),
                        'options' => $options->toJson(JSON_UNESCAPED_UNICODE), 'sort' => $index + 1,
                        'created_at' => now(), 'updated_at' => now(),
                    ]);
                    $questionCount++;
                }
            });
        }

        $this->info("Quizzes processados: {$quizCount}; questões processadas: {$questionCount}.");
        return self::SUCCESS;
    }

    private function sectionFor(string $title, array $sections): ?int
    {
        preg_match('/\(M([1-8])\)/i', $title, $match);
        if (! empty($match[1])) {
            foreach ($sections as $name => $id) if (Str::startsWith($name, 'Módulo ' . $match[1])) return (int) $id;
        }
        return Str::contains(Str::upper($title), 'PRÉ-PROVA') ? (int) reset($sections) : null;
    }
}
