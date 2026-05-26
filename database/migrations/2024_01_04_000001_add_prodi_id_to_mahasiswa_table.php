<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            // Tambahkan kolom prodi_id, nullable agar data lama aman
            $table->unsignedBigInteger('prodi_id')->nullable()->after('dosen_wali_id');

            // Tambahkan foreign key constraint
            $table->foreign('prodi_id')
                  ->references('id')
                  ->on('prodi')
                  ->onDelete('set null'); // Jika prodi dihapus, mahasiswa tidak ikut terhapus, hanya prodi_id-nya yang jadi null
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            // Hapus foreign key dulu baru hapus kolom saat rollback
            $table->dropForeign(['prodi_id']);
            $table->dropColumn('prodi_id');
        });
    }
};