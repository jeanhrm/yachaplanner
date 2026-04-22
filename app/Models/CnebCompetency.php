<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CnebCompetency extends Model
{
    protected $fillable = [
        'area', 'level', 'competency_code',
        'competency', 'capacities', 'grade_range',
        'performance_descriptors', 'approach', 'is_active',
    ];

    protected $casts = [
        'capacities'              => 'array',
        'grade_range'             => 'array',
        'performance_descriptors' => 'array',
        'is_active'               => 'boolean',
    ];

    public function scopeForArea($query, string $area)
    {
        return $query->where('area', $area);
    }

    public function scopeForLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    public function scopeSteam($query)
    {
        return $query->whereIn('approach', ['steam', 'both']);
    }
}