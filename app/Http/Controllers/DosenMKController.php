<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DosenMKController extends Controller
{
    private function getTahunAjaranList() { return ['2025/2026', '2024/2025', '2023/2024']; }
    private function getSemesterList()    { return ['Ganjil', 'Genap']; }

    public function index()
    {
        $stats = ['mata_kuliah_diampu' => 3, 'total_mahasiswa' => 5, 'nilai_diinput' => 3, 'belum_dinilai' => 2];
        $mataKuliah = [
            ['kode' => 'IF101', 'nama' => 'Pemrograman Dasar',                'sks' => 3, 'semester' => 1, 'jadwal' => 'Senin, 07.00 - 08.40',    'ruang' => 'Lab Komputer 1', 'mahasiswa' => 2, 'kapasitas' => 40],
            ['kode' => 'IF102', 'nama' => 'Basis Data',                        'sks' => 3, 'semester' => 2, 'jadwal' => 'Selasa, 08.40 - 10.40',   'ruang' => 'Lab Komputer 2', 'mahasiswa' => 2, 'kapasitas' => 40],
            ['kode' => 'IF103', 'nama' => 'Pemrograman Berorientasi Objek',   'sks' => 3, 'semester' => 3, 'jadwal' => 'Jumat, 08.50 - 10.30',     'ruang' => 'Lab Komputer 1', 'mahasiswa' => 1, 'kapasitas' => 40],
        ];
        $mahasiswaTerbaru = [
            ['nama' => 'Reyhan',           'nim' => '3312501022', 'prodi' => 'Teknik Informatika', 'kelas' => 'A', 'rata_nilai' => 3.67],
            ['nama' => 'Nabila Fatin',     'nim' => '3312501007', 'prodi' => 'Teknik Informatika', 'kelas' => 'A', 'rata_nilai' => 3.50],
            ['nama' => 'Irenessa Rosidin', 'nim' => '3312501017', 'prodi' => 'Teknik Informatika', 'kelas' => 'A', 'rata_nilai' => 3.45],
        ];
        return view('pages.dosen_matkul.beranda', compact('stats', 'mataKuliah', 'mahasiswaTerbaru'));
    }

    public function inputNilai(Request $request)
    {
        $filterTahunAjaran = $request->input('tahun_ajaran', '2025/2026');
        $filterSemester    = $request->input('semester', 'Genap');

        $mataKuliahList = [
            ['kode' => 'IF101', 'nama' => 'Pemrograman Dasar',              'sks' => 3, 'semester' => 1, 'jumlah_mahasiswa' => 3],
            ['kode' => 'IF102', 'nama' => 'Basis Data',                      'sks' => 3, 'semester' => 2, 'jumlah_mahasiswa' => 2],
            ['kode' => 'IF103', 'nama' => 'Pemrograman Berorientasi Objek', 'sks' => 3, 'semester' => 3, 'jumlah_mahasiswa' => 1],
        ];

        $tahunAjaranList = $this->getTahunAjaranList();
        $semesterList    = $this->getSemesterList();

        return view('pages.dosen_matkul.input-nilai', compact(
            'mataKuliahList', 'filterTahunAjaran', 'filterSemester',
            'tahunAjaranList', 'semesterList'
        ));
    }

    public function simpanNilai(Request $request)
    {
        return redirect()->back()->with('success', 'Nilai berhasil disimpan');
    }

    public function lihatNilai(Request $request)
    {
        $filterMK          = $request->input('mata_kuliah', 'semua');
        $filterTahunAjaran = $request->input('tahun_ajaran', '2025/2026');
        $filterSemester    = $request->input('semester', 'Genap');

        $allMahasiswa = [
            ['no' => 1, 'nim' => '3312501017', 'nama' => 'Irenessa Rosidin', 'kelas' => 'A', 'mata_kuliah' => 'IF101 - Pemrograman Dasar',              'nilai' => 85.5, 'grade' => 'A'],
            ['no' => 2, 'nim' => '3312501007', 'nama' => 'Nabila Fatin',     'kelas' => 'A', 'mata_kuliah' => 'IF101 - Pemrograman Dasar',              'nilai' => 92.0, 'grade' => 'A'],
            ['no' => 3, 'nim' => '3312501022', 'nama' => 'Reyhan',           'kelas' => 'A', 'mata_kuliah' => 'IF102 - Basis Data',                      'nilai' => 78.3, 'grade' => 'B'],
            ['no' => 4, 'nim' => '3312501010', 'nama' => 'Della Reska',      'kelas' => 'A', 'mata_kuliah' => 'IF102 - Basis Data',                      'nilai' => 65.8, 'grade' => 'C'],
            ['no' => 5, 'nim' => '3312501023', 'nama' => 'Samuel Deidra',    'kelas' => 'A', 'mata_kuliah' => 'IF103 - Pemrograman Berorientasi Objek', 'nilai' => 88.2, 'grade' => 'A'],
        ];

        $mahasiswa = $filterMK !== 'semua'
            ? array_values(array_filter($allMahasiswa, fn($m) => $m['mata_kuliah'] === $filterMK))
            : $allMahasiswa;

        $stats = [
            'total_mahasiswa' => count($mahasiswa),
            'nilai_terinput'  => count(array_filter($mahasiswa, fn($m) => $m['nilai'] > 0)),
            'rata_nilai'      => count($mahasiswa) > 0
                ? number_format(array_sum(array_column($mahasiswa, 'nilai')) / count($mahasiswa), 2)
                : 0,
        ];

        $daftarMK        = ['IF101 - Pemrograman Dasar', 'IF102 - Basis Data', 'IF103 - Pemrograman Berorientasi Objek'];
        $tahunAjaranList = $this->getTahunAjaranList();
        $semesterList    = $this->getSemesterList();

        return view('pages.dosen_matkul.lihat-nilai', compact(
            'stats', 'mahasiswa', 'filterMK', 'daftarMK',
            'filterTahunAjaran', 'filterSemester',
            'tahunAjaranList', 'semesterList'
        ));
    }

    public function profil()
    {
        $user = session('user', []);
        $dosen = [
            'nama'          => $user['name']  ?? 'Dosen Mata Kuliah',
            'nidn'          => $user['nik']   ?? '-',
            'email'         => $user['email'] ?? 'dosen@univ.ac.id',
            'no_hp'         => '08123456789',
            'alamat'        => 'Kota Batam',
            'program_studi' => 'Teknik Informatika',
        ];
        return view('pages.dosen_matkul.profil', compact('dosen'));
    }

    public function update(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255', 'email' => 'required|email', 'no_hp' => 'required|string', 'alamat' => 'required|string']);
        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $request->validate(['password_lama' => 'required', 'password_baru' => ['required', 'min:6', 'confirmed']]);
        return redirect()->back()->with('success', 'Password berhasil diubah');
    }
}
