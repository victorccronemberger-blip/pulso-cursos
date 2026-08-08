<?php

namespace App\Console\Commands;

use App\Domain\CourseImports\CourseContentSyncService;
use App\Domain\CourseImports\CourseVideoManifest;
use Illuminate\Console\Command;

class SyncCourseContent extends Command
{
    protected $signature = 'courses:sync-content
        {manifest : Caminho do manifesto JSON do curso}
        {--materials= : Pasta com apostilas PDF}
        {--questions= : Pasta com bancos JSON}
        {--dry-run : Valida e mostra os totais sem gravar}
        {--prune : Remove quizzes importados que não estejam mais na origem}';

    protected $description = 'Sincroniza apostilas e simulados contextuais de um curso.';

    public function handle(CourseContentSyncService $service): int
    {
        $report = $service->sync(
            CourseVideoManifest::fromFile((string) $this->argument('manifest')),
            $this->option('materials') ?: null,
            $this->option('questions') ?: null,
            (bool) $this->option('dry-run'),
            (bool) $this->option('prune'),
        );

        $this->table(['Apostilas', 'Simulados', 'Questões válidas', 'Questões ignoradas', 'Não resolvidos'], [[
            $report['materials'],
            $report['quizzes'],
            $report['questions'],
            $report['skipped_questions'],
            count($report['unresolved']),
        ]]);
        foreach ($report['unresolved'] as $file) {
            $this->warn("Sem vínculo: {$file}");
        }

        return $report['unresolved'] === [] ? self::SUCCESS : self::FAILURE;
    }
}
