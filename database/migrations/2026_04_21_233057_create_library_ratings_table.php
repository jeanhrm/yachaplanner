<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('library_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->boolean('is_verified_use')->default(false);
            $table->timestamps();

            $table->unique(['library_item_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_ratings');
    }
};