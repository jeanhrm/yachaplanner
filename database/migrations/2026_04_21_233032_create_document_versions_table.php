<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('version')->default(1);
            $table->longText('content_markdown');
            $table->json('content_structured')->nullable();
            $table->unsignedInteger('tokens_input')->nullable();
            $table->unsignedInteger('tokens_output')->nullable();
            $table->unsignedSmallInteger('generation_time_ms')->nullable();
            $table->string('change_summary')->nullable();
            $table->timestamp('exported_at')->nullable();
            $table->timestamps();

            $table->unique(['document_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_versions');
    }
};