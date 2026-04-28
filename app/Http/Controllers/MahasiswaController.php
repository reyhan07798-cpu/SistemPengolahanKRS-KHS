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

        return view('mahasiswa.beranda', compact('data'));
    }

    // PROFIL MAHASISWA
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

        return view('mahasiswa.profil', compact('data'));
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

        return redirect()->route('mahasiswa.profil')
            ->with('success', '✅ Profil berhasil diperbarui!');
    }

    public function ambilKrs(Request $request)
    {
        $mahasiswaId = Auth::id();
        $semesterAktif = 2; // TODO: ambil dari data mahasiswa aktif
        $tahunAjaranAktif = '2025/2026'; // TODO: ambil dari setting tahun ajaran aktif

        // ============================================================
        // 0. GENERATE OPSI FILTER TAHUN AJARAN + SEMESTER
        // ============================================================

        // Generate list tahun ajaran dari 2020 sampai 2030
        $opsiFilter = collect();
        for ($tahun = 2020; $tahun <= 2030; $tahun++) {
            $ta = "$tahun/" . ($tahun + 1);
            // Ganjil = semester 1, 3, 5, 7
            $opsiFilter->push([
                'label' => "$ta Ganjil",
                'tahun_ajaran' => $ta,
                'semester_gg' => 'ganjil',
                'semesters' => [1, 3, 5, 7],
                'value' => "$ta|ganjil",
            ]);
            // Genap = semester 2, 4, 6, 8
            $opsiFilter->push([
                'label' => "$ta Genap",
                'tahun_ajaran' => $ta,
                'semester_gg' => 'genap',
                'semesters' => [2, 4, 6, 8],
                'value' => "$ta|genap",
            ]);
        }

        // ============================================================
        // PARSE FILTER VALUE (format: "2025/2026|ganjil")
        // ============================================================
        $filterValue = $request->input('filter_tahun_ajaran', '');
        $filterTahunAjaran = '';
        $filterSemesterGanjilGenap = '';

        if (!empty($filterValue)) {
            $parts = explode('|', $filterValue);
            if (count($parts) >= 2) {
                $filterTahunAjaran = $parts[0];
                $filterSemesterGanjilGenap = $parts[1];
            }
        }

        // ============================================================
        // JIKA ADA FILTER -> TAMPILKAN HISTORIS KRS (VIEW ONLY)
        // ============================================================
        $modeHistoris = false;
        $historisKrs = collect();
        $historisPaket = collect();
        $historisRetake = collect();

        if ($filterTahunAjaran && $filterSemesterGanjilGenap) {
            $modeHistoris = true;
            $semesterList = $filterSemesterGanjilGenap === 'ganjil' ? [1,3,5,7] : [2,4,6,8];

            $historisKrs = KrsMahasiswa::where('mahasiswa_id', $mahasiswaId)
                ->where('tahun_ajaran', $filterTahunAjaran)
                ->whereIn('semester', $semesterList)
                ->with(['mataKuliah', 'nilai'])
                ->get();

            $historisPaket = $historisKrs->where('is_retake', false);
            $historisRetake = $historisKrs->where('is_retake', true);
        }

        // ============================================================
        // 1. AMBIL PAKET SEMESTER NORMAL (dummy data untuk demo)
        // ============================================================
        $paketSemester = [
            ['id' => 1, 'kode' => 'IF201', 'matkul' => 'Basis Data', 'dosen' => 'Dr. Budi Santoso, M.T', 'sks' => 4, 'status' => 'Disetujui'],
            ['id' => 2, 'kode' => 'IF202', 'matkul' => 'Pemrograman Web', 'dosen' => 'Dr. Budi Santoso, M.T', 'sks' => 4, 'status' => 'Disetujui'],
            ['id' => 3, 'kode' => 'IF203', 'matkul' => 'Jaringan Komputer', 'dosen' => 'Dr. Budi Santoso, M.T', 'sks' => 4, 'status' => 'Menunggu'],
            ['id' => 4, 'kode' => 'IF204', 'matkul' => 'Proyek Pembuatan Prototipe', 'dosen' => 'Dr. Budi Santoso, M.T', 'sks' => 4, 'status' => 'Disetujui'],
            ['id' => 5, 'kode' => 'IF205', 'matkul' => 'Pemrograman Berorientasi Objek', 'dosen' => 'Dr. Budi Santoso, M.T', 'sks' => 4, 'status' => 'Ditolak'],
        ];

        // ============================================================
        // 2. AMBIL DAFTAR MK PENGULANGAN (MK yang nilainya E)
        // ============================================================
        $mkPengulangan = getMkPengulangan($mahasiswaId);

        // Jika belum ada data nilai di database, tampilkan dummy pengulangan untuk demo
        // Hanya nilai E yang boleh diulang (D tidak boleh)
        if ($mkPengulangan->isEmpty()) {
            $mkPengulangan = collect([
                [
                    'mata_kuliah_id' => 99,
                    'kode_mk' => 'IF101',
                    'nama_mk' => 'Algoritma dan Pemrograman (Demo Pengulangan)',
                    'sks' => 3,
                    'nilai_lama' => 'E',
                    'bobot_lama' => 0.00,
                    'semester_lama' => 1,
                    'tahun_ajaran_lama' => '2024/2025',
                ],
                [
                    'mata_kuliah_id' => 98,
                    'kode_mk' => 'IF102',
                    'nama_mk' => 'Matematika Diskrit (Demo Pengulangan)',
                    'sks' => 3,
                    'nilai_lama' => 'E',
                    'bobot_lama' => 0.00,
                    'semester_lama' => 1,
                    'tahun_ajaran_lama' => '2024/2025',
                ],
            ]);
        }

        // ============================================================
        // 3. HITUNG TOTAL SKS (Paket + Pengulangan)
        // ============================================================
        $sksPaket = collect($paketSemester)->sum('sks');
        $sksPengulangan = $mkPengulangan->sum('sks');
        $totalSks = $sksPaket + $sksPengulangan;

        $data = [
            'nama' => 'Reyhan',
            'email' => 'reyhan@gmail.com',
            'semester_aktif' => $semesterAktif,
            'tahun_ajaran' => $tahunAjaranAktif,
            'total_sks' => $totalSks,
            'sks_paket' => $sksPaket,
            'sks_pengulangan' => $sksPengulangan,
            'status_krs' => 'Disetujui',
            'paket_semester' => $paketSemester,
            'mk_pengulangan' => $mkPengulangan,
            // Data untuk filter
            'opsi_filter' => $opsiFilter,
            'filter_tahun_ajaran' => $filterTahunAjaran,
            'filter_semester_gg' => $filterSemesterGanjilGenap,
            'mode_historis' => $modeHistoris,
            'historis_krs' => $historisKrs,
            'historis_paket' => $historisPaket,
            'historis_retake' => $historisRetake,
        ];

        return view('mahasiswa.ambil-krs', compact('data'));
    }

    public function storeKrs(Request $request)
    {
        $request->validate([
            'mata_kuliah_ids' => 'required|array',
            'mata_kuliah_ids.*' => 'exists:mata_kuliah,id',
            'mata_kuliah_retake_ids' => 'nullable|array',
            'mata_kuliah_retake_ids.*' => 'exists:mata_kuliah,id',
        ]);

        $mahasiswaId = Auth::id();
        $semesterAktif = $request->input('semester_aktif', 2);
        $tahunAjaran = $request->input('tahun_ajaran', '2025/2026');

        // ============================================================
        // VALIDASI MAKSIMAL 24 SKS
        // ============================================================
        $totalSks = 0;
        foreach ($request->mata_kuliah_ids as $mkId) {
            $mk = MataKuliah::find($mkId);
            if ($mk) $totalSks += $mk->sks;
        }
        if ($request->has('mata_kuliah_retake_ids')) {
            foreach ($request->mata_kuliah_retake_ids as $mkId) {
                $mk = MataKuliah::find($mkId);
                if ($mk) $totalSks += $mk->sks;
            }
        }

        if ($totalSks > 24) {
            return redirect()->back()
                ->with('error', '⚠️ Total SKS tidak boleh melebihi 24 SKS. SKS Anda saat ini: ' . $totalSks . ' SKS.')
                ->withInput();
        }

        // ============================================================
        // 1. HAPUS KRS SEMESTER AKTIF SAJA (jangan hapus historis)
        // ============================================================
        KrsMahasiswa::where('mahasiswa_id', $mahasiswaId)
            ->where('semester', $semesterAktif)
            ->where('tahun_ajaran', $tahunAjaran)
            ->delete();

        // ============================================================
        // 2. SIMPAN MK PAKET NORMAL
        // ============================================================
        foreach ($request->mata_kuliah_ids as $mataKuliahId) {
            KrsMahasiswa::create([
                'mahasiswa_id' => $mahasiswaId,
                'mata_kuliah_id' => $mataKuliahId,
                'status' => 'menunggu',
                'semester' => $semesterAktif,
                'tahun_ajaran' => $tahunAjaran,
                'is_retake' => false,
                'status_perkuliahan' => 'aktif',
            ]);
        }

        // ============================================================
        // 3. SIMPAN MK PENGULANGAN (RETAKE)
        // ============================================================
        if ($request->has('mata_kuliah_retake_ids')) {
            foreach ($request->mata_kuliah_retake_ids as $mataKuliahId) {
                // Validasi: hanya boleh mengulang jika nilai D/E
                if (cekBolehMengulang($mahasiswaId, $mataKuliahId)) {
                    KrsMahasiswa::create([
                        'mahasiswa_id' => $mahasiswaId,
                        'mata_kuliah_id' => $mataKuliahId,
                        'status' => 'menunggu',
                        'semester' => $semesterAktif,
                        'tahun_ajaran' => $tahunAjaran,
                        'is_retake' => true,
                        'status_perkuliahan' => 'aktif',
                    ]);
                }
            }
        }

        return redirect()->route('mahasiswa.ambil-krs')
            ->with('success', 'KRS berhasil diajukan dan menunggu persetujuan dosen wali.');
    }

    public function lihatKhs(Request $request)
    {
        $mahasiswaId = Auth::id();

        // ============================================================
        // 1. AMBIL SEMUA NILAI (termasuk yang diulang)
        // ============================================================
        $nilaiQuery = Nilai::where('mahasiswa_id', $mahasiswaId)
            ->with(['mataKuliah', 'krs'])
            ->orderBy('semester', 'asc')
            ->orderBy('tahun_ajaran', 'desc');

        if ($request->filled('tahun_ajaran')) {
            $nilaiQuery->where('tahun_ajaran', $request->tahun_ajaran);
        }

        if ($request->filled('semester')) {
            $nilaiQuery->where('semester', $request->semester);
        }

        $nilaiRecords = $nilaiQuery->get();

        // ============================================================
        // 2. HITUNG IPK DENGAN ATURAN RETAKE (nilai terbaik per MK)
        // ============================================================
        $ipkData = hitungIpkDenganRetake($mahasiswaId);
        $ipk = $ipkData['ipk'];
        $totalSks = $ipkData['total_sks'];
        $mataKuliahCount = $ipkData['total_mk'];

        // ============================================================
        // 3. FORMAT DATA UNTUK VIEW
        // ============================================================
        $nilai = $nilaiRecords->map(function ($item) {
            $history = getNilaiHistory($item->mahasiswa_id, $item->mata_kuliah_id);

            return [
                'kode_mk' => $item->mataKuliah->kode_mk ?? 'IF' . str_pad($item->mata_kuliah_id, 3, '0', STR_PAD_LEFT),
                'nama_mk' => $item->mataKuliah->nama ?? 'Mata Kuliah',
                'sks' => $item->sks,
                'nilai' => $item->nilai,
                'bobot' => $item->bobot,
                'tahun_ajaran' => $item->tahun_ajaran,
                'semester' => $item->semester,
                'is_retake' => $item->krs ? $item->krs->is_retake : false,
                'nilai_lama' => $history['history']->pluck('nilai')->toArray(),
                'color' => getNilaiColor($item->nilai),
            ];
        })->toArray();

        $data = [
            'nama' => 'Reyhan',
            'email' => 'reyhan@gmail.com',
        ];

        return view('mahasiswa.lihat-khs', compact('nilai', 'ipk', 'totalSks', 'mataKuliahCount', 'data'));
    }
}