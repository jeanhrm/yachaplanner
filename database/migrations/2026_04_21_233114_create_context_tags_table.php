<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('context_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['geography', 'agriculture', 'culture', 'science', 'community']);
            $table->string('district')->nullable();
            $table->string('province')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index(['type', 'district']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('context_tags');
    }
};