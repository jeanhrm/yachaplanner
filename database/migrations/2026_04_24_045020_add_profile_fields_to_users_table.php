<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ugel')->nullable()->after('name');
            $table->string('nivel')->nullable()->after('ugel');
            $table->string('area_docente')->nullable()->after('nivel');
            $table->string('institucion')->nullable()->after('area_docente');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['ugel','nivel','area_docente','institucion']);
        });
    }
};