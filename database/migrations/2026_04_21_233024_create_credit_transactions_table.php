<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['weekly_refill', 'ai_generation', 'bonus', 'admin_grant', 'purchase']);
            $table->smallInteger('amount');
            $table->unsignedSmallInteger('balance_after');
            $table->string('description')->nullable();
            $table->string('reference_id')->nullable();
            $table->unsignedInteger('tokens_used')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_transactions');
    }
};