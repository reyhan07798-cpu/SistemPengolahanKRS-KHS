<?php

use App\Models\Nilai;
use App\Models\NilaiHistory;
use App\Models\KrsMahasiswa;

if (!function_exists('getNilaiColor')) {
    function getNilaiColor($nilai)
    {
        $colors = [
            'A' => '#22c55e',   // green
            'A-' => '#84cc16',  // lime
            'B+' => '#eab308',  // yellow
            'B' => '#f97316',   // orange
            'B-' => '#ef4444',  // red
            'C+' => '#dc2626',  // dark-red
            'C' => '#7f1d1d',   // very-dark-red
            'D' => '#4b5563',   // gray
            'E' => '#1f2937'    // dark-gray
        ];
        return $colors[$nilai] ?? '#666';
    }
}

if (!function_exists('getNilaiBobot')) {
    function getNilaiBobot($nilai)
    {
        $bobot = [
            'A' => 4.00,
            'A-' => 3.70,
            'B+' => 3.30,
            'B' => 3.00,
            'B-' => 2.70,
            'C+' => 2.30,
            'C' => 2.00,
            'D' => 1.00,
            'E' => 0.00,
        ];
        return $bobot[$nilai] ?? 0.00;
    }
}

if (!function_exists('cekBolehMengulang')) {
    /**
     * Cek apakah mahasiswa boleh mengulang suatu mata kuliah
     * Boleh mengulang hanya jika nilai terakhir adalah E (tidak boleh D)
     */
    function cekBolehMengulang($mahasiswaId, $mataKuliahId)
    {
        $nilaiTerakhir = Nilai::where('mahasiswa_id', $mahasiswaId)
            ->where('mata_kuliah_id', $mataKuliahId)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$nilaiTerakhir) {
            return false; // Belum pernah ambil, bukan mengulang
        }

        // Boleh mengulang HANYA jika nilai E (D tidak boleh diulang)
        return $nilaiTerakhir->nilai === 'E';
    }
}

if (!function_exists('getMkPengulangan')) {
    /**
     * Ambil daftar mata kuliah yang bisa diulang oleh mahasiswa
     * (MK yang pernah diambil dan nilainya E saja - D tidak boleh diulang)
     */
    function getMkPengulangan($mahasiswaId)
    {
        $nilaiGagal = Nilai::where('mahasiswa_id', $mahasiswaId)
            ->where('nilai', 'E') // Hanya E yang boleh diulang
            ->with('mataKuliah')
            ->get();

        return $nilaiGagal->map(function ($item) {
            return [
                'mata_kuliah_id' => $item->mata_kuliah_id,
                'kode_mk' => $item->mataKuliah->kode_mk ?? '-',
                'nama_mk' => $item->mataKuliah->nama ?? 'Mata Kuliah',
                'sks' => $item->sks,
                'nilai_lama' => $item->nilai,
                'bobot_lama' => $item->bobot,
                'semester_lama' => $item->semester,
                'tahun_ajaran_lama' => $item->tahun_ajaran,
            ];
        });
    }
}

if (!function_exists('getNilaiHistory')) {
    /**
     * Ambil riwayat nilai untuk suatu mata kuliah dan mahasiswa
     */
    function getNilaiHistory($mahasiswaId, $mataKuliahId)
    {
        $history = NilaiHistory::where('mahasiswa_id', $mahasiswaId)
            ->where('mata_kuliah_id', $mataKuliahId)
            ->orderBy('created_at', 'desc')
            ->get();

        $current = Nilai::where('mahasiswa_id', $mahasiswaId)
            ->where('mata_kuliah_id', $mataKuliahId)
            ->orderBy('created_at', 'desc')
            ->first();

        return [
            'history' => $history,
            'current' => $current,
        ];
    }
}

if (!function_exists('hitungIpkDenganRetake')) {
    /**
     * Hitung IPK dengan aturan: ambil nilai TERBAIK per mata kuliah
     * Total SKS dihitung sekali per MK (tidak double count)
     */
    function hitungIpkDenganRetake($mahasiswaId)
    {
        // Ambil semua nilai + history, group by mata_kuliah_id
        $allNilai = Nilai::where('mahasiswa_id', $mahasiswaId)
            ->with('mataKuliah')
            ->get();

        $grouped = $allNilai->groupBy('mata_kuliah_id');

        $totalBobotKaliSks = 0;
        $totalSks = 0;
        $detail = [];

        foreach ($grouped as $mataKuliahId => $nilaiList) {
            // Ambil nilai terbaik untuk MK ini
            $nilaiTerbaik = $nilaiList->sortByDesc('bobot')->first();

            $totalBobotKaliSks += $nilaiTerbaik->bobot * $nilaiTerbaik->sks;
            $totalSks += $nilaiTerbaik->sks;

            $detail[] = [
                'mata_kuliah_id' => $mataKuliahId,
                'nama_mk' => $nilaiTerbaik->mataKuliah->nama ?? '-',
                'sks' => $nilaiTerbaik->sks,
                'nilai_terbaik' => $nilaiTerbaik->nilai,
                'bobot_terbaik' => $nilaiTerbaik->bobot,
                'jumlah_percobaan' => $nilaiList->count(),
                'semua_nilai' => $nilaiList->pluck('nilai')->toArray(),
            ];
        }

        $ipk = $totalSks > 0 ? round($totalBobotKaliSks / $totalSks, 2) : 0;

        return [
            'ipk' => $ipk,
            'total_sks' => $totalSks,
            'total_mk' => count($detail),
            'detail' => $detail,
        ];
    }
}

if (!function_exists('simpanNilaiKeHistory')) {
    /**
     * Simpan nilai lama ke history sebelum update nilai baru (saat mengulang)
     */
    function simpanNilaiKeHistory($nilaiId)
    {
        $nilai = Nilai::find($nilaiId);
        if (!$nilai) return false;

        NilaiHistory::create([
            'mahasiswa_id' => $nilai->mahasiswa_id,
            'mata_kuliah_id' => $nilai->mata_kuliah_id,
            'krs_id' => $nilai->krs_id,
            'nilai' => $nilai->nilai,
            'bobot' => $nilai->bobot,
            'sks' => $nilai->sks,
            'semester' => $nilai->semester,
            'tahun_ajaran' => $nilai->tahun_ajaran,
        ]);

        return true;
    }
}

if (!function_exists('getStatusRetakeBadge')) {
    /**
     * Generate badge HTML untuk status retake
     */
    function getStatusRetakeBadge($isRetake)
    {
        if ($isRetake) {
            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Pengulangan</span>';
        }
        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Baru</span>';
    }
}

