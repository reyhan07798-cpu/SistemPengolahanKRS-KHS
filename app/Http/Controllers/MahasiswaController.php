<?php

namespace App\Http\Controllers;

use App\Models\KrsMahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\Semester; // ← Pastikan model ini sudah ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    /**
     * Halaman Beranda Mahasiswa
     */
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

    /**
     * Halaman Profil Mahasiswa
     */
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

    /**
     * Update Profil Mahasiswa
     */
    public function updateProfil(Request $request)
    {
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

    /**
     * Halaman Ambil KRS
     */
    public function ambilKrs()
    {
        // Get active semester (fallback ke dummy jika model belum siap)
        $semesterAktif = class_exists(Semester::class) 
            ? Semester::where('is_active', true)->first() 
            : null;
        
        $data = [
            'nama' => 'Reyhan',
            'email' => 'reyhan@gmail.com', 
            'semester_aktif' => 2,
            'total_sks' => 0,
            'status_krs' => 'Belum Diajukan',
            'max_sks' => 24,
            'tahun_ajaran_aktif' => $semesterAktif?->tahun_ajaran ?? '2025/2026',
        ];

        return view('pages.mahasiswa.ambil-krs', compact('data'));
    }

    /**
     * API: Load Paket Semester berdasarkan filter
     * Digunakan oleh JavaScript di halaman ambil-krs
     */
    public function getPaketSemester(Request $request)
    {
        $semester = $request->input('semester', 2);
        $tahunAjaran = $request->input('tahun_ajaran', '2025/2026');
        
        // ========================================
        // DATA DUMMY PAKET SEMESTER (untuk frontend testing)
        // Nanti bisa diganti query ke database: MataKuliah::where(...)
        // ========================================
        $paketWajib = [
            1 => [
                ['id' => 1, 'kode' => 'IF101', 'matkul' => 'Algoritma & Pemrograman', 'dosen' => 'Dr. A', 'sks' => 4, 'prasyarat' => null],
                ['id' => 2, 'kode' => 'IF102', 'matkul' => 'Matematika Diskrit', 'dosen' => 'Dr. B', 'sks' => 3, 'prasyarat' => null],
                ['id' => 3, 'kode' => 'IF103', 'matkul' => 'Bahasa Indonesia', 'dosen' => 'Dr. C', 'sks' => 2, 'prasyarat' => null],
            ],
            2 => [
                ['id' => 4, 'kode' => 'IF201', 'matkul' => 'Basis Data', 'dosen' => 'Dr. Budi Santoso, M.T', 'sks' => 4, 'prasyarat' => 'IF101'],
                ['id' => 5, 'kode' => 'IF202', 'matkul' => 'Pemrograman Web', 'dosen' => 'Dr. Budi Santoso, M.T', 'sks' => 4, 'prasyarat' => 'IF101'],
                ['id' => 6, 'kode' => 'IF203', 'matkul' => 'Jaringan Komputer', 'dosen' => 'Dr. Budi Santoso, M.T', 'sks' => 4, 'prasyarat' => 'IF102'],
                ['id' => 7, 'kode' => 'IF204', 'matkul' => 'Proyek Pembuatan Prototipe', 'dosen' => 'Dr. Budi Santoso, M.T', 'sks' => 4, 'prasyarat' => null],
            ],
            3 => [
                ['id' => 8, 'kode' => 'IF301', 'matkul' => 'Pemrograman Berorientasi Objek', 'dosen' => 'Dr. D', 'sks' => 4, 'prasyarat' => 'IF202'],
                ['id' => 9, 'kode' => 'IF302', 'matkul' => 'Rekayasa Perangkat Lunak', 'dosen' => 'Dr. E', 'sks' => 4, 'prasyarat' => 'IF201'],
            ],
        ];
        
        // Data dummy nilai yang sudah lulus (untuk filter MK mengulang)
        $nilaiLulus = [
            'IF101' => ['nilai' => 'A', 'bobot' => 4.0],
            'IF102' => ['nilai' => 'B', 'bobot' => 3.0],
            'IF103' => ['nilai' => 'C', 'bobot' => 2.0], // Bisa diulang jika nilai < C
            'IF201' => ['nilai' => 'D', 'bobot' => 1.0], // Contoh MK yang bisa diulang
        ];
        
        // Filter MK wajib untuk semester yang dipilih
        $mkWajib = $paketWajib[$semester] ?? [];
        
        // Filter MK yang bisa diulang (yang sudah diambil tapi nilai < C)
        $mkMengulang = [];
        foreach ($nilaiLulus as $kode => $data) {
            if ($data['bobot'] < 2.0) { // Nilai D atau E bisa diulang
                foreach ($paketWajib as $sem => $mks) {
                    foreach ($mks as $mk) {
                        if ($mk['kode'] === $kode) {
                            $mkMengulang[] = [
                                ...$mk,
                                'isMengulang' => true,
                                'nilaiLama' => $data['nilai'],
                            ];
                            break;
                        }
                    }
                }
            }
        }
        
        return response()->json([
            'semester' => $semester,
            'tahun_ajaran' => $tahunAjaran,
            'paket_semester' => [
                'wajib' => $mkWajib,
                'mengulang' => $mkMengulang,
            ],
            'max_sks' => 24,
        ]);
    }

    /**
     * Simpan KRS Mahasiswa
     */
    public function storeKrs(Request $request)
    {
        $request->validate([
            'mata_kuliah_ids' => 'required|array',
            'mata_kuliah_ids.*' => 'numeric|exists:mata_kuliah,id',
            'semester' => 'required|integer',
            'tahun_ajaran' => 'required|string',
        ]);

        $mahasiswaId = Auth::id();

        // Hapus KRS lama mahasiswa ini (opsional, tergantung business rule)
        KrsMahasiswa::where('mahasiswa_id', $mahasiswaId)
            ->where('status', 'menunggu')
            ->delete();

        // Simpan KRS baru
        foreach ($request->mata_kuliah_ids as $mataKuliahId) {
            KrsMahasiswa::create([
                'mahasiswa_id' => $mahasiswaId,
                'mata_kuliah_id' => $mataKuliahId,
                'semester_id' => $request->semester, // jika ada relasi semester
                'tahun_ajaran' => $request->tahun_ajaran,
                'status' => 'menunggu',
            ]);
        }

        return redirect()->route('pages.mahasiswa.ambil-krs')
            ->with('success', '✅ KRS berhasil diajukan dan menunggu persetujuan dosen wali.');
    }

    /**
     * Halaman Lihat KHS
     */
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