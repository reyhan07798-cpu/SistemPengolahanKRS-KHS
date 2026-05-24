<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder khusus untuk data Dosen Mata Kuliah.
 * Jalankan: php artisan db:seed --class=DosenMKSeeder
 */
class DosenMKSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan semester aktif ada
        $semId = DB::table('semesters')->where('is_active', true)->value('id');
        if (!$semId) {
            $semId = DB::table('semesters')->insertGetId([
                'nama'          => 'Semester Genap 2025/2026',
                'tahun_ajaran'  => '2025/2026',
                'semester_ke'   => 2,
                'tanggal_mulai' => '2026-01-01',
                'tanggal_selesai' => '2026-06-30',
                'is_active'     => true,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        // 2. Mata kuliah yang diampu dosen dengan NIK 87654321
        $mataKuliah = [
            ['kode_mk'=>'IF101','nama'=>'Pemrograman Dasar',              'sks'=>3,'semester_ke'=>1,'kelas'=>'A','dosen_nik'=>'87654321'],
            ['kode_mk'=>'IF102','nama'=>'Basis Data',                      'sks'=>3,'semester_ke'=>2,'kelas'=>'A','dosen_nik'=>'87654321'],
            ['kode_mk'=>'IF103','nama'=>'Pemrograman Berorientasi Objek', 'sks'=>3,'semester_ke'=>3,'kelas'=>'A','dosen_nik'=>'87654321'],
        ];

        $mkIds = [];
        foreach ($mataKuliah as $mk) {
            $existing = DB::table('mata_kuliah')->where('kode_mk', $mk['kode_mk'])->first();
            if ($existing) {
                DB::table('mata_kuliah')->where('id', $existing->id)->update([
                    'dosen_nik'   => $mk['dosen_nik'],
                    'kelas'       => $mk['kelas'],
                    'semester_ke' => $mk['semester_ke'],
                    'tahun_ajaran'=> '2025/2026',
                    'semester_id' => $semId,
                ]);
                $mkIds[$mk['kode_mk']] = $existing->id;
            } else {
                $id = DB::table('mata_kuliah')->insertGetId(array_merge($mk, [
                    'tahun_ajaran' => '2025/2026',
                    'semester_id'  => $semId,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]));
                $mkIds[$mk['kode_mk']] = $id;
            }
        }

        // 3. Pastikan ada users mahasiswa (id 2,3,4)
        $mahasiswaData = [
            ['id'=>2,'name'=>'Reyhan',           'email'=>'reyhan@student.com'],
            ['id'=>3,'name'=>'Nabila Fatin',     'email'=>'nabila@student.com'],
            ['id'=>4,'name'=>'Irenessa Rosidin', 'email'=>'irenessa@student.com'],
        ];
        foreach ($mahasiswaData as $m) {
            if (!DB::table('users')->where('id', $m['id'])->exists()) {
                DB::table('users')->insert(array_merge($m, [
                    'password'   => Hash::make($m['email']),
                    'created_at' => now(), 'updated_at' => now(),
                ]));
            }
        }

        // 4. Daftarkan mahasiswa ke KRS (status disetujui)
        foreach ($mahasiswaData as $mhs) {
            foreach ($mkIds as $kodeMk => $mkId) {
                $exists = DB::table('krs_mahasiswa')
                    ->where('mahasiswa_id', $mhs['id'])
                    ->where('mata_kuliah_id', $mkId)
                    ->exists();
                if (!$exists) {
                    DB::table('krs_mahasiswa')->insert([
                        'mahasiswa_id'   => $mhs['id'],
                        'mata_kuliah_id' => $mkId,
                        'kelas'          => 'A',
                        'status'         => 'disetujui',
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);
                }
            }
        }

        $this->command->info('DosenMKSeeder berhasil! Login: NIK 87654321, password 87654321');
    }
}