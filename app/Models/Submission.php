<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'exam_id',
        'student_id',
        'submitted_pdf',
        'annotation_data',
        'annotated_pdf',
        'obtained_marks',
        'remarks',
        'status',
        'submitted_at',
        'checked_at'
    ];

    public function student()
    {
        return $this->belongsTo(\App\Models\User::class, 'student_id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }
}
