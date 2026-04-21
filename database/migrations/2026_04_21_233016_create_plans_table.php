<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('target', ['user', 'institution'])->default('user');
            $table->unsignedInteger('price_soles')->default(0);
            $table->enum('billing_cycle', ['weekly', 'monthly', 'yearly'])->default('monthly');
            $table->unsignedSmallInteger('weekly_ai_credits')->default(5);
            $table->unsignedSmallInteger('teacher_seats')->default(1);
            $table->json('features');
            $table->boolean('export_watermark')->default(true);
            $table->boolean('library_publish')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};