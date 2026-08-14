<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectIdea extends Model
{
    protected $fillable = [
        'title', 'description', 'problem_type', 'level',
        'areas', 'methodology', 'steam_connection',
        'ancestral_knowledge', 'prototype_ideas',
        'difficulty', 'duration', 'is_active',
        'is_user_submitted', 'submitted_by',
    ];

    protected $casts = [
        'areas' => 'array',
        'is_active' => 'boolean',
        'is_user_submitted' => 'boolean',
    ];
}