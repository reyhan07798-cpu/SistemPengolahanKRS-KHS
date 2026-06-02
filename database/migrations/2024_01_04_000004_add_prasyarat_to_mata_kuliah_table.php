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
        if (Schema::hasTable('mata_kuliahs') && !Schema::hasColumn('mata_kuliahs', 'prasyarat')) {
            Schema::table('mata_kuliahs', function (Blueprint $table) {
                $table->string('prasyarat')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('mata_kuliahs') && Schema::hasColumn('mata_kuliahs', 'prasyarat')) {
            Schema::table('mata_kuliahs', function (Blueprint $table) {
                $table->dropColumn('prasyarat');
            });
        }
    }
};
