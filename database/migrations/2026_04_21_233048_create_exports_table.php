<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_version_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('format', ['docx', 'pdf']);
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedInteger('file_size_bytes')->nullable();
            $table->boolean('has_watermark')->default(true);
            $table->unsignedSmallInteger('download_count')->default(0);
            $table->timestamp('last_downloaded_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'format']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exports');
    }
};