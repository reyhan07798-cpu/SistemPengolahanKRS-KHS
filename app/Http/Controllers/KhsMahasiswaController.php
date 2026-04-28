<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
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

        return view('dosen_wali.khs', [
            'nilai' => $nilai,
            'ipk' => 3.64,
            'totalSks' => $nilai->sum('sks'),
            'mataKuliahCount' => $nilai->count(),
            'listTahun' => ['2025/2026', '2024/2025']
        ]);
    }

    private function khsMahasiswa(Request $request)
    {
        $mahasiswaId = auth()->id() ?? 1;

        // Ambil semua nilai mahasiswa
        $nilaiRecords = Nilai::where('mahasiswa_id', $mahasiswaId)
            ->with(['mataKuliah', 'krs'])
            ->orderBy('semester', 'asc')
            ->orderBy('tahun_ajaran', 'desc')
            ->get();

        // Jika belum ada data di database, gunakan dummy data
        if ($nilaiRecords->isEmpty()) {
            $nilai = collect([
                (object) [
                    'kode_mk' => 'IF201',
                    'nama_mk' => 'Basis Data',
                    'sks' => 3,
                    'nilai' => 'A',
                    'bobot' => 4,
                    'tahun_ajaran' => '2025/2026',
                    'semester' => 1,
                    'is_retake' => false,
                    'nilai_lama' => [],
                    'color' => '#22c55e',
                ],
                (object) [
                    'kode_mk' => 'IF202',
                    'nama_mk' => 'Pemrograman Web',
                    'sks' => 3,
                    'nilai' => 'B',
                    'bobot' => 3,
                    'tahun_ajaran' => '2025/2026',
                    'semester' => 1,
                    'is_retake' => false,
                    'nilai_lama' => [],
                    'color' => '#f97316',
                ],
                (object) [
                    'kode_mk' => 'IF101',
                    'nama_mk' => 'Algoritma dan Pemrograman',
                    'sks' => 3,
                    'nilai' => 'B+',
                    'bobot' => 3.3,
                    'tahun_ajaran' => '2025/2026',
                    'semester' => 2,
                    'is_retake' => true,
                    'nilai_lama' => ['E'],
                    'color' => '#eab308',
                ],
            ]);

            $ipk = 3.43;
            $totalSks = 9;
            $mataKuliahCount = 3;
        } else {
            // Hitung IPK dengan aturan retake
            $ipkData = hitungIpkDenganRetake($mahasiswaId);
            $ipk = $ipkData['ipk'];
            $totalSks = $ipkData['total_sks'];
            $mataKuliahCount = $ipkData['total_mk'];

            $nilai = $nilaiRecords->map(function ($item) {
                $history = getNilaiHistory($item->mahasiswa_id, $item->mata_kuliah_id);

                $n = (object) [
                    'kode_mk' => $item->mataKuliah->kode_mk ?? '-',
                    'nama_mk' => $item->mataKuliah->nama ?? 'Mata Kuliah',
                    'sks' => $item->sks,
                    'nilai' => $item->nilai,
                    'bobot' => $item->bobot,
                    'tahun_ajaran' => $item->tahun_ajaran,
                    'semester' => $item->semester,
                    'is_retake' => $item->krs ? $item->krs->is_retake : false,
                    'nilai_lama' => $history['history']->pluck('nilai')->toArray(),
                    'color' => $this->getNilaiColor($item->nilai),
                ];
                return $n;
            });
        }

        return view('mahasiswa.lihat-khs', [
            'nilai' => $nilai,
            'ipk' => $ipk,
            'totalSks' => $totalSks,
            'mataKuliahCount' => $mataKuliahCount,
            'listTahun' => ['2025/2026', '2024/2025']
        ]);
    }

    public function export(Request $request)
    {
        return "Export KHS";
    }
}
