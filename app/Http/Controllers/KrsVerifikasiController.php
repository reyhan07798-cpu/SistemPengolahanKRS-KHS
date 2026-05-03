<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KrsVerifikasiController extends Controller
{
    private function getTahunAjaranList() { return ['2025/2026', '2024/2025', '2023/2024']; }
    private function getSemesterList()    { return ['Ganjil', 'Genap']; }

    public function index(Request $request)
    {
        $filterStatus      = $request->input('status', 'semua');
        $filterKelas       = $request->input('kelas', 'semua');
        $filterTahunAjaran = $request->input('tahun_ajaran', '2025/2026');
        $filterSemester    = $request->input('semester', 'Genap');

        $allKrs = [
            ['nama' => 'Irenessa Rosidin', 'nim' => '3312501017', 'kelas' => 'A', 'mk_count' => 3, 'total_sks' => 9,  'status' => 'Disetujui', 'tanggal' => '15/8/2026', 'tahun_ajaran' => '2025/2026', 'semester' => 'Genap'],
            ['nama' => 'Nabila Fatin',     'nim' => '3312501007', 'kelas' => 'A', 'mk_count' => 5, 'total_sks' => 15, 'status' => 'Disetujui', 'tanggal' => '15/8/2026', 'tahun_ajaran' => '2025/2026', 'semester' => 'Genap'],
            ['nama' => 'Reyhan',           'nim' => '3312501022', 'kelas' => 'A', 'mk_count' => 4, 'total_sks' => 12, 'status' => 'Menunggu',  'tanggal' => '16/8/2026', 'tahun_ajaran' => '2025/2026', 'semester' => 'Genap'],
            ['nama' => 'Della Reska',      'nim' => '3312501010', 'kelas' => 'B', 'mk_count' => 3, 'total_sks' => 9,  'status' => 'Menunggu',  'tanggal' => '16/8/2026', 'tahun_ajaran' => '2025/2026', 'semester' => 'Genap'],
            ['nama' => 'Samuel Deidra',    'nim' => '3312501023', 'kelas' => 'A', 'mk_count' => 5, 'total_sks' => 15, 'status' => 'Ditolak',   'tanggal' => '17/8/2026', 'tahun_ajaran' => '2024/2025', 'semester' => 'Ganjil'],
        ];

        $daftarKrs = $allKrs;
        if ($filterStatus !== 'semua')
            $daftarKrs = array_filter($daftarKrs, fn($k) => $k['status'] === $filterStatus);
        if ($filterKelas !== 'semua')
            $daftarKrs = array_filter($daftarKrs, fn($k) => $k['kelas'] === $filterKelas);
        if ($filterTahunAjaran !== 'semua')
            $daftarKrs = array_filter($daftarKrs, fn($k) => $k['tahun_ajaran'] === $filterTahunAjaran);
        if ($filterSemester !== 'semua')
            $daftarKrs = array_filter($daftarKrs, fn($k) => $k['semester'] === $filterSemester);

        $daftarKrs = array_values($daftarKrs);

        $stats = [
            'menunggu'  => count(array_filter($allKrs, fn($k) => $k['status'] === 'Menunggu')),
            'disetujui' => count(array_filter($allKrs, fn($k) => $k['status'] === 'Disetujui')),
            'ditolak'   => count(array_filter($allKrs, fn($k) => $k['status'] === 'Ditolak')),
        ];

        $tahunAjaranList = $this->getTahunAjaranList();
        $semesterList    = $this->getSemesterList();

        return view('pages.dosen_wali.krs-verifikasi', compact(
            'stats', 'daftarKrs',
            'filterStatus', 'filterKelas', 'filterTahunAjaran', 'filterSemester',
            'tahunAjaranList', 'semesterList'
        ));
    }

    public function approve($nim) { return redirect()->back()->with('success', 'KRS berhasil disetujui'); }
    public function reject($nim)  { return redirect()->back()->with('error',   'KRS berhasil ditolak'); }
}
