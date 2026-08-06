<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoticeBoard extends Model
{
    protected $table = 'noticeboards';
   protected $fillable = [
       'course_id',
        'title',
        'description',
        
    ];
}
