<?php

namespace App\Http\Controllers;

use App\Models\KrsMahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return view('pages.mahasiswa.beranda', compact('data'));
    }

    // =========================
    // ✅ PROFIL MAHASISWA
    // =========================
    public function profil()
    {
        $data = [
            'nama' => 'Reyhan',
            'nim' => '3312501022',
            'email' => 'reyhan@gmail.com', 
            'no_hp' => '08123456789',
            'alamat' => 'Kota Batam',
            'program_studi' => 'Teknik Informatika'
        ];

        return view('pages.mahasiswa.profil', compact('data'));
    }
    public function updateProfil(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:mahasiswas,nim,' . Auth::id(),
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:500',
            'program_studi' => 'required|string|max:100',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $validated['nama'],
            'email' => $validated['email'],
        ]);

        if ($user->mahasiswa) {
            $user->mahasiswa->update([
                'nim' => $validated['nim'],
                'no_hp' => $validated['no_hp'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'program_studi' => $validated['program_studi'],
            ]);
        }

        return redirect()->route('pages.mahasiswa.profil')
            ->with('success', '✅ Profil berhasil diperbarui!');
    }

    public function ambilKrs()
    {
        $data = [
            'nama' => 'Reyhan',
            'email' => 'reyhan@gmail.com', 
            'semester_aktif' => 2,
            'total_sks' => 20,
            'status_krs' => 'Disetujui',
            'paket_semester' => [
                ['id' => 1, 'kode' => 'IF201', 'matkul' => 'Basis Data', 'dosen' => 'Dr. Budi Santoso, M.T', 'sks' => 4, 'status' => 'Disetujui'],
                ['id' => 2, 'kode' => 'IF202', 'matkul' => 'Pemrograman Web', 'dosen' => 'Dr. Budi Santoso, M.T', 'sks' => 4, 'status' => 'Disetujui'],
                ['id' => 3, 'kode' => 'IF203', 'matkul' => 'Jaringan Komputer', 'dosen' => 'Dr. Budi Santoso, M.T', 'sks' => 4, 'status' => 'Menunggu'],
                ['id' => 4, 'kode' => 'IF204', 'matkul' => 'Proyek Pembuatan Prototipe', 'dosen' => 'Dr. Budi Santoso, M.T', 'sks' => 4, 'status' => 'Disetujui'],
                ['id' => 5, 'kode' => 'IF205', 'matkul' => 'Pemrograman Berorientasi Objek', 'dosen' => 'Dr. Budi Santoso, M.T', 'sks' => 4, 'status' => 'Ditolak'],
            ],
        ];

        return view('pages.mahasiswa.ambil-krs', compact('data'));
    }

    public function storeKrs(Request $request)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|array',
            'mata_kuliah_id.*' => 'exists:mata_kuliah,id',
        ]);

        $mahasiswaId = Auth::id();

        KrsMahasiswa::where('mahasiswa_id', $mahasiswaId)->delete();

        foreach ($request->mata_kuliah_id as $mataKuliahId) {
            KrsMahasiswa::create([
                'mahasiswa_id' => $mahasiswaId,
                'mata_kuliah_id' => $mataKuliahId,
                'status' => 'menunggu',
            ]);
        }

        return redirect()->route('pages.mahasiswa.ambil-krs')
            ->with('success', 'KRS berhasil diajukan dan menunggu persetujuan dosen wali.');
    }

    public function lihatKhs(Request $request)
    {
        $mahasiswaId = Auth::id();

        $nilaiQuery = Nilai::where('mahasiswa_id', $mahasiswaId)
            ->with('mataKuliah')
            ->orderBy('semester', 'asc')
            ->orderBy('tahun_ajaran', 'desc');

        if ($request->filled('tahun_ajaran')) {
            $nilaiQuery->where('tahun_ajaran', $request->tahun_ajaran);
        }

        if ($request->filled('semester')) {
            $nilaiQuery->where('semester', $request->semester);
        }

        $nilaiRecords = $nilaiQuery->get();

        $ipk = $nilaiRecords->isEmpty() ? 0 : round($nilaiRecords->avg('bobot'), 2);
        $totalSks = $nilaiRecords->sum('sks');
        $mataKuliahCount = $nilaiRecords->count();

        $nilai = $nilaiRecords->map(function ($item) {
            return [
                'kode_mk' => $item->mataKuliah->kode_mk ?? 'IF' . str_pad($item->mata_kuliah_id, 3, '0', STR_PAD_LEFT),
                'nama_mk' => $item->mataKuliah->nama ?? 'Mata Kuliah',
                'sks' => $item->sks,
                'nilai' => $item->nilai,
                'bobot' => $item->bobot,
                'tahun_ajaran' => $item->tahun_ajaran,
                'semester' => $item->semester,
            ];
        })->toArray();

        $data = [
            'nama' => 'Reyhan',
            'email' => 'reyhan@gmail.com', 
        ];

        return view('pages.mahasiswa.lihat-khs', compact('nilai', 'ipk', 'totalSks', 'mataKuliahCount', 'data'));
    }
}