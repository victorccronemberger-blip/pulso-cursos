<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseMaterial extends Model
{
    protected $fillable = [
        'course_id',
        'section_id',
        'lesson_id',
        'source_key',
        'title',
        'file_name',
        'mime_type',
        'size_bytes',
        'contents',
    ];

    protected $hidden = ['contents'];

    protected $casts = [
        'course_id' => 'integer',
        'section_id' => 'integer',
        'lesson_id' => 'integer',
        'size_bytes' => 'integer',
    ];
}
