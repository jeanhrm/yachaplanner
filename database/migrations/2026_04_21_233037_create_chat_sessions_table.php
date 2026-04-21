<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('document_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('module', ['bimestral', 'sesion', 'abp', 'rubrica', 'general']);
            $table->string('title')->nullable();
            $table->string('system_prompt_version')->nullable();
            $table->json('injected_context')->nullable();
            $table->enum('status', ['active', 'completed', 'archived'])->default('active');
            $table->unsignedInteger('total_tokens_used')->default(0);
            $table->unsignedTinyInteger('messages_count')->default(0);
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'module', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_sessions');
    }
};