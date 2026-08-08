<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\FileUploader;
use App\Models\Submission;
use App\Support\CourseAccess;
use App\Support\StudentQuizCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

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

        $quizCatalog = new StudentQuizCatalog((int) auth()->id(), $courseIds);
        $quizCourses = $quizCatalog->courses();
        $activeCourseId = $quizCatalog->resolveCourseId($request->integer('course') ?: null);
        $activeQuizCourse = $quizCatalog->course(
            $activeCourseId,
            $request->filled('search') ? trim((string) $request->search) : null,
        );

        $exams = Exam::with(['course', 'creator', 'mySubmission'])
            ->whereIn('course_id', $courseIds)
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%' . trim((string) $request->search) . '%'))
            ->orderByDesc('start_at')
            ->paginate(6)
            ->withQueryString();

        $summary = [
            'courses' => $quizCourses->count(),
            'quizzes' => $quizCourses->sum('quiz_count'),
            'attempted' => $quizCourses->sum('attempted_count'),
            'active_passed' => $activeQuizCourse['passed_count'] ?? 0,
        ];

        return view('frontend.default.student.my_exam.index', compact(
            'quizCourses',
            'activeQuizCourse',
            'activeCourseId',
            'exams',
            'summary',
        ));
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
