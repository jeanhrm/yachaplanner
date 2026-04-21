<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cneb_competencies', function (Blueprint $table) {
            $table->id();
            $table->string('area');
            $table->enum('level', ['inicial', 'primaria', 'secundaria']);
            $table->string('competency_code')->nullable();
            $table->text('competency');
            $table->json('capacities');
            $table->json('grade_range');
            $table->json('performance_descriptors')->nullable();
            $table->enum('approach', ['steam', 'general', 'both'])->default('general');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['area', 'level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cneb_competencies');
    }
};