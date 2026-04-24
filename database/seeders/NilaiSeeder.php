<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Nilai;

class NilaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data untuk mahasiswa dengan user_id 2 (Reyhan / 3312501022)
        $nilaiData = [
            [
                'mahasiswa_id' => 2,
                'mata_kuliah_id' => 1,
                'nilai' => 'A',
                'bobot' => 3.94,
                'sks' => 3,
                'semester' => 1,
                'tahun_ajaran' => '2025/2026',
            ],
            [
                'mahasiswa_id' => 2,
                'mata_kuliah_id' => 2,
                'nilai' => 'B',
                'bobot' => 3.24,
                'sks' => 3,
                'semester' => 1,
                'tahun_ajaran' => '2025/2026',
            ],
            [
                'mahasiswa_id' => 2,
                'mata_kuliah_id' => 3,
                'nilai' => 'A-',
                'bobot' => 3.64,
                'sks' => 3,
                'semester' => 1,
                'tahun_ajaran' => '2025/2026',
            ],
            [
                'mahasiswa_id' => 2,
                'mata_kuliah_id' => 4,
                'nilai' => 'B+',
                'bobot' => 3.44,
                'sks' => 3,
                'semester' => 1,
                'tahun_ajaran' => '2025/2026',
            ],
            [
                'mahasiswa_id' => 2,
                'mata_kuliah_id' => 5,
                'nilai' => 'A',
                'bobot' => 3.94,
                'sks' => 3,
                'semester' => 1,
                'tahun_ajaran' => '2025/2026',
            ],
            [
                'mahasiswa_id' => 2,
                'mata_kuliah_id' => 1,
                'nilai' => 'A',
                'bobot' => 3.94,
                'sks' => 3,
                'semester' => 2,
                'tahun_ajaran' => '2025/2026',
            ],
            [
                'mahasiswa_id' => 2,
                'mata_kuliah_id' => 2,
                'nilai' => 'B+',
                'bobot' => 3.44,
                'sks' => 3,
                'semester' => 2,
                'tahun_ajaran' => '2025/2026',
            ],
            [
                'mahasiswa_id' => 2,
                'mata_kuliah_id' => 3,
                'nilai' => 'A',
                'bobot' => 3.94,
                'sks' => 3,
                'semester' => 2,
                'tahun_ajaran' => '2025/2026',
            ],
        ];

        foreach ($nilaiData as $nilai) {
            Nilai::create($nilai);
        }
    }
}
