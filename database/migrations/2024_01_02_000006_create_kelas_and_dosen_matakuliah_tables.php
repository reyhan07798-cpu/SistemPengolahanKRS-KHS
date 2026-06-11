<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kelas')) {
            Schema::create('kelas', function (Blueprint $table) {
                $table->id();
                $table->string('nama_kelas', 50);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('dosen_matakuliah')) {
            Schema::create('dosen_matakuliah', function (Blueprint $table) {
                $table->id();
                $table->foreignId('dosen_id')->constrained('dosen')->onDelete('cascade');
                $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->onDelete('cascade');
                $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen_matakuliah');
        Schema::dropIfExists('kelas');
    }
};
