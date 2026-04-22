<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Export extends Model
{
    protected $fillable = [
        'document_version_id', 'user_id', 'format',
        'file_path', 'file_name', 'file_size_bytes',
        'has_watermark', 'download_count',
        'last_downloaded_at', 'expires_at',
    ];

    protected $casts = [
        'has_watermark'      => 'boolean',
        'last_downloaded_at' => 'datetime',
        'expires_at'         => 'datetime',
    ];

    public function documentVersion(): BelongsTo
    {
        return $this->belongsTo(DocumentVersion::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}