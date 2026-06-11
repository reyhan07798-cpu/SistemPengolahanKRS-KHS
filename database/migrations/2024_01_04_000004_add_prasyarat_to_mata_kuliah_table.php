<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('mata_kuliah') && !Schema::hasColumn('mata_kuliah', 'prasyarat')) {
            Schema::table('mata_kuliah', function (Blueprint $table) {
                $table->string('prasyarat')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('mata_kuliah') && Schema::hasColumn('mata_kuliah', 'prasyarat')) {
            Schema::table('mata_kuliah', function (Blueprint $table) {
                $table->dropColumn('prasyarat');
            });
        }
    }
};
