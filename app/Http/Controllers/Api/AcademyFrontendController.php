<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\Watch_history;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

/** Endpoints used by the static Academy client. */
class AcademyFrontendController extends Controller
{
    public function courses(Request $request): JsonResponse
    {
        $query = Course::query()->where('status', 'active')->latest('id');
        if ($search = trim((string) $request->query('search'))) {
            $query->where(fn ($q) => $q->where('title', 'like', "%{$search}%")
                ->orWhere('short_description', 'like', "%{$search}%"));
        }
        return response()->json(['data' => course_data($query->get())]);
    }

    public function course(string $slug): JsonResponse
    {
        $course = Course::where('slug', $slug)->where('status', 'active')->firstOrFail();
        return response()->json(['data' => course_details_by_id(0, $course->id)[0] ?? null]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json(['data' => $request->user()->only(['id', 'name', 'email', 'photo'])]);
    }

    public function startCheckout(Request $request, Course $course): JsonResponse
    {
        abort_unless($course->status === 'active', 404);
        return response()->json(['checkout_url' => URL::temporarySignedRoute(
            'api.frontend.checkout', now()->addMinutes(5),
            ['user' => $request->user()->id, 'course' => $course->id]
        )]);
    }

    public function cart(Request $request): JsonResponse
    {
        $courses = Course::whereIn('id', CartItem::where('user_id', $request->user()->id)->pluck('course_id'))->get();
        $data = course_data($courses);
        $total = collect($data)->sum(fn ($course) => (float) ($course->price_cart ?? 0));

        return response()->json(['data' => $data, 'total' => $total]);
    }

    public function addToCart(Request $request, Course $course): JsonResponse
    {
        abort_unless($course->status === 'active' && $course->is_paid, 422, 'Este curso não pode ser adicionado ao carrinho.');
        abort_if($this->hasActiveEnrollment($request->user()->id, $course->id), 422, 'Você já possui acesso a este curso.');

        CartItem::firstOrCreate(['user_id' => $request->user()->id, 'course_id' => $course->id]);
        return response()->json(['message' => 'Curso adicionado ao carrinho.'], 201);
    }

    public function enrollFree(Request $request, Course $course): JsonResponse
    {
        abort_unless($course->status === 'active' && ! $course->is_paid, 422, 'Este curso não é gratuito.');
        Enrollment::firstOrCreate(
            ['user_id' => $request->user()->id, 'course_id' => $course->id],
            ['enrollment_type' => 'free', 'entry_date' => time(), 'expiry_date' => null]
        );
        return response()->json(['player_url' => '#/learn/' . rawurlencode($course->slug)]);
    }

    public function removeFromCart(Request $request, Course $course): JsonResponse
    {
        CartItem::where('user_id', $request->user()->id)->where('course_id', $course->id)->delete();
        return response()->json(status: 204);
    }

    public function myCourses(Request $request): JsonResponse
    {
        $enrollments = Enrollment::query()
            ->where('user_id', $request->user()->id)
            ->whereRaw('id IN (SELECT MAX(id) FROM enrollments WHERE user_id = ? GROUP BY course_id)', [$request->user()->id])
            ->get();
        $courses = Course::whereIn('id', $enrollments->pluck('course_id'))->get()->keyBy('id');
        $data = course_data($courses->values())->map(function ($course) use ($request) {
            $course->progress = round(course_progress($course->id, $request->user()->id));
            $course->player_url = '#/learn/' . rawurlencode($course->slug);
            return $course;
        })->values();

        return response()->json(['data' => $data]);
    }

    public function player(Request $request, Course $course): JsonResponse
    {
        abort_unless($this->hasActiveEnrollment($request->user()->id, $course->id), 403, 'Você não possui acesso a este curso.');
        $history = Watch_history::firstOrCreate(
            ['course_id' => $course->id, 'student_id' => $request->user()->id],
            ['watching_lesson_id' => Lesson::where('course_id', $course->id)->orderBy('sort')->value('id'), 'completed_lesson' => json_encode([])]
        );
        $sections = Section::where('course_id', $course->id)->orderBy('sort')->get()->map(function ($section) {
            $section->lessons = Lesson::where('section_id', $section->id)->orderBy('sort')->get()->map(function ($lesson) {
                $lesson->lesson_src = str_starts_with((string) $lesson->lesson_src, 'http') ? $lesson->lesson_src : url('public/' . ltrim((string) $lesson->lesson_src, '/'));
                return $lesson;
            });
            return $section;
        });

        return response()->json(['data' => ['course' => course_data([$course])[0], 'sections' => $sections, 'history' => $history]]);
    }

    public function progress(Request $request, Lesson $lesson): JsonResponse
    {
        abort_unless($this->hasActiveEnrollment($request->user()->id, $lesson->course_id), 403);
        update_watch_history_manually($lesson->id, $lesson->course_id, $request->user()->id);
        return response()->json(['progress' => round(course_progress($lesson->course_id, $request->user()->id))]);
    }

    /** Establishes a backend session only after validating a short-lived signature. */
    public function checkout(Request $request, int $user, Course $course)
    {
        abort_unless($request->hasValidSignature() && $course->status === 'active', 403);
        auth()->loginUsingId($user);
        if ($course->is_paid) {
            CartItem::firstOrCreate(['user_id' => $user, 'course_id' => $course->id]);
            return redirect()->route('cart');
        }
        return redirect()->route('purchase.course', ['course_id' => $course->id]);
    }

    private function hasActiveEnrollment(int $userId, int $courseId): bool
    {
        return Enrollment::where('user_id', $userId)->where('course_id', $courseId)
            ->where(fn ($query) => $query->whereNull('expiry_date')->orWhere('expiry_date', '>', time()))->exists();
    }
}
