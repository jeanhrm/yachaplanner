<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContextTag extends Model
{
    protected $fillable = [
        'name', 'type', 'district',
        'province', 'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForDistrict($query, string $district)
    {
        return $query->where('district', $district)
                     ->orWhereNull('district');
    }
}