<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseQuizContext extends Model
{
    protected $fillable = [
        'course_id',
        'section_id',
        'lesson_id',
        'quiz_lesson_id',
        'source_key',
        'kind',
    ];

    protected $casts = [
        'course_id' => 'integer',
        'section_id' => 'integer',
        'lesson_id' => 'integer',
        'quiz_lesson_id' => 'integer',
    ];
}
