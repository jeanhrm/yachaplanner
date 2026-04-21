<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('ugel_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->foreignId('institution_id')->nullable()->after('ugel_id')->constrained()->nullOnDelete();
            $table->enum('role', ['super_admin', 'ugel_admin', 'institution_admin', 'teacher'])->default('teacher')->after('email');
            $table->string('speciality')->nullable()->after('role');
            $table->json('areas')->nullable()->after('speciality');
            $table->json('grades')->nullable()->after('areas');
            $table->string('avatar_path')->nullable()->after('grades');
            $table->enum('plan', ['free', 'pro'])->default('free')->after('avatar_path');
            $table->timestamp('plan_expires_at')->nullable()->after('plan');
            $table->unsignedSmallInteger('weekly_credits_used')->default(0)->after('plan_expires_at');
            $table->unsignedSmallInteger('weekly_credits_limit')->default(5)->after('weekly_credits_used');
            $table->timestamp('credits_reset_at')->nullable()->after('weekly_credits_limit');
            $table->json('preferences')->nullable()->after('credits_reset_at');
            $table->string('locale')->default('es')->after('preferences');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'ugel_id', 'institution_id', 'role', 'speciality',
                'areas', 'grades', 'avatar_path', 'plan', 'plan_expires_at',
                'weekly_credits_used', 'weekly_credits_limit',
                'credits_reset_at', 'preferences', 'locale'
            ]);
        });
    }
};