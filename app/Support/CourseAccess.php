<?php

namespace App\Support;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;

class CourseAccess
{
    public function allows(User $user, Course|int $course): bool
    {
        $course = $course instanceof Course ? $course : Course::find($course);
        if (! $course) {
            return false;
        }

        if ($user->role === 'admin' || is_course_instructor($course->id, $user->id)) {
            return true;
        }

        if (! $course->is_paid) {
            return true;
        }

        $enrollment = Enrollment::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->first();

        return $enrollment !== null
            && (! $enrollment->expiry_date || $enrollment->expiry_date >= time());
    }
}
