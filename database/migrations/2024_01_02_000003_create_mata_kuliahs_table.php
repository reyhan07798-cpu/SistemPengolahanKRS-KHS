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
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mk');
            $table->string('nama');
            $table->integer('sks');
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->onDelete('set null');
            $table->foreignId('dosen_id')->nullable()->constrained('dosen')->onDelete('set null');
            $table->string('tahun_ajaran')->nullable();
            $table->integer('semester_ke')->nullable();
            $table->string('kelas', 50)->nullable();
            $table->string('dosen_nik')->nullable();
            $table->string('prasyarat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah');
    }
};
