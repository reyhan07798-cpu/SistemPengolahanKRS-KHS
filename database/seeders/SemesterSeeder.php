<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        $semesters = [
            [
                'nama' => 'Semester Genap 2025/2026',
                'tahun_ajaran' => '2025/2026',
                'semester_ke' => 2,
                'tanggal_mulai' => '2026-02-01',
                'tanggal_selesai' => '2026-06-30',
                'is_active' => true,
            ],
            [
                'nama' => 'Semester Ganjil 2025/2026',
                'tahun_ajaran' => '2025/2026',
                'semester_ke' => 1,
                'tanggal_mulai' => '2025-09-01',
                'tanggal_selesai' => '2026-01-31',
                'is_active' => false,
            ],
            [
                'nama' => 'Semester Genap 2024/2025',
                'tahun_ajaran' => '2024/2025',
                'semester_ke' => 2,
                'tanggal_mulai' => '2025-02-01',
                'tanggal_selesai' => '2025-06-30',
                'is_active' => false,
            ],
        ];

        foreach ($semesters as $semester) {
            Semester::create($semester);
        }
    }
}