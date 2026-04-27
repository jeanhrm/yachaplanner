<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password',
        'ugel_id', 'institution_id', 'role',
        'speciality', 'areas', 'grades', 'avatar_path',
        'plan', 'plan_expires_at',
        'weekly_credits_used', 'weekly_credits_limit',
        'credits_reset_at', 'preferences', 'locale',
        'ugel', 'region', 'nivel', 'area_docente', 'institucion',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'  => 'datetime',
        'password'           => 'hashed',
        'areas'              => 'array',
        'grades'             => 'array',
        'preferences'        => 'array',
        'plan_expires_at'    => 'datetime',
        'credits_reset_at'   => 'datetime',
    ];

    public function ugel(): BelongsTo
    {
        return $this->belongsTo(Ugel::class);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function institutions(): BelongsToMany
    {
        return $this->belongsToMany(Institution::class, 'institution_users')
                    ->withPivot('role', 'joined_at');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function chatSessions(): HasMany
    {
        return $this->hasMany(ChatSession::class);
    }

    public function exports(): HasMany
    {
        return $this->hasMany(Export::class);
    }

    public function creditTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class);
    }

    public function libraryItems(): HasMany
    {
        return $this->hasMany(LibraryItem::class);
    }

    public function libraryBookmarks(): HasMany
    {
        return $this->hasMany(LibraryBookmark::class);
    }

    public function isPro(): bool
    {
        return $this->plan === 'pro' &&
               ($this->plan_expires_at === null || $this->plan_expires_at->isFuture());
    }

    public function hasCredits(): bool
    {
        return $this->remainingCredits() > 0;
    }

    public function remainingCredits(): int
    {
        $limit = match($this->plan ?? 'free') {
        'pro', 'institution' => 999,
        default => 5,
        };
        return max(0, $limit - $this->weekly_credits_used);
    }
}