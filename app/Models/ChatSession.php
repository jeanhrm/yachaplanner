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

    public const DEFAULT_TITLE = 'Nueva sesión';

    /**
     * Mensajes antiguos se guardaban con el contexto pedagógico delante
     * ("Región: X. UGEL: Y. ... Sugiere áreas que se articulan naturalmente. <texto>").
     * Devuelve solo el texto escrito por el docente.
     */
    public static function stripContextPrefix(?string $text): string
    {
        $text = (string) $text;
        return preg_replace('/^Región:.*?Sugiere áreas que se articulan naturalmente\.\s*/su', '', $text) ?? $text;
    }

    public static function makeTitle(string $text): string
    {
        $clean = trim(preg_replace('/\s+/u', ' ', self::stripContextPrefix($text)));
        return $clean === '' ? self::DEFAULT_TITLE : mb_substr($clean, 0, 60);
    }

    public function getDisplayTitleAttribute(): string
    {
        return self::makeTitle($this->title ?? '');
    }

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