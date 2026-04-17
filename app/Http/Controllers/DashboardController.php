<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'mahasiswa_bimbingan' => 3,
            'krs_menunggu'        => 0,
            'krs_disetujui'       => 2,
            'krs_ditolak'         => 0,
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

        return view('dosen_wali.dashboard', compact('stats', 'mahasiswa'));
    }
}