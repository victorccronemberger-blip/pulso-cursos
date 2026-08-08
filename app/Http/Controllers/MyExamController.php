<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\FileUploader;
use App\Models\Lesson;
use App\Models\QuizSubmission;
use App\Models\Submission;
use App\Support\CourseAccess;
use App\Support\QuizProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MyExamController extends Controller
{
    public function index(Request $request)
    {
        $courseIds = Enrollment::query()
            ->where('user_id', auth()->id())
            ->where(function ($query) {
                $query->whereNull('expiry_date')->orWhere('expiry_date', '>=', time());
            })
            ->pluck('course_id')
            ->unique()
            ->values();

        $quizzes = Lesson::query()
            ->join('course_quiz_contexts', 'course_quiz_contexts.quiz_lesson_id', '=', 'lessons.id')
            ->join('courses', 'courses.id', '=', 'lessons.course_id')
            ->join('sections', 'sections.id', '=', 'lessons.section_id')
            ->select([
                'lessons.*',
                'course_quiz_contexts.kind as context_kind',
                'courses.title as course_title',
                'courses.slug as course_slug',
                'courses.thumbnail as course_thumbnail',
                'sections.title as section_title',
                'sections.sort as section_sort',
            ])
            ->whereIn('lessons.course_id', $courseIds)
            ->where('lessons.lesson_type', 'quiz')
            ->where('lessons.status', 1)
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim((string) $request->search);
                $query->where(function ($nested) use ($search) {
                    $nested->where('lessons.title', 'like', "%{$search}%")
                        ->orWhere('sections.title', 'like', "%{$search}%")
                        ->orWhere('courses.title', 'like', "%{$search}%");
                });
            })
            ->orderBy('courses.title')
            ->orderBy('sections.sort')
            ->orderBy('lessons.sort')
            ->get();

        $quizIds = $quizzes->pluck('id');
        $questionCounts = DB::table('questions')
            ->whereIn('quiz_id', $quizIds)
            ->select('quiz_id', DB::raw('COUNT(*) as total'))
            ->groupBy('quiz_id')
            ->pluck('total', 'quiz_id');
        $submissions = QuizSubmission::query()
            ->where('user_id', auth()->id())
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

        $quizCourses = $quizzes->groupBy('course_id')->map(function ($courseQuizzes) {
            $first = $courseQuizzes->first();

            return [
                'id' => (int) $first->course_id,
                'title' => $first->course_title,
                'slug' => $first->course_slug,
                'thumbnail' => $first->course_thumbnail,
                'quiz_count' => $courseQuizzes->count(),
                'attempted_count' => $courseQuizzes->where('attempt_count', '>', 0)->count(),
                'modules' => $courseQuizzes->groupBy('section_id')->map(function ($moduleQuizzes) {
                    $firstQuiz = $moduleQuizzes->first();

                    return [
                        'id' => (int) $firstQuiz->section_id,
                        'title' => $firstQuiz->section_title,
                        'sort' => (int) $firstQuiz->section_sort,
                        'quizzes' => $moduleQuizzes,
                    ];
                })->sortBy('sort')->values(),
            ];
        })->values();

        $exams = Exam::with(['course', 'creator', 'mySubmission'])
            ->whereIn('course_id', $courseIds)
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%' . trim((string) $request->search) . '%'))
            ->orderByDesc('start_at')
            ->paginate(6)
            ->withQueryString();

        $summary = [
            'courses' => $quizCourses->count(),
            'quizzes' => $quizzes->count(),
            'attempted' => $quizzes->where('attempt_count', '>', 0)->count(),
            'passed' => $quizzes->where('status_key', 'passed')->count(),
        ];

        return view('frontend.default.student.my_exam.index', compact('quizCourses', 'exams', 'summary'));
    }

    public function details(Exam $exam, CourseAccess $courseAccess)
    {
        abort_unless($courseAccess->allows(auth()->user(), $exam->course_id), 403);

        $submission = Submission::where('exam_id', $exam->id)
            ->where('student_id', Auth::id())
            ->first();

        return view('frontend.default.student.my_exam.details', compact('exam', 'submission'));
    }

    public function submit(Request $request, $exam_id, CourseAccess $courseAccess)
    {
        $exam = Exam::findOrFail($exam_id);
        abort_unless($courseAccess->allows(auth()->user(), $exam->course_id), 403);

        $now = Carbon::now();

        if ($exam->start_at && $now->lt(Carbon::parse($exam->start_at))) {
            return back()->with('error', 'Esta prova ainda não começou.');
        }

        if ($exam->end_at && $now->gt(Carbon::parse($exam->end_at))) {
            return back()->with('error', 'O prazo para entregar esta prova terminou.');
        }

        if (Submission::where('exam_id', $exam->id)->where('student_id', Auth::id())->exists()) {
            return back()->with('error', 'Você já entregou esta prova.');
        }

        $request->validate(['answer_script' => 'required|mimes:pdf,doc,docx|max:10240']);

        $path = 'uploads/exam_submissions/' . nice_file_name('answer_' . Auth::id(), $request->file('answer_script')->extension());
        FileUploader::upload($request->file('answer_script'), $path);

        Submission::create([
            'exam_id' => $exam->id,
            'student_id' => Auth::id(),
            'submitted_pdf' => $path,
            'status' => 'checking',
            'submitted_at' => now(),
        ]);

        return redirect()->route('my.exam.details', $exam->id)->with('success', 'Prova entregue com sucesso.');
    }
}
