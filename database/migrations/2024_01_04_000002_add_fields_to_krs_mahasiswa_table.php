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
        if (!Schema::hasTable('krs_mahasiswa')) {
            return;
        }

        Schema::table('krs_mahasiswa', function (Blueprint $table) {
            if (!Schema::hasColumn('krs_mahasiswa', 'semester_id')) {
                $table->foreignId('semester_id')->nullable()->after('mahasiswa_id')->constrained('semesters')->onDelete('set null');
            }

            if (!Schema::hasColumn('krs_mahasiswa', 'semester_ke')) {
                $table->integer('semester_ke')->nullable()->after('semester_id');
            }

            if (!Schema::hasColumn('krs_mahasiswa', 'tahun_ajaran')) {
                $table->string('tahun_ajaran')->nullable()->after('semester_ke');
            }

            if (!Schema::hasColumn('krs_mahasiswa', 'semester')) {
                $table->string('semester', 20)->nullable()->after('tahun_ajaran');
            }

            if (!Schema::hasColumn('krs_mahasiswa', 'total_sks')) {
                $table->integer('total_sks')->default(0)->after('status');
            }

            if (!Schema::hasColumn('krs_mahasiswa', 'catatan')) {
                $table->text('catatan')->nullable()->after('total_sks');
            }

            if (!Schema::hasColumn('krs_mahasiswa', 'tanggal_disetujui')) {
                $table->timestamp('tanggal_disetujui')->nullable()->after('catatan');
            }

            if (!Schema::hasColumn('krs_mahasiswa', 'disetujui_oleh')) {
                $table->unsignedBigInteger('disetujui_oleh')->nullable()->after('tanggal_disetujui');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kolom-kolom ini sekarang dibuat langsung di migration create_krs_mahasiswa_table.
        // Migration ini dipertahankan sebagai no-op untuk database lama yang sudah pernah menjalankannya.
    }
};
