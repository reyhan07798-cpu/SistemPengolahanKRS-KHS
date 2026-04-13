<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $data = [
            'total_mahasiswa' => 5,
            'total_dosen' => 5,
            'total_matkul' => 10,
            'rata_ipk' => 3.60,
            'mahasiswa' => [
                [
                    'rank' => 1,
                    'nim' => '3312501017',
                    'nama' => 'Irenessa Rosidin',
                    'kelas' => 'A',
                    'prodi' => 'Teknik Informatika',
                    'angkatan' => 2025,
                    'ipk' => '3.96',
                    'predikat' => 'Cumlaude'
                ],
                [
                    'rank' => 2,
                    'nim' => '3312501007',
                    'nama' => 'Nabila Fatin',
                    'kelas' => 'B',
                    'prodi' => 'Teknologi Rekayasa Perangkat Lunak',
                    'angkatan' => 2025,
                    'ipk' => '3.92',
                    'predikat' => 'Sangat Baik'
                ],
                [
                    'rank' => 3,
                    'nim' => '3312501022',
                    'nama' => 'Reyhan',
                    'kelas' => 'A',
                    'prodi' => 'Teknik Informatika',
                    'angkatan' => 2025,
                    'ipk' => '3.85',
                    'predikat' => 'Sangat Baik'
                ],
                [
                    'rank' => 4,
                    'nim' => '3312501010',
                    'nama' => 'Della Reska',
                    'kelas' => 'B',
                    'prodi' => 'Teknologi Rekayasa Perangkat Lunak',
                    'angkatan' => 2025,
                    'ipk' => '-',
                    'predikat' => '-'
                ],
                [
                    'rank' => 5,
                    'nim' => '3312501023',
                    'nama' => 'Samuel Deidra',
                    'kelas' => 'A',
                    'prodi' => 'Teknik Informatika',
                    'angkatan' => 2025,
                    'ipk' => '-',
                    'predikat' => '-'
                ],
            ]
        ];
        
        return view('admin.dashboard', compact('data'));
    }
}