<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = [
        'session_id', 'user_id', 'role', 'content',
        'tokens_input', 'tokens_output', 'latency_ms',
        'model_used', 'generated_document_version_id',
        'is_selected', 'is_regenerated',
    ];

    protected $casts = [
        'is_selected'    => 'boolean',
        'is_regenerated' => 'boolean',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class, 'session_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function generatedVersion(): BelongsTo
    {
        return $this->belongsTo(DocumentVersion::class, 'generated_document_version_id');
    }

    public function isFromUser(): bool
    {
        return $this->role === 'user';
    }

    public function isFromAssistant(): bool
    {
        return $this->role === 'assistant';
    }
}