<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Kolom status menandai apakah nilai masih draft (sedang diisi dosen,
     * belum terlihat oleh mahasiswa) atau sudah final (difinalisasi dosen
     * dan tersampaikan ke mahasiswa).
     *
     * Default 'final' dipasang di level kolom agar data nilai yang sudah
     * ada sebelumnya (hasil seeding / input lama) otomatis dianggap sudah
     * final dan tetap tampil seperti biasa di KHS mahasiswa. Untuk input
     * nilai baru lewat form dosen, controller akan secara eksplisit
     * menyimpannya dengan status 'draft' sampai dosen menekan "Finalisasi".
     */
    public function up(): void
    {
        if (Schema::hasTable('nilai') && !Schema::hasColumn('nilai', 'status')) {
            Schema::table('nilai', function (Blueprint $table) {
                $table->string('status', 20)->default('final')->after('dosen_nik');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('nilai') && Schema::hasColumn('nilai', 'status')) {
            Schema::table('nilai', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
