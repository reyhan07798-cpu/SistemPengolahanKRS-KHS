<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KhsMahasiswaController extends Controller
{
    // HELPER WARNA NILAI
    private function getNilaiColor($nilai)
    {
        switch ($nilai) {
            case 'A':
                return '#22c55e';   // green
            case 'A-':
                return '#84cc16';  // lime
            case 'B+':
                return '#eab308';  // yellow
            case 'B':
                return '#f97316';   // orange
            case 'B-':
                return '#ef4444';  // red
            case 'C+':
                return '#dc2626';  // dark-red
            case 'C':
                return '#7f1d1d';   // very-dark-red
            case 'D':
                return '#4b5563';   // gray
            case 'E':
                return '#1f2937';   // dark-gray
            default:
                return '#666';      // fallback
        }
    }

    public function index(Request $request)
    {
        $role = $request->input('role', 'mahasiswa');

        if ($role === 'dosen') {
            return $this->khsDosen($request);
        }

        return $this->khsMahasiswa($request);
    }

    private function khsDosen(Request $request)
    {
        $nilai = collect([
            (object) [
                'kode_mk' => 'IF201',
                'nama_mk' => 'Basis Data',
                'sks' => 3,
                'nilai' => 'A',
                'bobot' => 4,
                'tahun_ajaran' => '2025/2026',
                'semester' => 1
            ],
            (object) [
                'kode_mk' => 'IF202',
                'nama_mk' => 'Pemrograman Web',
                'sks' => 3,
                'nilai' => 'B',
                'bobot' => 3,
                'tahun_ajaran' => '2025/2026',
                'semester' => 1
            ],
        ]);

        // Tambahkan warna ke setiap nilai
        $nilai = $nilai->map(function ($n) {
            $n->color = $this->getNilaiColor($n->nilai);
            return $n;
        });

        return view('pages.dosen.lihat-khs', [
            'nilai' => $nilai,
            'ipk' => 3.64,
            'totalSks' => $nilai->sum('sks'),
            'mataKuliahCount' => $nilai->count(),
            'listTahun' => ['2025/2026', '2024/2025']
        ]);
    }

    private function khsMahasiswa(Request $request)
    {
        $nilai = collect([
            (object) [
                'kode_mk' => 'IF201',
                'nama_mk' => 'Basis Data',
                'sks' => 3,
                'nilai' => 'A',
                'bobot' => 4,
                'tahun_ajaran' => '2025/2026',
                'semester' => 1
            ],
            (object) [
                'kode_mk' => 'IF202',
                'nama_mk' => 'Pemrograman Web',
                'sks' => 3,
                'nilai' => 'B',
                'bobot' => 3,
                'tahun_ajaran' => '2025/2026',
                'semester' => 1
            ],
        ]);

        // Tambahkan warna ke setiap nilai
        $nilai = $nilai->map(function ($n) {
            $n->color = $this->getNilaiColor($n->nilai);
            return $n;
        });

        return view('pages.mahasiswa.lihat-khs', [
            'nilai' => $nilai,
            'ipk' => 3.64,
            'totalSks' => $nilai->sum('sks'),
            'mataKuliahCount' => $nilai->count(),
            'listTahun' => ['2025/2026', '2024/2025']
        ]);
    }

    public function export(Request $request)
    {
        return "Export KHS";
    }
}
