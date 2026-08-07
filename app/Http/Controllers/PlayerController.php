<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Forum;
use App\Models\Lesson;
use App\Models\CourseMaterial;
use App\Models\CourseQuizContext;
use App\Models\Section;
use App\Models\Watch_history;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PlayerController extends Controller
{
    public function course_player(Request $request, $slug, $id = '')
    {
        $course = Course::where('slug', $slug)->firstOrFail();

        // check if course is paid
        if ($course->is_paid && auth()->user()->role != 'admin') {

            if (auth()->user()->role == 'student') {
                // get latest enrollment for this course and student
                $latest_enrollment = Enrollment::where('course_id', $course->id)
                    ->where('user_id', auth()->user()->id)
                    ->latest('created_at')
                    ->first();

                if (!$latest_enrollment) {
                    Session::flash('error', get_phrase('Not registered for this course.'));
                    return redirect()->route('course.details', ['slug' => $slug]);
                }

                // check if latest enrollment is expired (expiry_date is unix timestamp)
                if ($latest_enrollment->expiry_date && $latest_enrollment->expiry_date < time()) {
                    Session::flash('error', get_phrase('Your course accessibility has expired. You need to buy it again'));
                    return redirect()->route('course.details', ['slug' => $slug]);
                }
            }



            if (auth()->user()->role == 'instructor') { // for instructor check who is course instructor
                $instructor_ids = json_decode($course->instructor_ids ?? '[]', true);
                $enroll_status = enroll_status($course->id, auth()->user()->id);

                if (
                    $course->user_id != auth()->id() &&
                    !in_array(auth()->id(), $instructor_ids) &&
                    !$enroll_status
                ) {
                    Session::flash('error', get_phrase('Not valid instructor.'));
                    return redirect()->route('my.courses');
                }
            }
        }

        $check_lesson_history = Watch_history::where('course_id', $course->id)
            ->where('student_id', auth()->user()->id)->first();
        $first_lesson_of_course = Lesson::where('course_id', $course->id)->orderBy('sort', 'asc')->value('id');
        if ($id == '') {
            $id = $check_lesson_history->watching_lesson_id ?? $first_lesson_of_course;
        }

        // A lesson URL must always resolve inside the course the student can access.
        // Without this scope a manually edited URL could expose another course's lesson.
        $lesson_details = Lesson::where('course_id', $course->id)->where('id', $id)->firstOrFail();

        // if user has any watched history or not
        if (! $check_lesson_history && $id > 0) {
            $data = [
                'course_id'          => $course->id,
                'student_id'         => auth()->user()->id,
                'watching_lesson_id' => $id,
                'completed_lesson'   => json_encode([])
            ];
            $data['updated_at'] = now();
            $data['created_at'] = now();
            Watch_history::insert($data);
        }

        // when user plays a lesson, update that lesson id as watch history
        if ($id > 0) {
            Watch_history::where('course_id', $course->id)
                ->where('student_id', auth()->user()->id)
                ->update(['watching_lesson_id' => $id]);
        }

        $page_data['course_details'] = $course;
        $page_data['lesson_details'] = $lesson_details;
        $page_data['history']        = Watch_history::where('course_id', $course->id)->where('student_id', auth()->user()->id)->first();

        $contextLessonId = $lesson_details->lesson_type === 'quiz'
            ? CourseQuizContext::where('quiz_lesson_id', $lesson_details->id)->value('lesson_id')
            : $lesson_details->id;
        $activeSectionId = (int) $lesson_details->section_id;
        $page_data['current_section'] = Section::find($activeSectionId);

        $page_data['lesson_materials'] = CourseMaterial::query()
            ->select(['id', 'course_id', 'section_id', 'lesson_id', 'title', 'file_name', 'mime_type', 'size_bytes'])
            ->where('course_id', $course->id)
            ->where('lesson_id', $contextLessonId)
            ->orderBy('title')
            ->get();
        $page_data['section_materials'] = CourseMaterial::query()
            ->select(['id', 'course_id', 'section_id', 'lesson_id', 'title', 'file_name', 'mime_type', 'size_bytes'])
            ->where('course_id', $course->id)
            ->where('section_id', $activeSectionId)
            ->orderBy('title')
            ->get();
        $page_data['material_lesson_ids'] = $page_data['section_materials']->pluck('lesson_id')->filter()->map(fn ($id) => (int) $id)->all();
        $page_data['lesson_quizzes'] = Lesson::query()
            ->join('course_quiz_contexts', 'course_quiz_contexts.quiz_lesson_id', '=', 'lessons.id')
            ->select('lessons.*', 'course_quiz_contexts.kind as context_kind')
            ->where('course_quiz_contexts.course_id', $course->id)
            ->where('course_quiz_contexts.lesson_id', $contextLessonId)
            ->orderBy('lessons.sort')
            ->get();
        $page_data['module_simulations'] = Lesson::query()
            ->join('course_quiz_contexts', 'course_quiz_contexts.quiz_lesson_id', '=', 'lessons.id')
            ->select('lessons.*', 'course_quiz_contexts.kind as context_kind')
            ->where('course_quiz_contexts.course_id', $course->id)
            ->where('course_quiz_contexts.section_id', $activeSectionId)
            ->whereIn('course_quiz_contexts.kind', ['module', 'final'])
            ->orderBy('lessons.sort')
            ->get();

        $forum_query = Forum::join('users', 'forums.user_id', 'users.id')
            ->select('forums.*', 'users.name as user_name', 'users.photo as user_photo')
            ->latest('forums.id')
            ->where('forums.parent_id', 0)
            ->where('forums.course_id', $course->id);

        if (isset($_GET['search'])) {
            $forum_query->where(function ($query) use ($request) {
                $query->where('forums.title', 'like', '%' . $request->search . '%')->orWhere('forums.description', 'like', '%' . $request->search . '%');
            });
        }

        $page_data['questions'] = $forum_query->get();

        return view('course_player.index', $page_data);
    }

    public function set_watch_history(Request $request)
    {
        $course = Course::findOrFail($request->course_id);
        $lesson = Lesson::where('course_id', $course->id)->where('id', $request->lesson_id)->firstOrFail();
        $enrollment = Enrollment::where('course_id', $course->id)->where('user_id', auth()->user()->id)->first();
        $is_course_instructor = is_course_instructor($course->id, auth()->user()->id);
        if ($course->is_paid && !$enrollment && !$is_course_instructor && auth()->user()->role != 'admin') {
            Session::flash('error', get_phrase('Not registered for this course.'));
            return redirect()->back();
        }

        $data['course_id']  = $request->course_id;
        $data['student_id'] = auth()->user()->id;

        $total_lesson = Lesson::where('course_id', $request->course_id)->pluck('id')->toArray();

        $watch_history = Watch_history::where('course_id', $request->course_id)
            ->where('student_id', auth()->user()->id)->first();

        if (isset($watch_history) && $watch_history->id) {
            $lessons = (array) json_decode($watch_history->completed_lesson);
            if (! in_array($request->lesson_id, $lessons)) {
                array_push($lessons, $request->lesson_id);
            } else {
                while (($key = array_search($request->lesson_id, $lessons)) !== false) {
                    unset($lessons[$key]);
                }
            }

            $data['completed_lesson']   = json_encode($lessons);
            $data['watching_lesson_id'] = $lesson->id;
            $data['completed_date']     = (count($total_lesson) == count($lessons)) ? time() : null;
            Watch_history::where('course_id', $request->course_id)->where('student_id', auth()->user()->id)->update($data);
        } else {
            $lessons                    = [$lesson->id];
            $data['completed_lesson']   = json_encode($lessons);
            $data['watching_lesson_id'] = $lesson->id;
            $data['completed_date']     = (count($total_lesson) == count($lessons)) ? time() : null;
            $data['updated_at'] = now();
            $data['created_at'] = now();
            Watch_history::insert($data);
        }

        if (progress_bar($request->course_id) >= 100) {
            $certificate = Certificate::where('user_id', auth()->user()->id)->where('course_id', $request->course_id);

            if ($certificate->count() == 0) {
                $certificate_data['user_id']    = auth()->user()->id;
                $certificate_data['course_id']  = $request->course_id;
                $certificate_data['identifier'] = random(12);
                $certificate_data['created_at'] = date('Y-m-d H:i:s');
                $certificate_data['updated_at'] = now();
                $certificate_data['created_at'] = now();
                Certificate::insert($certificate_data);
            }
        }

        return redirect()->back();
    }

    public function prepend_watermark()
    {
        return view('course_player.watermark');
    }
}
