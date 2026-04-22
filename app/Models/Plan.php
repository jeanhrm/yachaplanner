<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name', 'slug', 'target', 'price_soles',
        'billing_cycle', 'weekly_ai_credits', 'teacher_seats',
        'features', 'export_watermark', 'library_publish',
        'is_active', 'sort_order',
    ];

    protected $casts = [
        'features'        => 'array',
        'export_watermark'=> 'boolean',
        'library_publish' => 'boolean',
        'is_active'       => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function getPriceFormattedAttribute(): string
    {
        if ($this->price_soles === 0) return 'Gratis';
        return 'S/ ' . number_format($this->price_soles / 100, 2);
    }
}