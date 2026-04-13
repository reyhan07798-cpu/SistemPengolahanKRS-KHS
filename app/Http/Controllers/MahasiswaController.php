<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        $data = [
            'nama' => 'Reyhan',
            'nim' => '3312501022',
            'prodi' => 'Teknik Informatika',
            'angkatan' => 2025,
            'email' => 'reyhan@gmail.com',
            'semester_aktif' => 2,
            'total_sks' => 15,
            'ipk' => 3.64,
            'mata_kuliah_lulus' => 7,
            'nilai_terbaru' => [
                ['matkul' => 'Basis Data', 'sks' => 3, 'nilai' => 'A', 'bobot' => 3.94],
                ['matkul' => 'Pemrograman Web', 'sks' => 3, 'nilai' => 'B', 'bobot' => 3.24],
                ['matkul' => 'Jaringan Komputer', 'sks' => 3, 'nilai' => 'A-', 'bobot' => 3.64],
                ['matkul' => 'Proyek Pembuatan Prototipe', 'sks' => 3, 'nilai' => 'B+', 'bobot' => 3.44],
                ['matkul' => 'Pemrograman Berorientasi Objek', 'sks' => 3, 'nilai' => 'A', 'bobot' => 3.94],
            ],
            'krs_aktif' => [
                ['kode' => 'IF201', 'matkul' => 'Basis Data', 'sks' => 3, 'status' => 'Disetujui'],
                ['kode' => 'IF202', 'matkul' => 'Pemrograman Web', 'sks' => 3, 'status' => 'Disetujui'],
                ['kode' => 'IF203', 'matkul' => 'Jaringan Komputer', 'sks' => 3, 'status' => 'Ditolak'],
                ['kode' => 'IF204', 'matkul' => 'Proyek Pembuatan Prototipe', 'sks' => 3, 'status' => 'Disetujui'],
                ['kode' => 'IF205', 'matkul' => 'Pemrograman Berorientasi Objek', 'sks' => 3, 'status' => 'Ditolak'],
            ]
        ];
        
        return view('mahasiswa.dashboard', compact('data'));
    }
}