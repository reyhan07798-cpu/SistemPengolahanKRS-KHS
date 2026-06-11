<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom detail nilai dan bobot variabel ke tabel nilai
        Schema::table('nilai', function (Blueprint $table) {
            $table->decimal('nilai_tugas', 5, 2)->nullable()->after('nilai');
            $table->decimal('nilai_praktikum', 5, 2)->nullable()->after('nilai_tugas');
            $table->decimal('nilai_uts', 5, 2)->nullable()->after('nilai_praktikum');
            $table->decimal('nilai_uas', 5, 2)->nullable()->after('nilai_uts');
            $table->decimal('nilai_kehadiran', 5, 2)->nullable()->after('nilai_uas');
            $table->decimal('nilai_akhir', 5, 2)->nullable()->after('nilai_kehadiran');
            $table->string('kelas', 50)->nullable()->after('nilai_akhir');

            // Bobot variabel (dalam persen, default sesuai sistem lama)
            $table->decimal('bobot_tugas', 5, 2)->default(20)->after('kelas');
            $table->decimal('bobot_praktikum', 5, 2)->default(15)->after('bobot_tugas');
            $table->decimal('bobot_uts', 5, 2)->default(30)->after('bobot_praktikum');
            $table->decimal('bobot_uas', 5, 2)->default(30)->after('bobot_uts');
            $table->decimal('bobot_kehadiran', 5, 2)->default(5)->after('bobot_uas');

            // Hubungan ke dosen yang input nilai
            $table->string('dosen_nik')->nullable()->after('bobot_kehadiran');
        });

        // Tambah kolom kelas ke krs_mahasiswa (jika belum ada)
        if (Schema::hasTable('krs_mahasiswa') && !Schema::hasColumn('krs_mahasiswa', 'kelas')) {
            Schema::table('krs_mahasiswa', function (Blueprint $table) {
                $table->string('kelas', 50)->nullable()->after('semester_id');
            });
        }

        // Tambah kolom kelas & dosen ke mata_kuliah (jika belum ada)
        if (Schema::hasTable('mata_kuliah') && !Schema::hasColumn('mata_kuliah', 'kelas')) {
            Schema::table('mata_kuliah', function (Blueprint $table) {
                $table->string('kelas', 50)->nullable()->after('semester_ke');
                $table->string('dosen_nik')->nullable()->after('kelas');
            });
        }
    }

    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropColumn([
                'nilai_tugas','nilai_praktikum','nilai_uts','nilai_uas','nilai_kehadiran',
                'nilai_akhir','kelas','bobot_tugas','bobot_praktikum','bobot_uts','bobot_uas',
                'bobot_kehadiran','dosen_nik'
            ]);
        });
    }
};
