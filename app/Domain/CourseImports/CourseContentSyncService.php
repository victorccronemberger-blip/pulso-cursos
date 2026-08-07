<?php

namespace App\Domain\CourseImports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class CourseContentSyncService
{
    public function sync(
        CourseVideoManifest $manifest,
        ?string $materialsDirectory,
        ?string $questionsDirectory,
        bool $dryRun = false,
        bool $prune = false,
    ): array {
        $course = DB::table('courses')->where('slug', $manifest->courseSlug())->first();
        if (! $course) {
            throw new RuntimeException("Course not found: {$manifest->courseSlug()}");
        }

        $sections = $this->sections($manifest, (int) $course->id);
        $lessons = $this->lessonIndex($manifest, (int) $course->id, $sections);
        $content = $manifest->content();
        $report = ['materials' => 0, 'quizzes' => 0, 'questions' => 0, 'unresolved' => []];

        if ($materialsDirectory) {
            foreach ($this->files($materialsDirectory, 'pdf') as $file) {
                $metadata = $this->resolve($file, null, $content, $sections, $lessons);
                if (! $metadata) {
                    $report['unresolved'][] = basename($file);
                    continue;
                }
                $report['materials']++;
                if (! $dryRun) {
                    $this->upsertMaterial((int) $course->id, $file, $metadata);
                }
            }
        }

        $activeQuizSources = [];
        if ($questionsDirectory) {
            $placement = [];
            foreach ($this->files($questionsDirectory, 'json') as $file) {
                $payload = json_decode((string) file_get_contents($file), true);
                if (! is_array($payload) || empty($payload['titulo']) || empty($payload['questions'])) {
                    $report['unresolved'][] = basename($file);
                    continue;
                }

                $metadata = $this->resolve($file, (string) $payload['titulo'], $content, $sections, $lessons);
                if (! $metadata) {
                    $report['unresolved'][] = basename($file);
                    continue;
                }

                $sourceKey = $this->sourceKey($file);
                $activeQuizSources[] = $sourceKey;
                $slot = ($metadata['lesson_id'] ?: 'section-'.$metadata['section_id']).'-'.$metadata['kind'];
                $placement[$slot] = ($placement[$slot] ?? 0) + 1;
                $metadata['sort'] = $this->quizSort($metadata, $lessons, $placement[$slot]);
                $report['quizzes']++;
                $report['questions'] += count($payload['questions']);

                if (! $dryRun) {
                    $this->upsertQuiz((int) $course->id, (int) $course->user_id, $file, $payload, $metadata, $content);
                }
            }
        }

        if ($prune && ! $dryRun) {
            $this->prune((int) $course->id, $activeQuizSources);
        }

        return $report;
    }

    private function sections(CourseVideoManifest $manifest, int $courseId): array
    {
        $result = [];
        foreach ($manifest->sections() as $section) {
            $id = DB::table('sections')->where('course_id', $courseId)->where('sort', $section['sort'])->value('id');
            if (! $id) {
                throw new RuntimeException("Section is not synchronized: {$section['key']}");
            }
            $result[$section['key']] = (int) $id;
        }
        return $result;
    }

    private function lessonIndex(CourseVideoManifest $manifest, int $courseId, array $sections): array
    {
        $byCode = $byTitle = $byId = [];
        foreach ($manifest->lessons() as $lesson) {
            $sort = (int) $lesson['sort'] * $manifest->curriculumSortStep();
            $row = DB::table('lessons')
                ->where('course_id', $courseId)
                ->where('section_id', $sections[$lesson['section']])
                ->where('sort', $sort)
                ->where('lesson_type', '!=', 'quiz')
                ->first();
            if (! $row) {
                continue;
            }
            $item = ['id' => (int) $row->id, 'section_id' => (int) $row->section_id, 'sort' => (int) $row->sort];
            $byCode[$this->sourceKey((string) $lesson['source_file'])] = $item;
            $byTitle[$this->normal((string) $lesson['title'])] = $item;
            $byId[$item['id']] = $item;
        }
        return compact('byCode', 'byTitle', 'byId');
    }

