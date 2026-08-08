<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\FileUploader;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class MyExamController extends Controller
{
    // Exam list
    public function index(Request $request)
    {
        $user_id = auth()->id(); // logged-in student

        // Step 1: Get all course IDs the student is enrolled in
        $course_ids = \DB::table('enrollments')
            ->where('user_id', $user_id)
            ->pluck('course_id')
            ->toArray();

        // Step 2: Fetch exams under those courses
        $exams = \App\Models\Exam::with(['course', 'creator', 'mySubmission'])
            ->whereIn('course_id', $course_ids)
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy('start_at', 'desc')
            ->paginate(10);

        return view('frontend.default.student.my_exam.index', compact('exams'));
    }



    // Exam details page
    public function details(Exam $exam)
    {
        $submission = Submission::where('exam_id', $exam->id)
            ->where('student_id', Auth::id())
            ->first();

        return view(
            'frontend.default.student.my_exam.details',
            compact('exam', 'submission')
        );
    }

    // Submit exam answers
    public function submit(Request $request, $exam_id)
    {
        $exam = Exam::findOrFail($exam_id);

        $now = Carbon::now();

        if ($exam->start_at && $now->lt(Carbon::parse($exam->start_at))) {
            return back()->with('error', 'Esta prova ainda não começou.');
        }

        if ($exam->end_at && $now->gt(Carbon::parse($exam->end_at))) {
            return back()->with('error', 'O prazo para entregar esta prova terminou.');
        }

        $alreadySubmitted = Submission::where('exam_id', $exam->id)
            ->where('student_id', Auth::id())
            ->exists();

        if ($alreadySubmitted) {
            return back()->with('error', 'Você já entregou esta prova.');
        }

        $request->validate([
            'answer_script' => 'required|mimes:pdf,doc,docx|max:10240',
        ]);


        $path = 'uploads/exam_submissions/' . nice_file_name('answer_' . Auth::id(), $request->file('answer_script')->extension());
        FileUploader::upload($request->file('answer_script'), $path);
        $filePath = $path;

        Submission::create([
            'exam_id'      => $exam->id,
            'student_id'   => Auth::id(),
            'submitted_pdf' => $filePath,
            'status'        => 'checking',
            'submitted_at' => now(),
        ]);

        return redirect()
            ->route('my.exam.details', $exam->id)
            ->with('success', 'Prova entregue com sucesso.');
    }
}
