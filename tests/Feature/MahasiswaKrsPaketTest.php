<?php

namespace Tests\Feature;

use App\Http\Controllers\MahasiswaController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MahasiswaKrsPaketTest extends TestCase
{
    public function test_paket_mata_kuliah_tetap_muncul_meski_kelas_tidak_sesuai(): void
    {
        Schema::dropIfExists('krs_detail');
        Schema::dropIfExists('krs_mahasiswa');
        Schema::dropIfExists('paket_mata_kuliah_details');
        Schema::dropIfExists('paket_mata_kuliahs');
        Schema::dropIfExists('mata_kuliah');
        Schema::dropIfExists('nilai');
        Schema::dropIfExists('mahasiswa_semester');
        Schema::dropIfExists('mahasiswa');
        Schema::dropIfExists('dosen');
        Schema::dropIfExists('prodi');
        Schema::dropIfExists('users');
        Schema::dropIfExists('semesters');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('role')->default('mahasiswa');
            $table->timestamps();
        });

        Schema::create('prodi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_prodi');
            $table->timestamps();
        });

        Schema::create('dosen', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('nik')->nullable();
            $table->timestamps();
        });

        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_ajaran');
            $table->string('semester');
            $table->integer('semester_ke');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->string('nim')->nullable();
            $table->string('nama')->nullable();
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('alamat')->nullable();
            $table->string('angkatan')->nullable();
            $table->string('kelas')->nullable();
            $table->timestamps();
        });

        Schema::create('mahasiswa_semester', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('semester_id');
            $table->integer('semester_ke');
            $table->string('status')->default('aktif');
            $table->timestamps();
        });

        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mk');
            $table->string('nama');
            $table->integer('sks');
            $table->integer('semester_ke');
            $table->string('kelas')->nullable();
            $table->string('prasyarat')->nullable();
            $table->unsignedBigInteger('dosen_id')->nullable();
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->timestamps();
        });

        Schema::create('paket_mata_kuliahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket');
            $table->unsignedBigInteger('semester_id');
            $table->unsignedBigInteger('prodi_id');
            $table->timestamps();
        });

        Schema::create('paket_mata_kuliah_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paket_mata_kuliah_id');
            $table->unsignedBigInteger('mata_kuliah_id');
            $table->timestamps();
        });

        Schema::create('krs_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('semester_id');
            $table->string('status')->default('menunggu');
            $table->integer('total_sks')->default(0);
            $table->timestamps();
        });

        Schema::create('krs_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('krs_mahasiswa_id');
            $table->unsignedBigInteger('mata_kuliah_id');
            $table->timestamps();
        });

        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('mata_kuliah_id');
            $table->string('nilai')->nullable();
            $table->string('status')->default('final');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        $userId = DB::table('users')->insertGetId([
            'name' => 'Mahasiswa Uji',
            'username' => 'mhsuji',
            'email' => 'mhsuji@example.com',
            'role' => 'mahasiswa',
        ]);

        $prodiId = DB::table('prodi')->insertGetId([
            'nama_prodi' => 'Teknik Informatika',
        ]);

        $semesterId = DB::table('semesters')->insertGetId([
            'tahun_ajaran' => '2025/2026',
            'semester' => 'Ganjil',
            'semester_ke' => 1,
            'is_active' => true,
        ]);

        $mahasiswaId = DB::table('mahasiswa')->insertGetId([
            'user_id' => $userId,
            'prodi_id' => $prodiId,
            'nim' => '20250001',
            'nama' => 'Mahasiswa Uji',
            'email' => 'mhsuji@example.com',
            'kelas' => 'IF2A',
        ]);

        DB::table('mahasiswa_semester')->insert([
            'mahasiswa_id' => $mahasiswaId,
            'semester_id' => $semesterId,
            'semester_ke' => 1,
            'status' => 'aktif',
        ]);

        $paketId = DB::table('paket_mata_kuliahs')->insertGetId([
            'nama_paket' => 'Paket Semester 1',
            'semester_id' => $semesterId,
            'prodi_id' => $prodiId,
        ]);

        $mk1Id = DB::table('mata_kuliah')->insertGetId([
            'kode_mk' => 'IF101',
            'nama' => 'Dasar Pemrograman',
            'sks' => 3,
            'semester_ke' => 1,
            'kelas' => 'IF2A',
            'prodi_id' => $prodiId,
        ]);

        $mk2Id = DB::table('mata_kuliah')->insertGetId([
            'kode_mk' => 'IF102',
            'nama' => 'Algoritma',
            'sks' => 3,
            'semester_ke' => 1,
            'kelas' => 'IF2B',
            'prodi_id' => $prodiId,
        ]);

        DB::table('paket_mata_kuliah_details')->insert([
            ['paket_mata_kuliah_id' => $paketId, 'mata_kuliah_id' => $mk1Id],
            ['paket_mata_kuliah_id' => $paketId, 'mata_kuliah_id' => $mk2Id],
        ]);

        session(['user' => [
            'id' => $userId,
            'name' => 'Mahasiswa Uji',
            'username' => 'mhsuji',
            'nim' => '20250001',
            'email' => 'mhsuji@example.com',
            'role' => 'mahasiswa',
        ]]);

        $controller = new MahasiswaController();
        $response = $controller->getPaketSemester(new Request([
            'semester' => 'Ganjil',
            'tahun_ajaran' => '2025/2026',
        ]));

        $payload = $response->getData(true);

        $this->assertFalse($payload['error'] ?? true);
        $this->assertCount(2, $payload['paket_semester']['wajib']);
        $this->assertSame(['IF101', 'IF102'], collect($payload['paket_semester']['wajib'])->pluck('kode')->all());
    }
}
