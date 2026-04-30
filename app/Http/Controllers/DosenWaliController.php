<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DosenWaliController extends Controller
{
    public function index()
    {
        $stats = [
            'mahasiswa_bimbingan' => 3,
            'krs_menunggu' => 0,
            'krs_disetujui' => 2,
            'krs_ditolak' => 0,
        ];

        $mahasiswa = [
            ['nama' => 'Reyhan', 'nim' => '3312501022', 'prodi' => 'Teknik Informatika', 'kelas' => 'A', 'ipk' => 3.60, 'status_krs' => 'Disetujui'],
            ['nama' => 'Nabila Fatin', 'nim' => '3312501007', 'prodi' => 'Teknik Informatika', 'kelas' => 'A', 'ipk' => 3.60, 'status_krs' => 'Disetujui'],
            ['nama' => 'Irenessa Rosidin', 'nim' => '3312501017', 'prodi' => 'Teknik Informatika', 'kelas' => 'A', 'ipk' => 3.60, 'status_krs' => 'Belum KRS'],
        ];

        return view('pages.dosen_wali.beranda', compact('stats', 'mahasiswa'));
    }

    public function verifikasiKrs(Request $request)
    {
        $filterStatus = $request->input('status', 'semua');
        $filterKelas  = $request->input('kelas', 'semua');

        $allKrs = [
            ['nim' => '3312501022', 'nama' => 'Reyhan', 'kelas' => 'A', 'mk_count' => 6, 'total_sks' => 18, 'status' => 'Disetujui', 'tanggal' => '2025-01-10'],
            ['nim' => '3312501007', 'nama' => 'Nabila Fatin', 'kelas' => 'A', 'mk_count' => 6, 'total_sks' => 18, 'status' => 'Disetujui', 'tanggal' => '2025-01-11'],
            ['nim' => '3312501017', 'nama' => 'Irenessa Rosidin', 'kelas' => 'A', 'mk_count' => 0, 'total_sks' => 0, 'status' => 'Menunggu', 'tanggal' => '-'],
        ];

        $daftarKrs = $allKrs;
        if ($filterStatus != 'semua') {
            $daftarKrs = array_values(array_filter($daftarKrs, fn($k) => $k['status'] == $filterStatus));
        }
        if ($filterKelas != 'semua') {
            $daftarKrs = array_values(array_filter($daftarKrs, fn($k) => $k['kelas'] == $filterKelas));
        }

        $stats = [
            'menunggu'  => count(array_filter($allKrs, fn($k) => $k['status'] == 'Menunggu')),
            'disetujui' => count(array_filter($allKrs, fn($k) => $k['status'] == 'Disetujui')),
            'ditolak'   => count(array_filter($allKrs, fn($k) => $k['status'] == 'Ditolak')),
        ];

        return view('pages.dosen_wali.krs-verifikasi', compact('daftarKrs', 'stats', 'filterStatus', 'filterKelas'));
    }

    public function approveKrs($nim)
    {
        return redirect()->back()->with('success', 'KRS mahasiswa berhasil disetujui.');
    }

    public function rejectKrs($nim)
    {
        return redirect()->back()->with('error', 'KRS mahasiswa telah ditolak.');
    }

    public function khs(Request $request)
    {
        $filterKelas = $request->input('kelas', 'semua');

        $allMahasiswa = [
            ['ranking' => 1, 'nim' => '3312501017', 'nama' => 'Irenessa Rosidin', 'kelas' => 'A', 'prodi' => 'Teknik Informatika', 'mk_lulus' => 3, 'ipk' => 3.86, 'status_krs' => 'Aktif'],
            ['ranking' => 2, 'nim' => '3312501007', 'nama' => 'Nabila Fatin', 'kelas' => 'A', 'prodi' => 'Teknik Informatika', 'mk_lulus' => 5, 'ipk' => 3.92, 'status_krs' => 'Aktif'],
            ['ranking' => 3, 'nim' => '3312501022', 'nama' => 'Reyhan', 'kelas' => 'A', 'prodi' => 'Teknik Informatika', 'mk_lulus' => 4, 'ipk' => 3.85, 'status_krs' => 'Aktif'],
        ];

        $mahasiswa = $allMahasiswa;
        if ($filterKelas != 'semua') {
            $mahasiswa = array_values(array_filter($mahasiswa, fn($m) => $m['kelas'] == $filterKelas));
        }

        $totalMahasiswa = count($allMahasiswa);
        $rataIpk = $totalMahasiswa > 0 ? array_sum(array_column($allMahasiswa, 'ipk')) / $totalMahasiswa : 0;
        $ipkTinggi = count(array_filter($allMahasiswa, fn($m) => $m['ipk'] >= 3.5));

        return view('pages.dosen_wali.khs', compact('mahasiswa', 'filterKelas', 'totalMahasiswa', 'rataIpk', 'ipkTinggi'));
    }
}
