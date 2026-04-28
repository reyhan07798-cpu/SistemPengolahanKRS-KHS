<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DosenMKController extends Controller
{
    public function index()
    {
        $stats = [
            'mata_kuliah_diampu' => 3,
            'total_mahasiswa'    => 40,
            'nilai_diinput'      => 3,
            'belum_dinilai'      => 0,
        ];

        $mataKuliah = [
            [
                'kode' => 'IF101',
                'nama' => 'Pemrograman Dasar',
                'sks' => 3,
                'semester' => 1,
                'jadwal' => 'Senin, 07.00 - 08.40',
                'ruang' => 'Lab Komputer 1',
                'mahasiswa' => 2,
                'kapasitas' => 40,
            ],
            [
                'kode' => 'IF102',
                'nama' => 'Basis Data',
                'sks' => 3,
                'semester' => 2,
                'jadwal' => 'Selasa, 08.40 - 10.40',
                'ruang' => 'Lab Komputer 2',
                'mahasiswa' => 2,
                'kapasitas' => 40,
            ],
            [
                'kode' => 'IF103',
                'nama' => 'Pemrograman Berorientasi Objek',
                'sks' => 3,
                'semester' => 3,
                'jadwal' => 'Jumat, 08.50 - 10.30',
                'ruang' => 'Lab Komputer 1',
                'mahasiswa' => 1,
                'kapasitas' => 40,
            ],
        ];

        $mahasiswaTerbaru = [
            [
                'nama' => 'Reyhan',
                'nim' => '3312501022',
                'prodi' => 'Teknik Informatika',
                'kelas' => 'A',
                'rata_nilai' => 3.67,
            ],
            [
                'nama' => 'Nabila Fatin',
                'nim' => '3312501007',
                'prodi' => 'Teknik Informatika',
                'kelas' => 'A',
                'rata_nilai' => 3.50,
            ],
            [
                'nama' => 'Irenessa Rosidin',
                'nim' => '3312501017',
                'prodi' => 'Teknik Informatika',
                'kelas' => 'A',
                'rata_nilai' => 3.45,
            ],
        ];

        return view('pages.dosen_matkul.beranda', compact('stats', 'mataKuliah', 'mahasiswaTerbaru'));
    }

    public function inputNilai()
    {
        $mataKuliahList = [
            [
                'kode' => 'IF101',
                'nama' => 'Pemrograman Dasar',
                'sks' => 3,
                'semester' => 1,
                'jumlah_mahasiswa' => 5,
            ],
            [
                'kode' => 'IF102',
                'nama' => 'Basis Data',
                'sks' => 3,
                'semester' => 2,
                'jumlah_mahasiswa' => 4,
            ],
            [
                'kode' => 'IF103',
                'nama' => 'Pemrograman Berorientasi Objek',
                'sks' => 3,
                'semester' => 3,
                'jumlah_mahasiswa' => 3,
            ],
            [
                'kode' => 'IF104',
                'nama' => 'Rekayasa Perangkat Lunak',
                'sks' => 3,
                'semester' => 4,
                'jumlah_mahasiswa' => 2,
            ],
        ];

        return view('pages.dosen_matkul.input-nilai', compact('mataKuliahList'));
    }

    public function lihatNilai(Request $request)
    {
        $filterMK = $request->input('mata_kuliah', 'semua');

        $allMahasiswa = [
            [
                'no' => 1,
                'nim' => '3312501017',
                'nama' => 'Irenessa Rosidin',
                'kelas' => 'A',
                'mata_kuliah' => 'IF101 - Pemrograman Dasar',
                'nilai' => 85.5,
                'grade' => 'B',
            ],
            [
                'no' => 2,
                'nim' => '3312501007',
                'nama' => 'Nabila Fatin',
                'kelas' => 'A',
                'mata_kuliah' => 'IF101 - Pemrograman Dasar',
                'nilai' => 92.0,
                'grade' => 'A',
            ],
            [
                'no' => 3,
                'nim' => '3312501022',
                'nama' => 'Reyhan',
                'kelas' => 'A',
                'mata_kuliah' => 'IF102 - Basis Data',
                'nilai' => 78.3,
                'grade' => 'B',
            ],
            [
                'no' => 4,
                'nim' => '3312501010',
                'nama' => 'Della Reska',
                'kelas' => 'A',
                'mata_kuliah' => 'IF102 - Basis Data',
                'nilai' => 65.8,
                'grade' => 'C',
            ],
            [
                'no' => 5,
                'nim' => '3312501023',
                'nama' => 'Samuel Deidra',
                'kelas' => 'A',
                'mata_kuliah' => 'IF103 - Pemrograman Berorientasi Objek',
                'nilai' => 88.2,
                'grade' => 'B',
            ],
        ];

        $mahasiswa = $allMahasiswa;
        if ($filterMK != 'semua') {
            $mahasiswa = array_filter($mahasiswa, function($m) use ($filterMK) {
                return $m['mata_kuliah'] == $filterMK;
            });
            $mahasiswa = array_values($mahasiswa);
        }

        $stats = [
            'total_mahasiswa' => count($mahasiswa),
            'nilai_terinput' => count(array_filter($mahasiswa, fn($m) => $m['nilai'] > 0)),
            'rata_nilai' => count($mahasiswa) > 0 
                ? number_format(array_sum(array_column($mahasiswa, 'nilai')) / count($mahasiswa), 2) 
                : 0,
        ];

        $daftarMK = [
            'IF101 - Pemrograman Dasar',
            'IF102 - Basis Data',
            'IF103 - Pemrograman Berorientasi Objek',
            'IF104 - Rekayasa Perangkat Lunak',
        ];

        return view('pages.dosen_matkul.lihat-nilai', compact('stats', 'mahasiswa', 'filterMK', 'daftarMK'));
    }

    public function profil()
    {
        $dosen = [
            'nama' => 'Cyntia Lasmi Andesti, S.Kom., M.Kom',
            'nidn' => '124307',
            'email' => 'MKI@univ.ac.id',
            'no_hp' => '08123456789',
            'alamat' => 'Kota Batam',
            'program_studi' => 'Teknik Informatika',
        ];

        return view('pages.dosen_matkul.profil', compact('dosen'));
    }
        public function update(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'no_hp' => 'required|string',
            'alamat' => 'required|string',
        ]);



        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'password_lama' => 'required',
            'password_baru' => ['required', 'min:8', 'confirmed'],
        ]);



        return redirect()->back()->with('success', 'Password berhasil diubah');
    }
        private function hitungNilaiAkhir($item)
    {
        $tugas = $item['tugas'] ?? 0;
        $praktikum = $item['praktikum'] ?? 0;
        $uts = $item['uts'] ?? 0;
        $uas = $item['uas'] ?? 0;
        $kehadiran = $item['kehadiran'] ?? 0;

        return ($tugas * 0.20) + 
               ($praktikum * 0.15) + 
               ($uts * 0.30) + 
               ($uas * 0.30) + 
               ($kehadiran * 0.05);
    }

    private function konversiGrade($nilai)
    {
        if ($nilai >= 85) return 'A';      
        if ($nilai >= 70) return 'B';      
        if ($nilai >= 55) return 'C';      
        if ($nilai >= 40) return 'D';      
        return 'E';                         
    }
}
