<?php

namespace App\Console\Commands;

use App\Domain\CourseImports\CourseVideoManifest;
use App\Domain\CourseImports\CourseVideoSyncService;
use Illuminate\Console\Command;
use Throwable;

class SyncCourseVideos extends Command
{
    protected $signature = 'courses:sync-videos
        {manifest : Absolute path or path relative to the project root}
        {--dry-run : Validate and report without writing}
        {--prune : Remove provider lessons absent from the manifest}';

    protected $description = 'Synchronize a course video curriculum from a versioned manifest.';

    public function handle(CourseVideoSyncService $service): int
    {
        $path = (string) $this->argument('manifest');
        if (! str_starts_with($path, DIRECTORY_SEPARATOR) && ! preg_match('/^[A-Za-z]:[\\\\\/]/', $path)) {
            $path = base_path($path);
        }

        try {
            $report = $service->sync(
                CourseVideoManifest::fromFile($path),
                (bool) $this->option('dry-run'),
                (bool) $this->option('prune')
            );
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());
            return self::FAILURE;
        }

        $this->table(['Course', 'Sections', 'Lessons', 'Provider', 'Pruned'], [[
            $report['course_id'], $report['sections'], $report['lessons'], $report['provider'], $report['pruned'],
        ]]);
        $this->info($this->option('dry-run') ? 'Manifest validated; no data written.' : 'Course videos synchronized.');

        return self::SUCCESS;
    }
}
