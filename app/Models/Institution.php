<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institution extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ugel_id', 'owner_id', 'name', 'code_modular',
        'district', 'province', 'region', 'level',
        'is_multigrado', 'is_bilingual', 'predominant_language',
        'context_tags', 'local_context', 'logo_path',
        'plan', 'plan_expires_at', 'teacher_seats', 'is_active',
    ];

    protected $casts = [
        'context_tags'     => 'array',
        'is_multigrado'    => 'boolean',
        'is_bilingual'     => 'boolean',
        'is_active'        => 'boolean',
        'plan_expires_at'  => 'datetime',
    ];

    public function ugel(): BelongsTo
    {
        return $this->belongsTo(Ugel::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'institution_users')
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function libraryItems(): HasMany
    {
        return $this->hasMany(LibraryItem::class);
    }
}