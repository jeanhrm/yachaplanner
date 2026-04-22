<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentVersion extends Model
{
    protected $fillable = [
        'document_id', 'version', 'content_markdown',
        'content_structured', 'tokens_input', 'tokens_output',
        'generation_time_ms', 'change_summary', 'exported_at',
    ];

    protected $casts = [
        'content_structured' => 'array',
        'exported_at'        => 'datetime',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function exports(): HasMany
    {
        return $this->hasMany(Export::class);
    }

    public function libraryItems(): HasMany
    {
        return $this->hasMany(LibraryItem::class);
    }

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'generated_document_version_id');
    }
}