<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('module_configs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('version')->default('1.0');
            $table->longText('system_prompt');
            $table->json('fields_schema');
            $table->unsignedSmallInteger('max_tokens')->default(2000);
            $table->string('model')->default('claude-sonnet-4-20250514');
            $table->decimal('temperature', 3, 2)->default(0.7);
            $table->unsignedTinyInteger('credits_cost')->default(1);
            $table->boolean('export_docx_enabled')->default(true);
            $table->boolean('export_pdf_enabled')->default(true);
            $table->boolean('is_active')->default(true);
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('module_configs');
    }
};