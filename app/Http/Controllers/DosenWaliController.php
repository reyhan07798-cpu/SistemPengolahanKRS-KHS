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

        return view('dosen_wali.beranda', compact('stats', 'mahasiswa'));
    }
    public function khs()
    {
        // Data dummy mahasiswa bimbingan (nanti bisa diganti dengan query database)
        $mahasiswa = [
            [
                'nama' => 'Reyhan',
                'nim' => '3312501022',
                'prodi' => 'Teknik Informatika',
                'khs' => [
                    ['matkul' => 'Pemrograman Web', 'sks' => 3, 'nilai' => 'A'],
                    ['matkul' => 'Basis Data', 'sks' => 3, 'nilai' => 'B+'],
                ]
            ],
            // ... tambah data lainnya
        ];

        return view('dosen_wali.khs', compact('mahasiswa'));
    }
    
}
