<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DosenWaliController extends Controller
{
    // Data dummy tahun ajaran & semester (bisa diganti dari DB nanti)
    private function getTahunAjaranList()
    {
        return ['2025/2026', '2024/2025', '2023/2024'];
    }

    private function getSemesterList()
    {
        return ['Ganjil', 'Genap'];
    }

    public function index()
    {
        $stats = [
            'mahasiswa_bimbingan' => 3,
            'krs_menunggu'  => 1,
            'krs_disetujui' => 2,
            'krs_ditolak'   => 0,
        ];

        $mahasiswa = [
            ['nama' => 'Reyhan',          'nim' => '3312501022', 'prodi' => 'Teknik Informatika', 'kelas' => 'A', 'ipk' => 3.60, 'status_krs' => 'Disetujui'],
            ['nama' => 'Nabila Fatin',    'nim' => '3312501007', 'prodi' => 'Teknik Informatika', 'kelas' => 'A', 'ipk' => 3.60, 'status_krs' => 'Disetujui'],
            ['nama' => 'Irenessa Rosidin','nim' => '3312501017', 'prodi' => 'Teknik Informatika', 'kelas' => 'A', 'ipk' => 3.60, 'status_krs' => 'Menunggu'],
        ];

        return view('pages.dosen_wali.beranda', compact('stats', 'mahasiswa'));
    }

    public function khs(Request $request)
    {
        $filterKelas      = $request->input('kelas', 'semua');
        $filterTahunAjaran = $request->input('tahun_ajaran', '2025/2026');
        $filterSemester   = $request->input('semester', 'Genap');

        $allMahasiswa = [
            ['ranking' => 1, 'nim' => '3312501007', 'nama' => 'Nabila Fatin',    'kelas' => 'A', 'prodi' => 'Teknik Informatika', 'mk_lulus' => 5, 'ipk' => 3.92, 'status_krs' => 'Aktif'],
            ['ranking' => 2, 'nim' => '3312501017', 'nama' => 'Irenessa Rosidin','kelas' => 'A', 'prodi' => 'Teknik Informatika', 'mk_lulus' => 3, 'ipk' => 3.86, 'status_krs' => 'Aktif'],
            ['ranking' => 3, 'nim' => '3312501022', 'nama' => 'Reyhan',          'kelas' => 'A', 'prodi' => 'Teknik Informatika', 'mk_lulus' => 4, 'ipk' => 3.85, 'status_krs' => 'Aktif'],
        ];

        $mahasiswa = $allMahasiswa;
        if ($filterKelas !== 'semua') {
            $mahasiswa = array_values(array_filter($mahasiswa, fn($m) => $m['kelas'] === $filterKelas));
        }

        $totalMahasiswa = count($allMahasiswa);
        $rataIpk   = $totalMahasiswa > 0 ? array_sum(array_column($allMahasiswa, 'ipk')) / $totalMahasiswa : 0;
        $ipkTinggi = count(array_filter($allMahasiswa, fn($m) => $m['ipk'] >= 3.5));

        $tahunAjaranList = $this->getTahunAjaranList();
        $semesterList    = $this->getSemesterList();

        return view('pages.dosen_wali.khs', compact(
            'mahasiswa', 'filterKelas', 'filterTahunAjaran', 'filterSemester',
            'totalMahasiswa', 'rataIpk', 'ipkTinggi',
            'tahunAjaranList', 'semesterList'
        ));
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'email'  => 'required|email',
            'no_hp'  => 'required|string',
            'alamat' => 'required|string',
        ]);
        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }
}
