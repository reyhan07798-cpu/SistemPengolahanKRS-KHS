<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KrsVerifikasiController extends Controller
{
    public function index(Request $request)
    {
        $filterStatus = $request->input('status', 'semua');
        $filterKelas  = $request->input('kelas', 'semua');

        $allKrs = [
            [
                'nama' => 'Irenessa Rosidin',
                'nim' => '3312501017',
                'kelas' => 'A',
                'mk_count' => 3,
                'total_sks' => 20,
                'status' => 'Disetujui',
                'tanggal' => '15/8/2026'
            ],
            [
                'nama' => 'Nabila Fatin',
                'nim' => '3312501007',
                'kelas' => 'A',
                'mk_count' => 5,
                'total_sks' => 20,
                'status' => 'Disetujui',
                'tanggal' => '15/8/2026'
            ],
            [
                'nama' => 'Reyhan',
                'nim' => '3312501022',
                'kelas' => 'A',
                'mk_count' => 4,
                'total_sks' => 20,
                'status' => 'Menunggu',
                'tanggal' => '16/8/2026'
            ],
            [
                'nama' => 'Della Reska',
                'nim' => '3312501010',
                'kelas' => 'B',
                'mk_count' => 3,
                'total_sks' => 20,
                'status' => 'Menunggu',
                'tanggal' => '16/8/2026'
            ],
            [
                'nama' => 'Samuel Deidra',
                'nim' => '3312501023',
                'kelas' => 'A',
                'mk_count' => 5,
                'total_sks' => 20,
                'status' => 'Ditolak',
                'tanggal' => '17/8/2026'
            ],
        ];

        $daftarKrs = $allKrs;
        
        if ($filterStatus != 'semua') {
            $daftarKrs = array_filter($daftarKrs, function($krs) use ($filterStatus) {
                return $krs['status'] == $filterStatus;
            });
        }
        
        if ($filterKelas != 'semua') {
            $daftarKrs = array_filter($daftarKrs, function($krs) use ($filterKelas) {
                return $krs['kelas'] == $filterKelas;
            });
        }

        $daftarKrs = array_values($daftarKrs);

        $stats = [
            'menunggu'  => count(array_filter($allKrs, fn($k) => $k['status'] == 'Menunggu')),
            'disetujui' => count(array_filter($allKrs, fn($k) => $k['status'] == 'Disetujui')),
            'ditolak'   => count(array_filter($allKrs, fn($k) => $k['status'] == 'Ditolak')),
        ];

        return view('dosen_wali.krs-verifikasi', compact('stats', 'daftarKrs', 'filterStatus', 'filterKelas'));
    }

    public function approve($id)
    {
        return redirect()->back()->with('success', 'KRS berhasil disetujui');
    }

    public function reject($id)
    {
        return redirect()->back()->with('error', 'KRS berhasil ditolak');
    }
}
