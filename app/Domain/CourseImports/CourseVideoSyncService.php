<?php

namespace App\Domain\CourseImports;

use App\Domain\CourseImports\Contracts\VideoProvider;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class CourseVideoSyncService
{
    /** @var array<string, VideoProvider> */
    private array $providers;

    public function __construct()
    {
        $this->providers = [];
        foreach (config('course-imports.video_providers', []) as $driver => $providerClass) {
            $provider = app($providerClass);
            if (! $provider instanceof VideoProvider || $provider->name() !== $driver) {
                throw new InvalidArgumentException("Invalid video provider registration: {$driver}");
            }
            $this->providers[$driver] = $provider;
        }
    }

    public function sync(CourseVideoManifest $manifest, bool $dryRun = false, bool $prune = false): array
    {
        $course = DB::table('courses')->where('slug', $manifest->courseSlug())->first();
        if (! $course) {
            throw new RuntimeException("Course not found: {$manifest->courseSlug()}");
        }

        $providerConfig = $manifest->provider();
        $provider = $this->providers[$providerConfig['driver']] ?? null;
        if (! $provider) {
            throw new InvalidArgumentException("Unsupported video provider: {$providerConfig['driver']}");
        }

        $sources = array_map(
            fn (array $lesson) => $provider->source($providerConfig, $lesson),
            $manifest->lessons()
        );

        $report = [
            'course_id' => (int) $course->id,
            'sections' => count($manifest->sections()),
            'lessons' => count($manifest->lessons()),
            'provider' => $provider->name(),
            'pruned' => 0,
        ];

        if ($dryRun) {
            return $report;
        }

        return DB::transaction(function () use ($manifest, $providerConfig, $provider, $course, $sources, $prune, $report) {
            DB::table('courses')->where('id', $course->id)->lockForUpdate()->first();

            $sectionIds = [];
            foreach ($manifest->sections() as $section) {
                $existing = DB::table('sections')
                    ->where('course_id', $course->id)
                    ->where('sort', (int) $section['sort'])
                    ->first();

                $values = [
                    'user_id' => (int) $course->user_id,
                    'course_id' => (int) $course->id,
                    'title' => trim((string) $section['title']),
                    'sort' => (int) $section['sort'],
                    'updated_at' => now(),
                ];

                if ($existing) {
                    DB::table('sections')->where('id', $existing->id)->update($values);
                    $sectionIds[$section['key']] = (int) $existing->id;
                } else {
                    $values['created_at'] = now();
                    $sectionIds[$section['key']] = DB::table('sections')->insertGetId($values);
                }
            }

            foreach ($manifest->lessons() as $lesson) {
                $source = $provider->source($providerConfig, $lesson);
                $identity = ['course_id' => (int) $course->id, 'lesson_src' => $source];
                $values = [
                    'title' => trim((string) $lesson['title']),
                    'user_id' => (int) $course->user_id,
                    'section_id' => $sectionIds[$lesson['section']],
                    'lesson_type' => $provider->lessonType(),
                    'duration' => $lesson['duration'],
                    'video_type' => $provider->name(),
                    'is_free' => (int) ($lesson['is_free'] ?? false),
                    'sort' => (int) $lesson['sort'] * $manifest->curriculumSortStep(),
                    'status' => 1,
                    'updated_at' => now(),
                ];

                $existing = DB::table('lessons')->where($identity)->exists();
                if ($existing) {
                    DB::table('lessons')->where($identity)->update($values);
                } else {
                    DB::table('lessons')->insert($identity + $values + ['created_at' => now()]);
                }
            }

            if ($prune) {
                $report['pruned'] = DB::table('lessons')
                    ->where('course_id', $course->id)
                    ->where('lesson_type', $provider->lessonType())
                    ->whereNotIn('lesson_src', $sources)
                    ->delete();
            }

            return $report;
        });
    }
}
