<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('chat_sessions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['user', 'assistant']);
            $table->longText('content');
            $table->unsignedInteger('tokens_input')->nullable();
            $table->unsignedInteger('tokens_output')->nullable();
            $table->unsignedSmallInteger('latency_ms')->nullable();
            $table->string('model_used')->nullable();
            $table->foreignId('generated_document_version_id')
                  ->nullable()
                  ->constrained('document_versions')
                  ->nullOnDelete();
            $table->boolean('is_selected')->default(false);
            $table->boolean('is_regenerated')->default(false);
            $table->timestamps();

            $table->index(['session_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};