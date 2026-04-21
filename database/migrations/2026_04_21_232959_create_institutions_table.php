<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ugel_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('code_modular')->unique()->nullable();
            $table->string('district');
            $table->string('province')->default('Huancavelica');
            $table->string('region')->default('Huancavelica');
            $table->enum('level', ['inicial', 'primaria', 'secundaria', 'multigrado'])->default('primaria');
            $table->boolean('is_multigrado')->default(false);
            $table->boolean('is_bilingual')->default(false);
            $table->string('predominant_language')->default('español');
            $table->json('context_tags')->nullable();
            $table->text('local_context')->nullable();
            $table->string('logo_path')->nullable();
            $table->enum('plan', ['free', 'institution'])->default('free');
            $table->timestamp('plan_expires_at')->nullable();
            $table->unsignedSmallInteger('teacher_seats')->default(5);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
