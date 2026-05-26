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
            $table->timestamps();
        });
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->foreignId('semester_id')->nullable()->after('sks')->constrained('semesters')->onDelete('set null');
            $table->string('tahun_ajaran')->nullable()->after('semester_id');
            $table->integer('semester_ke')->nullable()->after('tahun_ajaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliahs');
    }
};