    private function resolve(string $file, ?string $title, array $content, array $sections, array $lessons): ?array
    {
        $sourceKey = $this->sourceKey($file);
        $normalTitle = $this->normal($title ?: pathinfo($file, PATHINFO_FILENAME));
        $kind = str_contains($normalTitle, 'SIMULADO') ? 'module' : (str_contains($normalTitle, 'PRE PROVA') ? 'final' : 'topic');
        $lesson = $lessons['byCode'][$sourceKey] ?? $lessons['byTitle'][$normalTitle] ?? null;
        if ($kind !== 'topic') {
            $lesson = null;
        }

        $sectionKey = null;
        if ($lesson) {
            $sectionId = $lesson['section_id'];
        } else {
            $overrides = array_change_key_case($content['section_overrides'] ?? [], CASE_UPPER);
            $sectionKey = $overrides[$sourceKey] ?? null;
            if (! $sectionKey && preg_match('/\bM([1-8])\b/u', $normalTitle, $match)) {
                $sectionKey = 'm'.$match[1];
            }
            if (! $sectionKey && $kind === 'final') {
                $sectionKey = (string) ($content['final_section'] ?? '');
            }
            $sectionId = $sections[$sectionKey] ?? null;
        }

        if (! $sectionId) {
            return null;
        }

        return [
            'source_key' => $sourceKey,
            'section_id' => (int) $sectionId,
            'lesson_id' => $lesson['id'] ?? null,
            'lesson_sort' => $lesson['sort'] ?? null,
            'kind' => $kind,
        ];
    }

    private function quizSort(array $metadata, array $lessons, int $offset): int
    {
        if ($metadata['lesson_sort']) {
            return (int) $metadata['lesson_sort'] + min(89, 9 + $offset);
        }
        $max = collect($lessons['byId'])->where('section_id', $metadata['section_id'])->max('sort') ?: 0;
        $base = match ($metadata['kind']) {
            'final' => 90,
            'module' => 70,
            default => 40,
        };
        return (int) $max + $base + $offset;
    }

    private function upsertMaterial(int $courseId, string $file, array $metadata): void
    {
        $fileName = basename($file);
        $title = preg_replace('/^\d+_/', '', pathinfo($fileName, PATHINFO_FILENAME));
        $values = [
            'section_id' => $metadata['section_id'],
            'lesson_id' => $metadata['lesson_id'],
            'title' => $this->displayTitle((string) $title),
            'file_name' => $fileName,
            'mime_type' => 'application/pdf',
            'size_bytes' => filesize($file),
            'contents' => file_get_contents($file),
            'updated_at' => now(),
        ];
        $existing = DB::table('course_materials')->where('course_id', $courseId)->where('source_key', $metadata['source_key'])->value('id');
        $existing
            ? DB::table('course_materials')->where('id', $existing)->update($values)
            : DB::table('course_materials')->insert($values + ['course_id' => $courseId, 'source_key' => $metadata['source_key'], 'created_at' => now()]);
    }

