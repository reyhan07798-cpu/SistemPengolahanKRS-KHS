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
            [
                'nama' => 'Reyhan',
                'nim' => '3312501022',
                'prodi' => 'Teknik Informatika',
                'kelas' => 'A',
                'ipk' => 3.60,
                'status_krs' => 'Disetujui'
            ],
            [
                'nama' => 'Nabila Fatin',
                'nim' => '3312501007',
                'prodi' => 'Teknik Informatika',
                'kelas' => 'A',
                'ipk' => 3.60,
                'status_krs' => 'Disetujui'
            ],
            [
                'nama' => 'Irenessa Rosidin',
                'nim' => '3312501017',
                'prodi' => 'Teknik Informatika',
                'kelas' => 'A',
                'ipk' => 3.60,
                'status_krs' => 'Belum KRS'
            ],
        ];

        return view('pages.dosen_wali.beranda', compact('stats', 'mahasiswa'));
    }

    // ✅ METHOD KHUSUS UNTUK KHS DOSEN WALI
    public function khs(Request $request)
    {
        $filterKelas = $request->input('kelas', 'semua');

        // Data dummy mahasiswa bimbingan
        $allMahasiswa = [
            [
                'ranking' => 1,
                'nim' => '3312501017',
                'nama' => 'Irenessa Rosidin',
                'kelas' => 'A',
                'prodi' => 'Teknik Informatika',
                'mk_lulus' => 3,
                'ipk' => 3.86,
                'status_krs' => 'Aktif'
            ],
            [
                'ranking' => 2,
                'nim' => '3312501007',
                'nama' => 'Nabila Fatin',
                'kelas' => 'A',
                'prodi' => 'Teknik Informatika',
                'mk_lulus' => 5,
                'ipk' => 3.92,
                'status_krs' => 'Aktif'
            ],
            [
                'ranking' => 3,
                'nim' => '3312501022',
                'nama' => 'Reyhan',
                'kelas' => 'A',
                'prodi' => 'Teknik Informatika',
                'mk_lulus' => 4,
                'ipk' => 3.85,
                'status_krs' => 'Aktif'
            ],
        ];

        // Filter berdasarkan kelas
        $mahasiswa = $allMahasiswa;
        if ($filterKelas != 'semua') {
            $mahasiswa = array_filter($mahasiswa, function($m) use ($filterKelas) {
                return $m['kelas'] == $filterKelas;
            });
            $mahasiswa = array_values($mahasiswa); // Re-index array
        }

        // Statistik
        $totalMahasiswa = count($allMahasiswa);
        $rataIpk = $totalMahasiswa > 0 ? 
            array_sum(array_column($allMahasiswa, 'ipk')) / $totalMahasiswa : 0;
        $ipkTinggi = count(array_filter($allMahasiswa, fn($m) => $m['ipk'] >= 3.5));

        return view('pages.dosen_wali.khs', compact(
            'mahasiswa',
            'filterKelas',
            'totalMahasiswa',
            'rataIpk',
            'ipkTinggi'
        ));
    }
}