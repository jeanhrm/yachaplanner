<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatSession extends Model
{
    protected $fillable = [
        'user_id', 'document_id', 'module', 'title',
        'system_prompt_version', 'injected_context',
        'status', 'total_tokens_used', 'messages_count',
        'last_message_at',
    ];

    protected $casts = [
        'injected_context' => 'array',
        'last_message_at'  => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'session_id');
    }

    public function lastMessage(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'session_id')
                    ->latest()
                    ->limit(1);
    }
}