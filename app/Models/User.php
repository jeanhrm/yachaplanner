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
        'steam_stage', 'projects_completed', 'badges', 'current_journey',
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
        'badges'          => 'array',
        'current_journey' => 'array',
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
        return match($this->plan ?? 'free') {
            'pro', 'institution' => 999,
            default => max(0, 5 - $this->weekly_credits_used),
        };
    }
    // Insignia actual según proyectos completados
    public function getCurrentBadge(): array
    {
        $completed = $this->projects_completed ?? 0;

        if ($completed >= 10) return ['emoji' => '🦙', 'name' => 'Yachaq STEAM',       'color' => '#7c3aed'];
        if ($completed >= 5)  return ['emoji' => '⭐', 'name' => 'Maestro STEAM',       'color' => '#d97706'];
        if ($completed >= 3)  return ['emoji' => '🔬', 'name' => 'Investigador STEAM',  'color' => '#0284c7'];
        if ($completed >= 1)  return ['emoji' => '🌱', 'name' => 'Explorador STEAM',    'color' => '#059669'];
        return                       ['emoji' => '🌀', 'name' => 'Aprendiz STEAM',      'color' => '#9ca3af'];
    }

    // Próxima insignia
    public function getNextBadge(): array
    {
        $completed = $this->projects_completed ?? 0;

        if ($completed >= 10) return ['name' => '¡Nivel máximo!', 'need' => 0];
        if ($completed >= 5)  return ['name' => 'Yachaq STEAM 🦙',      'need' => 10 - $completed];
        if ($completed >= 3)  return ['name' => 'Maestro STEAM ⭐',     'need' => 5  - $completed];
        if ($completed >= 1)  return ['name' => 'Investigador STEAM 🔬', 'need' => 3  - $completed];
        return                       ['name' => 'Explorador STEAM 🌱',   'need' => 1  - $completed];
    }

    // Etapa actual del proyecto
    public function getCurrentStage(): int
    {
        return $this->steam_stage ?? 0;
    }

    // Avanzar etapa
    public function advanceStage(array $stageData = []): void
    {
        $journey = $this->current_journey ?? [];
        $stage   = $this->steam_stage ?? 0;

        $journey['stage_' . $stage] = array_merge([
            'completed_at' => now()->toISOString(),
        ], $stageData);

        $newStage = $stage + 1;

        // Si completó las 5 etapas — proyecto terminado
        if ($newStage >= 5) {
            $previousBadge = $this->getCurrentBadge()['name'];
            $this->projects_completed = ($this->projects_completed ?? 0) + 1;
            $newBadge = $this->getCurrentBadge();

            // Solo se registra la insignia cuando se alcanza un nuevo nivel
            $badges = $this->badges ?? [];
            if ($newBadge['name'] !== $previousBadge) {
                $badges[] = ['badge' => $newBadge, 'date' => now()->toISOString()];
            }

            $this->update([
                'steam_stage'        => 0,
                'projects_completed' => $this->projects_completed,
                'current_journey'    => [],
                'badges'             => $badges,
            ]);
        } else {
            $this->update([
                'steam_stage'     => $newStage,
                'current_journey' => $journey,
            ]);
        }
    }
}