<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('steam_stage')->default(0);        // etapa actual del proyecto en curso
            $table->integer('projects_completed')->default(0); // proyectos completados total
            $table->json('badges')->nullable();                 // insignias ganadas
            $table->json('current_journey')->nullable();        // datos del proyecto en curso
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['steam_stage','projects_completed','badges','current_journey']);
        });
    }
};