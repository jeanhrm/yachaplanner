<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleConfig extends Model
{
    protected $fillable = [
        'slug', 'name', 'icon', 'version',
        'system_prompt', 'fields_schema',
        'max_tokens', 'model', 'temperature',
        'credits_cost', 'export_docx_enabled',
        'export_pdf_enabled', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'fields_schema'       => 'array',
        'export_docx_enabled' => 'boolean',
        'export_pdf_enabled'  => 'boolean',
        'is_active'           => 'boolean',
        'temperature'         => 'float',
    ];

    public function buildSystemPrompt(array $context): string
    {
        $prompt = $this->system_prompt;
        foreach ($context as $key => $value) {
            $prompt = str_replace('{{' . $key . '}}', $value ?? '', $prompt);
        }
        return $prompt;
    }

    public static function findBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)
                     ->where('is_active', true)
                     ->first();
    }
}