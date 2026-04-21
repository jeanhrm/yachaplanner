<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('module', ['bimestral', 'sesion', 'abp', 'rubrica', 'unidad']);
            $table->string('title');
            $table->string('area')->nullable();
            $table->string('grade')->nullable();
            $table->enum('level', ['inicial', 'primaria', 'secundaria'])->nullable();
            $table->unsignedTinyInteger('bimester')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('school_year')->nullable();
            $table->text('situation')->nullable();
            $table->json('competencies')->nullable();
            $table->json('methodology_tags')->nullable();
            $table->json('context_tags')->nullable();
            $table->enum('status', ['draft', 'completed', 'archived'])->default('draft');
            $table->unsignedTinyInteger('current_version')->default(1);
            $table->timestamp('last_exported_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'module', 'status']);
            $table->index(['institution_id', 'module']);
            $table->index(['area', 'grade', 'level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};