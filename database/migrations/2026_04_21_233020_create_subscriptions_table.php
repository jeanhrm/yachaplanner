<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained();
            $table->nullableMorphs('subscriber');
            $table->string('culqi_charge_id')->nullable();
            $table->string('culqi_customer_id')->nullable();
            $table->unsignedInteger('amount_paid_soles')->default(0);
            $table->enum('status', ['active', 'cancelled', 'expired', 'trial', 'pending'])->default('active');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->string('invoice_ruc')->nullable();
            $table->string('invoice_razon_social')->nullable();
            $table->string('invoice_address')->nullable();
            $table->string('invoice_number')->nullable();
            $table->timestamps();

            $table->index(['subscriber_type', 'subscriber_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};