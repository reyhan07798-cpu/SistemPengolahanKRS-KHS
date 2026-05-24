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
        Schema::table('krs_mahasiswa', function (Blueprint $table) {
            // Tambahkan field untuk tracking semester & tahun ajaran
            $table->integer('semester_ke')->nullable()->after('mata_kuliah_id');
            $table->string('tahun_ajaran')->nullable()->after('semester_ke');
            $table->text('catatan')->nullable()->after('status');
            $table->timestamp('tanggal_disetujui')->nullable()->after('catatan');
            $table->unsignedBigInteger('disetujui_oleh')->nullable()->after('tanggal_disetujui');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('krs_mahasiswa', function (Blueprint $table) {
            $table->dropColumn([
                'semester_ke',
                'tahun_ajaran',
                'catatan',
                'tanggal_disetujui',
                'disetujui_oleh'
            ]);
        });
    }
};
