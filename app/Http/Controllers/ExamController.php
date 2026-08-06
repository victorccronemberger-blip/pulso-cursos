<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Course;
use App\Models\Submission;
use App\Models\FileUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class examController extends Controller
{
    // List exams with filters
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search', '');

        $query = Exam::with(['creator', 'course', 'submissions']);

        if ($search != '') {
            $query->where('title', 'LIKE', "%$search%");
        }

        if ($status != 'all') {
            switch ($status) {
                case 'pending':
                    // Exams with no submissions at all
                    $query->whereDoesntHave('submissions');
                    break;

                case 'checking':
                    // Exams with at least one ungraded submission
                    $query->whereHas(
                        'submissions',
                        fn($q) =>
                        $q->whereNotNull('submitted_pdf')->whereNull('obtained_marks')
                    );
                    break;

                case 'checked':
                    // Exams where every submission is graded but none published yet
                    $query->whereHas(
                        'submissions',
                        fn($q) =>
                        $q->whereNotNull('obtained_marks')
                    )->whereDoesntHave(
                        'submissions',
                        fn($q) =>
                        $q->whereNull('obtained_marks')
                    );
                    break;

                case 'published':
                    // Exams where at least one submission is published
                    $query->whereHas(
                        'submissions',
                        fn($q) =>
                        $q->where('status', 'published')
                    );
                    break;
            }
        }

        $exams = $query->orderBy('id', 'desc')->paginate(20)->appends($request->query());

        $page_data = [
            'status'         => $status,
            'exams'          => $exams,

            // Exams with no submissions yet — waiting for students
            'pending_exams'  => Exam::whereDoesntHave('submissions')->count(),

            // Exams with at least one submission that hasn't been graded yet
            'checking_exams' => Exam::whereHas(
                'submissions',
                fn($q) =>
                $q->whereNotNull('submitted_pdf')->whereNull('obtained_marks')
            )->count(),

            // Exams where ALL submissions are graded but results not published
            'checked_exams'  => Exam::whereHas(
                'submissions',
                fn($q) =>
                $q->whereNotNull('obtained_marks')
            )->whereDoesntHave(
                'submissions',
                fn($q) =>
                $q->whereNull('obtained_marks')
            )->count(),

            // Exams where at least one submission has been published to the student
            'published_exams' => Exam::whereHas(
                'submissions',
                fn($q) =>
                $q->where('status', 'published')
            )->count(),
        ];

        return view('admin.exam.index', $page_data);
    }

    // Show create form
    public function create()
    {
        $courses = Course::all();
        return view('admin.exam.create', compact('courses'));
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

        $exam = new Exam();
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

        return redirect()->route('admin.exams')->with('success', 'Exam created successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $exam    = Exam::findOrFail($id);
        $courses = Course::all();
        return view('admin.exam.edit', compact('exam', 'courses'));
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

        return redirect()->route('admin.exams')->with('success', 'Exam updated successfully.');
    }

    // Delete exam
    public function delete($id)
    {
        $exam = Exam::findOrFail($id);

        if ($exam->question_paper_pdf) {
            remove_file($exam->question_paper_pdf);
        }

        $exam->delete();

        return redirect()->route('admin.exams')->with('success', 'Exam deleted successfully.');
    }

    // Publish all checked submissions for an exam in one click
    public function publishExam($id)
    {
        $exam = Exam::findOrFail($id);

        // Block publish if any submitted script is still ungraded
        $ungradedCount = $exam->submissions()
            ->whereNotNull('submitted_pdf')
            ->whereNull('obtained_marks')
            ->count();

        if ($ungradedCount > 0) {
            return redirect()->back()->with(
                'error',
                $ungradedCount . ' submission(s) are still ungraded. Please grade all submissions before publishing.'
            );
        }

        // Block publish if no checked submissions exist at all
        $checkedCount = $exam->submissions()
            ->where('status', 'checked')
            ->count();

        if ($checkedCount === 0) {
            return redirect()->back()->with(
                'error',
                'No graded submissions found. Nothing to publish.'
            );
        }

        // Publish all checked submissions for this exam at once
        $exam->submissions()
            ->where('status', 'checked')
            ->update([
                'status'       => 'published',
                'published_at' => now(),
            ]);

        return redirect()->back()->with(
            'success',
            $checkedCount . ' result(s) published successfully. Students can now see their marks.'
        );
    }

    // List submissions for a specific exam
    public function submissions(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);

        $submissions = Submission::with('student')->where('exam_id', $id);

        if ($request->filled('status') && $request->status !== 'all') {
            switch ($request->status) {
                case 'checking':
                    // Script uploaded but not graded yet
                    $submissions->whereNotNull('submitted_pdf')->whereNull('obtained_marks');
                    break;

                case 'checked':
                    // Graded but not published
                    $submissions->whereNotNull('obtained_marks')->where('status', 'checked');
                    break;

                case 'published':
                    // Results visible to student
                    $submissions->where('status', 'published');
                    break;
            }
        }

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

        return view('admin.exam.submission', compact('exam', 'submissions'));
    }

    // Show a single submission in detail
    public function submissionDetails($id)
    {
        $submission = Submission::with(['student', 'exam'])->findOrFail($id);
        $exam       = $submission->exam;
        return view('admin.exam.submission_details', compact('submission', 'exam'));
    }

    // Grade a submission — marks + remarks → status becomes 'checked'
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

        return redirect()->back()->with('success', 'Submission graded successfully.');
    }

    // Upload annotated PDF with annotation data
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
