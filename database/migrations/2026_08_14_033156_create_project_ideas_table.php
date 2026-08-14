<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_ideas', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('problem_type'); // ambiental, social, tecnologico, cultural, salud, economico
            $table->string('level');        // Primaria, Secundaria, Ambos
            $table->json('areas');          // áreas curriculares involucradas
            $table->string('methodology');  // ABP, 5E, Design Thinking, etc
            $table->text('steam_connection');
            $table->text('ancestral_knowledge')->nullable();
            $table->text('prototype_ideas');
            $table->string('difficulty');   // basico, intermedio, avanzado
            $table->string('duration');     // 2 semanas, 1 mes, etc
            $table->boolean('is_active')->default(true);
            $table->boolean('is_user_submitted')->default(false);
            $table->foreignId('submitted_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_ideas');
    }
};