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
        Schema::create('paket_mata_kuliahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket');
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->onDelete('set null');
            $table->foreignId('prodi_id')->nullable()->constrained('prodi')->onDelete('set null');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('paket_mata_kuliah_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paket_mata_kuliah_id')->constrained('paket_mata_kuliahs')->onDelete('cascade');
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_mata_kuliah_details');
        Schema::dropIfExists('paket_mata_kuliahs');
    }
};
