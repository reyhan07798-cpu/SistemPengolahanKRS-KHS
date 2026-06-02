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
        Schema::table('dosen', function (Blueprint $table) {
            // Add columns if they don't exist
            if (!Schema::hasColumn('dosen', 'tipe_dosen')) {
                $table->string('tipe_dosen')->nullable()->after('alamat');
            }
            
            if (!Schema::hasColumn('dosen', 'fakultas')) {
                $table->string('fakultas')->nullable()->after('tipe_dosen');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosen', function (Blueprint $table) {
            if (Schema::hasColumn('dosen', 'tipe_dosen')) {
                $table->dropColumn('tipe_dosen');
            }
            
            if (Schema::hasColumn('dosen', 'fakultas')) {
                $table->dropColumn('fakultas');
            }
        });
    }
};
