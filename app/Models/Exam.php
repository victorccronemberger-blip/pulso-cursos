<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'marks',
        'duration',
        'exam_mode',
        'question_paper_pdf',
        'start_at',
        'end_at',
        'created_by'
    ];

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'exam_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function mySubmission()
    {
        return $this->hasOne(Submission::class, 'exam_id')
            ->where('student_id', auth()->id());
    }
}
