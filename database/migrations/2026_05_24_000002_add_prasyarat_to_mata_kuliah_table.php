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
        // Check both possible table names
        $tableName = Schema::hasTable('mata_kuliah') ? 'mata_kuliah' : 'mata_kuliahs';
        
        if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'prasyarat')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('prasyarat')->nullable()->after('dosen_nik');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = Schema::hasTable('mata_kuliah') ? 'mata_kuliah' : 'mata_kuliahs';
        
        if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'prasyarat')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('prasyarat');
            });
        }
    }
};
