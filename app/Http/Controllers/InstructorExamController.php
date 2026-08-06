<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Course;
use App\Models\Submission;
use App\Models\FileUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstructorExamController extends Controller
{

    public function exams(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search', '');

        $query = Exam::with(['creator', 'course', 'submissions'])
            ->whereHas('course', function ($q) {
                $q->whereJsonContains('instructor_ids', Auth::id());
            });

        if ($search != '') {
            $query->where('title', 'LIKE', "%$search%");
        }

        if ($status != 'all') {
            $query->whereHas('submissions', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        $exams = $query->orderBy('id', 'desc')->paginate(20)->appends($request->query());

        // Scoped to this instructor's courses only
        $instructorExamIds = Exam::whereHas('course', function ($q) {
            $q->whereJsonContains('instructor_ids', Auth::id());
        })->pluck('id');

        $page_data = [
            'status'          => $status,
            'exams'           => $exams,
            'pending_exams'   => Exam::whereIn('id', $instructorExamIds)
                ->whereHas('submissions', fn($q) => $q->where('status', 'pending'))->count(),
            'checking_exams'  => Exam::whereIn('id', $instructorExamIds)
                ->whereHas('submissions', fn($q) => $q->where('status', 'checking'))->count(),
            'checked_exams'   => Exam::whereIn('id', $instructorExamIds)
                ->whereHas('submissions', fn($q) => $q->where('status', 'checked'))->count(),
            'published_exams' => Exam::whereIn('id', $instructorExamIds)
                ->whereHas('submissions', fn($q) => $q->where('status', 'published'))->count(),
        ];

        return view('instructor.exam.index', $page_data);
    }

    // Show create form
    public function create()
    {
        $courses = Course::all();
        return view('instructor.exam.create', compact('courses'));
    }

    // Store new exam
    public function store(Request $request)
    {
        $request->validate([
            'title'              => 'required|string|max:255',
            'description'        => 'nullable|string',
            'marks'              => 'required|integer|min:0',
            'duration'           => 'required|integer|min:1',
            'exam_mode'          => 'required|in:online,offline',
            'course_id'          => 'required|exists:courses,id',
            'question_paper_pdf' => 'nullable|mimes:pdf|max:10240',
            'start_at'           => 'nullable|date',
            'end_at'             => 'nullable|date|after_or_equal:start_at',
        ]);

        $exam              = new Exam();
        $exam->title       = $request->title;
        $exam->description = $request->description;
        $exam->marks       = $request->marks;
        $exam->duration    = $request->duration;
        $exam->exam_mode   = $request->exam_mode;
        $exam->course_id   = $request->course_id;
        $exam->start_at    = $request->start_at;
        $exam->end_at      = $request->end_at;
        $exam->created_by  = Auth::id();

        if ($request->hasFile('question_paper_pdf')) {
            $path = "uploads/exams/" . nice_file_name($request->title, $request->question_paper_pdf->extension());
            FileUploader::upload($request->question_paper_pdf, $path);
            $exam->question_paper_pdf = $path;
        }

        $exam->save();

        return redirect()->route('instructor.exams')->with('success', 'Exam created successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $exam    = Exam::findOrFail($id);
        $courses = Course::all();
        return view('instructor.exam.edit', compact('exam', 'courses'));
    }

    // Update exam
    public function update(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);

        $request->validate([
            'title'              => 'required|string|max:255',
            'description'        => 'nullable|string',
            'marks'              => 'required|integer|min:0',
            'duration'           => 'required|integer|min:1',
            'exam_mode'          => 'required|in:online,offline',
            'course_id'          => 'required|exists:courses,id',
            'question_paper_pdf' => 'nullable|mimes:pdf|max:10240',
            'start_at'           => 'nullable|date',
            'end_at'             => 'nullable|date|after_or_equal:start_at',
        ]);

        $exam->title       = $request->title;
        $exam->description = $request->description;
        $exam->marks       = $request->marks;
        $exam->duration    = $request->duration;
        $exam->exam_mode   = $request->exam_mode;
        $exam->course_id   = $request->course_id;
        $exam->start_at    = $request->start_at;
        $exam->end_at      = $request->end_at;

        if ($request->hasFile('question_paper_pdf')) {
            if ($exam->question_paper_pdf) {
                remove_file($exam->question_paper_pdf);
            }
            $path = "uploads/exams/" . nice_file_name($request->title, $request->question_paper_pdf->extension());
            FileUploader::upload($request->question_paper_pdf, $path);
            $exam->question_paper_pdf = $path;
        }

        $exam->save();

        return redirect()->route('instructor.exams')->with('success', 'Exam updated successfully.');
    }

    // Delete exam
    public function delete($id)
    {
        $exam = Exam::findOrFail($id);

        if ($exam->question_paper_pdf) {
            remove_file($exam->question_paper_pdf);
        }

        $exam->delete();

        return redirect()->route('instructor.exams')->with('success', 'Exam deleted successfully.');
    }

    public function submissions(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);

        $submissions = Submission::with('student')
            ->where('exam_id', $id);

        /* =======================
           STATUS FILTER
        ======================== */
        if ($request->filled('status') && $request->status !== 'all') {

            if ($request->status === 'pending') {
                $submissions->whereNull('submitted_pdf');
            } elseif ($request->status === 'checking') {
                $submissions->whereNotNull('submitted_pdf')
                    ->whereNull('obtained_marks');
            } elseif ($request->status === 'Evaluated') {
                $submissions->whereNotNull('obtained_marks');
            }
        }

        /* =======================
           SEARCH (Name / Email)
        ======================== */
        if ($request->filled('search')) {
            $search = $request->search;

            $submissions->whereHas('student', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $submissions = $submissions
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('instructor.exam.submission', compact('exam', 'submissions'));
    }

    public function submissionDetails($id)
    {
        $submission = Submission::with(['student', 'exam'])->findOrFail($id);
        $exam       = $submission->exam;

        return view('instructor.exam.submission_details', compact('submission', 'exam'));
    }

    // Update marks and remarks for a submission
    public function updateSubmission(Request $request, $id)
    {
        $request->validate([
            'obtained_marks' => 'required|numeric|min:0',
            'remarks'        => 'nullable|string',
        ]);

        $submission = Submission::findOrFail($id);

        $submission->update([
            'obtained_marks' => $request->obtained_marks,
            'remarks'        => $request->remarks,
            'status'         => 'checked',
            'checked_at'     => now(),
        ]);

        return redirect()->back()->with('success', 'Submission updated successfully.');
    }

    public function uploadAnnotatedPdf(Request $request, $id)
    {
        $submission = Submission::findOrFail($id);

        $request->validate([
            'annotated_pdf'   => 'required|file|mimes:pdf|max:51200', 
            'annotation_data' => 'required|string',
        ]);

        if ($request->hasFile('annotated_pdf')) {
            if ($submission->annotated_pdf) {
                remove_file($submission->annotated_pdf);
            }

            $path = "uploads/annotated_pdfs/" . nice_file_name('annotated_' . $id, $request->annotated_pdf->extension());
            FileUploader::upload($request->annotated_pdf, $path);
            $submission->annotated_pdf = $path;
        }

        $submission->annotation_data = $request->annotation_data;
        $submission->status          = 'checked';
        $submission->checked_at      = now();
        $submission->save();

        return response()->json([
            'success' => true,
            'message' => 'Annotated PDF saved successfully.'
        ]);
    }
}
