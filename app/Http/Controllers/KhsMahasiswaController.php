<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KhsMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $filterKelas = $request->input('kelas', 'semua');

        // Data dummy mahasiswa dengan KHS
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
            [
                'ranking' => 4,
                'nim' => '3312501010',
                'nama' => 'Delia Reska',
                'kelas' => 'B',
                'prodi' => 'Teknik Informatika',
                'mk_lulus' => 0,
                'ipk' => 0.00,
                'status_krs' => 'Belum KRS'
            ],
            [
                'ranking' => 5,
                'nim' => '3312501023',
                'nama' => 'Samuel Deidra',
                'kelas' => 'A',
                'prodi' => 'Teknik Informatika',
                'mk_lulus' => 0,
                'ipk' => 0.00,
                'status_krs' => 'Belum KRS'
            ],
        ];

        // Filter
        $mahasiswa = $allMahasiswa;
        if ($filterKelas != 'semua') {
            $mahasiswa = array_filter($mahasiswa, function($m) use ($filterKelas) {
                return $m['kelas'] == $filterKelas;
            });
            $mahasiswa = array_values($mahasiswa);
        }

        // Statistik
        $totalMahasiswa = count($allMahasiswa);
        $rataIpk = $totalMahasiswa > 0 ? 
            array_sum(array_column($allMahasiswa, 'ipk')) / $totalMahasiswa : 0;
        $ipkTinggi = count(array_filter($allMahasiswa, fn($m) => $m['ipk'] >= 3.5));

        return view('dosen_wali.khs_mahasiswa', compact(
            'mahasiswa',
            'filterKelas',
            'totalMahasiswa',
            'rataIpk',
            'ipkTinggi'
        ));
    }

    public function detail($nim)
    {
        // TODO: Ambil detail KHS mahasiswa dari database
        return redirect()->route('khs.index');
    }
}