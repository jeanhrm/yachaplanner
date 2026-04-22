<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'institution_id', 'module', 'title',
        'area', 'grade', 'level', 'bimester', 'year',
        'school_year', 'situation', 'competencies',
        'methodology_tags', 'context_tags', 'status',
        'current_version', 'last_exported_at',
    ];

    protected $casts = [
        'competencies'     => 'array',
        'methodology_tags' => 'array',
        'context_tags'     => 'array',
        'last_exported_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(DocumentVersion::class);
    }

    public function currentVersion(): HasOne
    {
        return $this->hasOne(DocumentVersion::class)
                    ->where('version', $this->current_version);
    }

    public function chatSessions(): HasMany
    {
        return $this->hasMany(ChatSession::class);
    }

    public function exports(): HasMany
    {
        return $this->hasMany(Export::class, 'document_version_id');
    }

    public function libraryItems(): HasMany
    {
        return $this->hasMany(LibraryItem::class, 'document_version_id');
    }
}