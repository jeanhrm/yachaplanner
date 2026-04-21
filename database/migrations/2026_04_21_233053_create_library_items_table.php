<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_version_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('ugel_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->enum('module', ['bimestral', 'sesion', 'abp', 'rubrica']);
            $table->string('area')->nullable();
            $table->string('grade')->nullable();
            $table->enum('level', ['inicial', 'primaria', 'secundaria'])->nullable();
            $table->unsignedTinyInteger('bimester')->nullable();
            $table->string('school_year')->nullable();
            $table->text('description')->nullable();
            $table->json('methodology_tags')->nullable();
            $table->json('context_tags')->nullable();
            $table->enum('visibility', ['public', 'institution', 'ugel'])->default('public');
            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])->default('approved');
            $table->string('rejection_reason')->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('download_count')->default(0);
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->unsignedSmallInteger('rating_count')->default(0);
            $table->unsignedSmallInteger('bookmark_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['module', 'area', 'grade', 'visibility', 'status']);
            $table->index(['ugel_id', 'status']);
            $table->index('rating_avg');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_items');
    }
};