    private function upsertQuiz(int $courseId, int $userId, string $file, array $payload, array $metadata, array $content): void
    {
        DB::transaction(function () use ($courseId, $userId, $file, $payload, $metadata, $content) {
            $source = 'question-bank:'.$metadata['source_key'];
            $questionCount = count($payload['questions']);
            $minutes = max(10, min(120, $questionCount));
            $values = [
                'title' => $this->displayTitle((string) $payload['titulo']),
                'user_id' => $userId,
                'section_id' => $metadata['section_id'],
                'lesson_type' => 'quiz',
                'duration' => sprintf('%02d:%02d:00', intdiv($minutes, 60), $minutes % 60),
                'total_mark' => $questionCount,
                'pass_mark' => max(1, (int) ceil($questionCount * .7)),
                'retake' => (int) ($content['quiz_attempts'] ?? 5),
                'lesson_src' => $source,
                'sort' => $metadata['sort'],
                'description' => $metadata['kind'] === 'topic' ? 'Prática vinculada ao conteúdo estudado.' : 'Simulado para consolidar o módulo.',
                'status' => 1,
                'updated_at' => now(),
            ];
            $quizId = DB::table('lessons')->where('course_id', $courseId)->where('lesson_src', $source)->value('id');
            if ($quizId) {
                DB::table('lessons')->where('id', $quizId)->update($values);
            } else {
                $quizId = DB::table('lessons')->insertGetId($values + ['course_id' => $courseId, 'created_at' => now()]);
            }

            $rows = [];
            foreach ($payload['questions'] as $index => $question) {
                $answers = collect($question['answers'] ?? []);
                $options = $answers->pluck('resposta')->map(fn ($value) => $this->plainText((string) $value))->filter()->values();
                $correct = $answers->filter(fn ($answer) => (int) ($answer['correta'] ?? 0) === 1)
                    ->map(fn ($answer) => $this->plainText((string) ($answer['resposta'] ?? '')))->filter()->values();
                if ($options->isEmpty() || $correct->isEmpty()) {
                    continue;
                }
                $rows[] = [
                    'quiz_id' => $quizId,
                    'title' => $this->plainText((string) ($question['enunciado'] ?? '')),
                    'type' => 'mcq',
                    'answer' => $correct->toJson(JSON_UNESCAPED_UNICODE),
                    'options' => $options->toJson(JSON_UNESCAPED_UNICODE),
                    'sort' => $index + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $existingQuestions = DB::table('questions')->where('quiz_id', $quizId)->pluck('id', 'sort');
            $activeSorts = [];
            foreach ($rows as $row) {
                $activeSorts[] = $row['sort'];
                $existingId = $existingQuestions[$row['sort']] ?? null;
                if ($existingId) {
                    unset($row['created_at']);
                    DB::table('questions')->where('id', $existingId)->update($row);
                } else {
                    DB::table('questions')->insert($row);
                }
            }
            DB::table('questions')->where('quiz_id', $quizId)->whereNotIn('sort', $activeSorts)->delete();

            DB::table('course_quiz_contexts')->updateOrInsert(
                ['course_id' => $courseId, 'source_key' => $metadata['source_key']],
                [
                    'section_id' => $metadata['section_id'],
                    'lesson_id' => $metadata['lesson_id'],
                    'quiz_lesson_id' => $quizId,
                    'kind' => $metadata['kind'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        });
    }

    private function prune(int $courseId, array $activeQuizSources): void
    {
        $contexts = DB::table('course_quiz_contexts')->where('course_id', $courseId)->whereNotIn('source_key', $activeQuizSources)->get();
        foreach ($contexts as $context) {
            DB::table('questions')->where('quiz_id', $context->quiz_lesson_id)->delete();
            DB::table('lessons')->where('id', $context->quiz_lesson_id)->where('lesson_src', 'like', 'question-bank:%')->delete();
            DB::table('course_quiz_contexts')->where('id', $context->id)->delete();
        }
    }

    private function files(string $directory, string $extension): array
    {
        if (! is_dir($directory)) {
            throw new RuntimeException("Content directory not found: {$directory}");
        }
        $files = glob(rtrim($directory, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'*.'.$extension) ?: [];
        natsort($files);
        return array_values($files);
    }

    private function sourceKey(string $path): string
    {
        $name = pathinfo($path, PATHINFO_FILENAME);
        $name = preg_replace('/^\d+_/', '', $name);
        $code = explode('_', (string) $name, 2)[0];
        return Str::upper(preg_replace('/[^A-Z0-9]+/i', '', $code));
    }

    private function normal(string $value): string
    {
        return trim(preg_replace('/\s+/', ' ', Str::upper(preg_replace('/[^\pL\pN]+/u', ' ', Str::ascii($this->plainText($value))))));
    }

    private function plainText(string $value): string
    {
        $value = preg_replace('/<\s*\/?(?:p|div|li|ul|ol|br|h[1-6]|tr|td|th)[^>]*>/iu', ' ', $value);
        return trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
    }

    private function displayTitle(string $value): string
    {
        return trim(preg_replace('/\s+/', ' ', str_replace('_', ' ', $this->plainText($value))));
    }
}
