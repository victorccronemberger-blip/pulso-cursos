<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'name',
        'designation',
        'photo',
        'linkedin_url',
        'facebook_url',
        'twitter_url',
        'bio',
        'sort_order',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
