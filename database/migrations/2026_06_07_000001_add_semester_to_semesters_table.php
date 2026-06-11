<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('semesters') || Schema::hasColumn('semesters', 'semester')) {
            return;
        }

        Schema::table('semesters', function (Blueprint $table) {
            $table->string('semester')->nullable()->after('tahun_ajaran');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('semesters') || !Schema::hasColumn('semesters', 'semester')) {
            return;
        }

        Schema::table('semesters', function (Blueprint $table) {
            $table->dropColumn('semester');
        });
    }
};
