<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibraryItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'document_version_id', 'user_id', 'institution_id', 'ugel_id',
        'title', 'module', 'area', 'grade', 'level', 'bimester',
        'school_year', 'description', 'methodology_tags', 'context_tags',
        'visibility', 'status', 'rejection_reason',
        'view_count', 'download_count', 'rating_avg',
        'rating_count', 'bookmark_count', 'published_at',
    ];

    protected $casts = [
        'methodology_tags' => 'array',
        'context_tags'     => 'array',
        'published_at'     => 'datetime',
        'rating_avg'       => 'float',
    ];

    public function documentVersion(): BelongsTo
    {
        return $this->belongsTo(DocumentVersion::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function ugel(): BelongsTo
    {
        return $this->belongsTo(Ugel::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(LibraryRating::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(LibraryBookmark::class);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}