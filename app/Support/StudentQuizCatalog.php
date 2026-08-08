<?php

namespace App\Support;

use App\Models\Lesson;
use App\Models\QuizSubmission;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class StudentQuizCatalog
{
    private ?Collection $courseOptions = null;

    public function __construct(
        private readonly int $userId,
        private readonly Collection $enrolledCourseIds,
    ) {
    }

    public function courses(): Collection
    {
        if ($this->courseOptions !== null) {
            return $this->courseOptions;
        }

        $attemptedQuizzes = QuizSubmission::query()
            ->select('quiz_id')
            ->where('user_id', $this->userId)
            ->groupBy('quiz_id');

        $this->courseOptions = Lesson::query()
            ->join('course_quiz_contexts', 'course_quiz_contexts.quiz_lesson_id', '=', 'lessons.id')
            ->join('courses', 'courses.id', '=', 'lessons.course_id')
            ->leftJoinSub($attemptedQuizzes, 'attempted_quizzes', function ($join) {
                $join->on('attempted_quizzes.quiz_id', '=', 'lessons.id');
            })
            ->whereIn('lessons.course_id', $this->enrolledCourseIds)
            ->where('lessons.lesson_type', 'quiz')
            ->where('lessons.status', 1)
            ->groupBy('courses.id', 'courses.title', 'courses.slug', 'courses.thumbnail')
            ->orderBy('courses.title')
            ->get([
                'courses.id as course_id',
                'courses.title',
                'courses.slug',
                'courses.thumbnail',
                DB::raw('COUNT(DISTINCT lessons.id) as quiz_count'),
                DB::raw('COUNT(DISTINCT attempted_quizzes.quiz_id) as attempted_count'),
            ])
            ->map(fn ($course) => [
                'id' => (int) $course->course_id,
                'title' => $course->title,
                'slug' => $course->slug,
                'thumbnail' => $course->thumbnail,
                'quiz_count' => (int) $course->quiz_count,
                'attempted_count' => (int) $course->attempted_count,
            ]);

        return $this->courseOptions;
    }

    public function resolveCourseId(?int $requestedCourseId): ?int
    {
        $courses = $this->courses();

        if ($requestedCourseId && $courses->contains('id', $requestedCourseId)) {
            return $requestedCourseId;
        }

        return $courses->first()['id'] ?? null;
    }

    public function course(?int $courseId, ?string $search = null): ?array
    {
        if (!$courseId) {
            return null;
        }

        $courseOption = $this->courses()->firstWhere('id', $courseId);
        if (!$courseOption) {
            return null;
        }

        $quizzes = Lesson::query()
            ->join('course_quiz_contexts', 'course_quiz_contexts.quiz_lesson_id', '=', 'lessons.id')
            ->join('sections', 'sections.id', '=', 'lessons.section_id')
            ->select([
                'lessons.*',
                'course_quiz_contexts.kind as context_kind',
                'sections.title as section_title',
                'sections.sort as section_sort',
            ])
            ->where('lessons.course_id', $courseId)
            ->where('lessons.lesson_type', 'quiz')
            ->where('lessons.status', 1)
            ->when($search, function ($query, $search) {
                $query->where(function ($nested) use ($search) {
                    $nested->where('lessons.title', 'like', "%{$search}%")
                        ->orWhere('sections.title', 'like', "%{$search}%");
                });
            })
            ->orderBy('sections.sort')
            ->orderBy('lessons.sort')
            ->get();

        $this->attachProgress($quizzes);

        return [
            ...$courseOption,
            'passed_count' => $quizzes->where('status_key', 'passed')->count(),
            'modules' => $quizzes->groupBy('section_id')->map(function ($moduleQuizzes) {
                $firstQuiz = $moduleQuizzes->first();

                return [
                    'id' => (int) $firstQuiz->section_id,
                    'title' => $firstQuiz->section_title,
                    'sort' => (int) $firstQuiz->section_sort,
                    'quizzes' => $moduleQuizzes,
                ];
            })->sortBy('sort')->values(),
        ];
    }

    private function attachProgress(Collection $quizzes): void
    {
        $quizIds = $quizzes->pluck('id');
        $questionCounts = DB::table('questions')
            ->whereIn('quiz_id', $quizIds)
            ->select('quiz_id', DB::raw('COUNT(*) as total'))
            ->groupBy('quiz_id')
            ->pluck('total', 'quiz_id');
        $submissions = QuizSubmission::query()
            ->where('user_id', $this->userId)
            ->whereIn('quiz_id', $quizIds)
            ->latest('created_at')
            ->get()
            ->groupBy('quiz_id');

        $quizzes->each(function (Lesson $quiz) use ($questionCounts, $submissions) {
            $progress = QuizProgress::calculate(
                submissions: $submissions->get($quiz->id, collect()),
                questionCount: (int) ($questionCounts[$quiz->id] ?? 0),
                retake: (int) $quiz->retake,
                totalMark: (int) $quiz->total_mark,
                passMark: (int) $quiz->pass_mark,
            );

            foreach ($progress as $key => $value) {
                $quiz->setAttribute($key, $value);
            }
        });
    }
}
