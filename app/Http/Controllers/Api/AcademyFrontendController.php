<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Course;
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
}
