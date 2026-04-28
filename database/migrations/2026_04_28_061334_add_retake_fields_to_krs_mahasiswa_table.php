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
            $table->integer('semester')->nullable()->after('mata_kuliah_id');
            $table->string('tahun_ajaran')->nullable()->after('semester');
            $table->boolean('is_retake')->default(false)->after('tahun_ajaran');
            $table->enum('status_perkuliahan', ['aktif', 'selesai', 'dibatalkan'])->default('aktif')->after('is_retake');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('krs_mahasiswa', function (Blueprint $table) {
            $table->dropColumn(['semester', 'tahun_ajaran', 'is_retake', 'status_perkuliahan']);
        });
    }
};
