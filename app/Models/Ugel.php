<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ugel extends Model
{
    protected $fillable = [
        'name', 'code', 'region', 'province',
        'districts', 'contact_email', 'contact_phone', 'is_active',
    ];

    protected $casts = [
        'districts' => 'array',
        'is_active' => 'boolean',
    ];

    public function institutions(): HasMany
    {
        return $this->hasMany(Institution::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function libraryItems(): HasMany
    {
        return $this->hasMany(LibraryItem::class);
    }
}