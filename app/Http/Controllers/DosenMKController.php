<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DosenMKController extends Controller
{
    // ──────────────────────────────────────────────────────────────
    //  Helper: ambil nik dosen dari session
    // ──────────────────────────────────────────────────────────────
    private function dosenNik(): string
    {
        $user = session('user', []);
        return $user['nik'] ?? '';
    }

    // ──────────────────────────────────────────────────────────────
    //  Helper: daftar mata kuliah yang diampu dosen ini
    //  Berdasarkan kolom dosen_nik di tabel mata_kuliah
    // ──────────────────────────────────────────────────────────────
    private function getMataKuliahDiampu(): array
    {
        $nik = $this->dosenNik();
        if (!$nik) return [];

        $rows = DB::table('mata_kuliah')
            ->where('dosen_nik', $nik)
            ->select('id','kode_mk','nama','sks','semester_ke','kelas','tahun_ajaran','semester_id')
            ->get();

        return $rows->toArray();
    }

    // ──────────────────────────────────────────────────────────────
    //  Konversi nilai 0-100 ke grade & mutu
    // ──────────────────────────────────────────────────────────────
    public static function konversiNilai(float $nilai): array
    {
        if ($nilai >= 85) return ['grade' => 'A',  'mutu' => 4.00];
        if ($nilai >= 80) return ['grade' => 'A-', 'mutu' => 3.75];
        if ($nilai >= 75) return ['grade' => 'B+', 'mutu' => 3.50];
        if ($nilai >= 70) return ['grade' => 'B',  'mutu' => 3.00];
        if ($nilai >= 65) return ['grade' => 'B-', 'mutu' => 2.75];
        if ($nilai >= 60) return ['grade' => 'C+', 'mutu' => 2.50];
        if ($nilai >= 55) return ['grade' => 'C',  'mutu' => 2.00];
        if ($nilai >= 40) return ['grade' => 'D',  'mutu' => 1.00];
        return ['grade' => 'E', 'mutu' => 0.00];
    }

    // ──────────────────────────────────────────────────────────────
    //  BERANDA
    // ──────────────────────────────────────────────────────────────
    public function index()
    {
        $nik = $this->dosenNik();
        $mataKuliahDiampu = $this->getMataKuliahDiampu();

        $totalMhs = 0;
        $nilaiDiinput = 0;
        $belumDinilai = 0;

        foreach ($mataKuliahDiampu as $mk) {
            $mahasiswaInMK = DB::table('krs_mahasiswa')
                ->where('mata_kuliah_id', $mk->id)
                ->where('status', 'disetujui')
                ->count();
            $sudahDinilai = DB::table('nilai')
                ->where('mata_kuliah_id', $mk->id)
                ->where('dosen_nik', $nik)
                ->count();
            $totalMhs    += $mahasiswaInMK;
            $nilaiDiinput += $sudahDinilai;
            $belumDinilai += max(0, $mahasiswaInMK - $sudahDinilai);
        }

        $stats = [
            'mata_kuliah_diampu' => count($mataKuliahDiampu),
            'total_mahasiswa'    => $totalMhs,
            'nilai_diinput'      => $nilaiDiinput,
            'belum_dinilai'      => $belumDinilai,
        ];

        // Mahasiswa terbaru (yang ada nilainya dari dosen ini)
        $mahasiswaTerbaru = DB::table('nilai')
            ->join('users', 'nilai.mahasiswa_id', '=', 'users.id')
            ->where('nilai.dosen_nik', $nik)
            ->select('users.name as nama', 'users.email', 'nilai.mahasiswa_id as nim',
                     DB::raw('AVG(nilai.bobot) as rata_nilai'))
            ->groupBy('nilai.mahasiswa_id', 'users.name', 'users.email')
            ->orderByDesc('rata_nilai')
            ->limit(5)
            ->get()
            ->map(function ($m) {
                return [
                    'nama'       => $m->nama,
                    'nim'        => $m->nim,
                    'prodi'      => 'Teknik Informatika',
                    'kelas'      => 'A',
                    'rata_nilai' => round((float)$m->rata_nilai, 2),
                ];
            })->toArray();

        // Format mata kuliah untuk tampilan (tanpa jadwal)
        $mataKuliah = array_map(function ($mk) {
            $jml = DB::table('krs_mahasiswa')
                ->where('mata_kuliah_id', $mk->id)
                ->where('status', 'disetujui')
                ->count();
            return [
                'kode'      => $mk->kode_mk,
                'nama'      => $mk->nama,
                'sks'       => $mk->sks,
                'semester'  => $mk->semester_ke ?? '-',
                'kelas'     => $mk->kelas ?? 'A',
                'mahasiswa' => $jml,
                'kapasitas' => 40,
            ];
        }, $mataKuliahDiampu);

        return view('pages.dosen_matkul.beranda', compact('stats', 'mataKuliah', 'mahasiswaTerbaru'));
    }

    // ──────────────────────────────────────────────────────────────
    //  INPUT NILAI — tampilkan halaman
    // ──────────────────────────────────────────────────────────────
    public function inputNilai(Request $request)
    {
        $nik = $this->dosenNik();

        // Filter
        $filterTahunAjaran = $request->input('tahun_ajaran', '');
        $filterSemester    = $request->input('semester', '');
        $filterMK          = $request->input('mata_kuliah_id', '');
        $filterKelas       = $request->input('kelas', '');

        // Daftar tahun ajaran & semester dari DB
        $tahunAjaranList = DB::table('mata_kuliah')
            ->where('dosen_nik', $nik)
            ->whereNotNull('tahun_ajaran')
            ->distinct()->pluck('tahun_ajaran')->toArray();
        if (empty($tahunAjaranList)) $tahunAjaranList = ['2025/2026'];

        $semesterList = ['Ganjil', 'Genap'];

        // Daftar mata kuliah yang diampu
        $mataKuliahList = DB::table('mata_kuliah')
            ->where('dosen_nik', $nik)
            ->get(['id','kode_mk','nama','sks','semester_ke','kelas','tahun_ajaran'])
            ->map(function ($mk) {
                $jml = DB::table('krs_mahasiswa')
                    ->where('mata_kuliah_id', $mk->id)
                    ->where('status', 'disetujui')
                    ->count();
                return [
                    'id'                => $mk->id,
                    'kode'              => $mk->kode_mk,
                    'nama'              => $mk->nama,
                    'sks'               => $mk->sks,
                    'semester'          => $mk->semester_ke,
                    'kelas'             => $mk->kelas ?? 'A',
                    'tahun_ajaran'      => $mk->tahun_ajaran,
                    'jumlah_mahasiswa'  => $jml,
                ];
            })->toArray();

        // Daftar kelas unik
        $kelasList = collect($mataKuliahList)->pluck('kelas')->unique()->sort()->values()->toArray();

        // Jika ada filter MK aktif, ambil data mahasiswa + nilai existing
        $mahasiswaList = [];
        $bobotAktif    = null;
        $mkAktif       = null;

        if ($filterMK) {
            $mk = DB::table('mata_kuliah')->where('id', $filterMK)->first();
            if ($mk) {
                $mkAktif = $mk;
                // Ambil bobot dari nilai pertama yang ada (bisa variabel per MK)
                $nilaiContoh = DB::table('nilai')
                    ->where('mata_kuliah_id', $filterMK)
                    ->first();
                $bobotAktif = [
                    'tugas'      => $nilaiContoh->bobot_tugas      ?? 20,
                    'praktikum'  => $nilaiContoh->bobot_praktikum  ?? 15,
                    'uts'        => $nilaiContoh->bobot_uts         ?? 30,
                    'uas'        => $nilaiContoh->bobot_uas         ?? 30,
                    'kehadiran'  => $nilaiContoh->bobot_kehadiran   ?? 5,
                ];

                // Mahasiswa yang ambil MK ini & disetujui
                $krsQuery = DB::table('krs_mahasiswa')
                    ->join('users', 'krs_mahasiswa.mahasiswa_id', '=', 'users.id')
                    ->where('krs_mahasiswa.mata_kuliah_id', $filterMK)
                    ->where('krs_mahasiswa.status', 'disetujui');

                if ($filterKelas) {
                    $krsQuery->where('krs_mahasiswa.kelas', $filterKelas);
                }

                $mahasiswaRows = $krsQuery
                    ->select('users.id', 'users.name as nama', 'users.email',
                             'krs_mahasiswa.kelas')
                    ->get();

                foreach ($mahasiswaRows as $mhs) {
                    $nilaiRow = DB::table('nilai')
                        ->where('mahasiswa_id', $mhs->id)
                        ->where('mata_kuliah_id', $filterMK)
                        ->first();
                    $mahasiswaList[] = [
                        'id'               => $mhs->id,
                        'nama'             => $mhs->nama,
                        'email'            => $mhs->email,
                        'kelas'            => $mhs->kelas ?? ($mk->kelas ?? 'A'),
                        'nilai_tugas'      => $nilaiRow->nilai_tugas      ?? null,
                        'nilai_praktikum'  => $nilaiRow->nilai_praktikum  ?? null,
                        'nilai_uts'        => $nilaiRow->nilai_uts         ?? null,
                        'nilai_uas'        => $nilaiRow->nilai_uas         ?? null,
                        'nilai_kehadiran'  => $nilaiRow->nilai_kehadiran   ?? null,
                        'nilai_akhir'      => $nilaiRow->nilai_akhir       ?? null,
                        'grade'            => $nilaiRow->nilai             ?? null,
                        'bobot'            => $nilaiRow->bobot             ?? null,
                        'sudah_dinilai'    => $nilaiRow !== null,
                    ];
                }
            }
        }

        return view('pages.dosen_matkul.input-nilai', compact(
            'mataKuliahList', 'mahasiswaList', 'bobotAktif', 'mkAktif',
            'filterTahunAjaran', 'filterSemester', 'filterMK', 'filterKelas',
            'tahunAjaranList', 'semesterList', 'kelasList'
        ));
    }

    // ──────────────────────────────────────────────────────────────
    //  SIMPAN NILAI (POST) — inline table save
    // ──────────────────────────────────────────────────────────────
    public function simpanNilai(Request $request)
    {
        $nik        = $this->dosenNik();
        $mkId       = $request->input('mata_kuliah_id');
        $mahasiswaId = $request->input('mahasiswa_id');

        // Validasi bobot total = 100
        $bobotTugas     = (float)$request->input('bobot_tugas', 20);
        $bobotPraktikum = (float)$request->input('bobot_praktikum', 15);
        $bobotUts       = (float)$request->input('bobot_uts', 30);
        $bobotUas       = (float)$request->input('bobot_uas', 30);
        $bobotKehadiran = (float)$request->input('bobot_kehadiran', 5);

        $totalBobot = $bobotTugas + $bobotPraktikum + $bobotUts + $bobotUas + $bobotKehadiran;
        if (abs($totalBobot - 100) > 0.01) {
            return response()->json([
                'success' => false,
                'message' => 'Total bobot harus 100%. Sekarang: ' . $totalBobot . '%',
            ], 422);
        }

        // Nilai komponen
        $nTugas      = $request->input("nilai_tugas.$mahasiswaId");
        $nPraktikum  = $request->input("nilai_praktikum.$mahasiswaId");
        $nUts        = $request->input("nilai_uts.$mahasiswaId");
        $nUas        = $request->input("nilai_uas.$mahasiswaId");
        $nKehadiran  = $request->input("nilai_kehadiran.$mahasiswaId");

        // Hitung nilai akhir
        $nilaiAkhir = (
            ((float)$nTugas     * $bobotTugas     / 100) +
            ((float)$nPraktikum * $bobotPraktikum / 100) +
            ((float)$nUts       * $bobotUts       / 100) +
            ((float)$nUas       * $bobotUas       / 100) +
            ((float)$nKehadiran * $bobotKehadiran / 100)
        );

        $konversi = self::konversiNilai($nilaiAkhir);

        // Ambil info MK
        $mk = DB::table('mata_kuliah')->find($mkId);

        // Cari semester aktif
        $semester = DB::table('semesters')->where('is_active', true)->first();
        $semesterKe   = $mk->semester_ke ?? 1;
        $tahunAjaran  = $mk->tahun_ajaran ?? ($semester->tahun_ajaran ?? '2025/2026');

        $data = [
            'mahasiswa_id'     => $mahasiswaId,
            'mata_kuliah_id'   => $mkId,
            'nilai'            => $konversi['grade'],
            'nilai_tugas'      => $nTugas,
            'nilai_praktikum'  => $nPraktikum,
            'nilai_uts'        => $nUts,
            'nilai_uas'        => $nUas,
            'nilai_kehadiran'  => $nKehadiran,
            'nilai_akhir'      => round($nilaiAkhir, 2),
            'bobot'            => $konversi['mutu'],
            'kelas'            => $mk->kelas ?? 'A',
            'bobot_tugas'      => $bobotTugas,
            'bobot_praktikum'  => $bobotPraktikum,
            'bobot_uts'        => $bobotUts,
            'bobot_uas'        => $bobotUas,
            'bobot_kehadiran'  => $bobotKehadiran,
            'sks'              => $mk->sks ?? 3,
            'semester'         => $semesterKe,
            'tahun_ajaran'     => $tahunAjaran,
            'dosen_nik'        => $nik,
            'updated_at'       => now(),
        ];

        $existing = DB::table('nilai')
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('mata_kuliah_id', $mkId)
            ->first();

        if ($existing) {
            DB::table('nilai')->where('id', $existing->id)->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('nilai')->insert($data);
        }

        $nama = DB::table('users')->where('id', $mahasiswaId)->value('name') ?? 'Mahasiswa';

        return response()->json([
            'success'     => true,
            'message'     => "Nilai $nama berhasil disimpan.",
            'nilai_akhir' => round($nilaiAkhir, 2),
            'grade'       => $konversi['grade'],
            'mutu'        => $konversi['mutu'],
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    //  LIHAT NILAI
    // ──────────────────────────────────────────────────────────────
    public function lihatNilai(Request $request)
    {
        $nik = $this->dosenNik();

        $filterMK          = $request->input('mata_kuliah_id', '');
        $filterTahunAjaran = $request->input('tahun_ajaran', '');
        $filterSemester    = $request->input('semester', '');
        $filterKelas       = $request->input('kelas', '');

        $tahunAjaranList = DB::table('mata_kuliah')
            ->where('dosen_nik', $nik)
            ->whereNotNull('tahun_ajaran')
            ->distinct()->pluck('tahun_ajaran')->toArray();
        if (empty($tahunAjaranList)) $tahunAjaranList = ['2025/2026'];

        $semesterList = ['Ganjil', 'Genap'];

        $daftarMK = DB::table('mata_kuliah')
            ->where('dosen_nik', $nik)
            ->get(['id','kode_mk','nama','kelas'])->toArray();

        $kelasList = DB::table('mata_kuliah')
            ->where('dosen_nik', $nik)
            ->whereNotNull('kelas')
            ->distinct()->pluck('kelas')->sort()->values()->toArray();
        if (empty($kelasList)) $kelasList = ['A','B'];

        // Query nilai
        $query = DB::table('nilai')
            ->join('users', 'nilai.mahasiswa_id', '=', 'users.id')
            ->join('mata_kuliah', 'nilai.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->where('nilai.dosen_nik', $nik)
            ->select(
                'users.id as mahasiswa_id',
                'users.name as nama',
                'mata_kuliah.kode_mk',
                'mata_kuliah.nama as nama_mk',
                'nilai.kelas',
                'nilai.nilai as grade',
                'nilai.bobot as mutu',
                'nilai.nilai_akhir',
                'nilai.nilai_tugas',
                'nilai.nilai_praktikum',
                'nilai.nilai_uts',
                'nilai.nilai_uas',
                'nilai.nilai_kehadiran',
                'nilai.bobot_tugas',
                'nilai.bobot_praktikum',
                'nilai.bobot_uts',
                'nilai.bobot_uas',
                'nilai.bobot_kehadiran',
                'nilai.tahun_ajaran'
            );

        if ($filterMK) $query->where('nilai.mata_kuliah_id', $filterMK);
        if ($filterKelas) $query->where('nilai.kelas', $filterKelas);
        if ($filterTahunAjaran) $query->where('nilai.tahun_ajaran', $filterTahunAjaran);

        $mahasiswa = $query->orderBy('users.name')->get()->map(function ($m, $i) {
            return array_merge((array)$m, ['no' => $i + 1]);
        })->toArray();

        $stats = [
            'total_mahasiswa' => count($mahasiswa),
            'nilai_terinput'  => collect($mahasiswa)->filter(fn($m) => !is_null($m['nilai_akhir']))->count(),
            'rata_nilai'      => count($mahasiswa) > 0
                ? number_format(collect($mahasiswa)->avg('nilai_akhir'), 2)
                : '0.00',
        ];

        return view('pages.dosen_matkul.lihat-nilai', compact(
            'stats', 'mahasiswa', 'daftarMK',
            'filterMK', 'filterTahunAjaran', 'filterSemester', 'filterKelas',
            'tahunAjaranList', 'semesterList', 'kelasList'
        ));
    }

    // ──────────────────────────────────────────────────────────────
    //  PROFIL
    // ──────────────────────────────────────────────────────────────
    public function profil()
    {
        $user = session('user', []);
        $dosen = [
            'nama'          => $user['name']  ?? 'Dosen Mata Kuliah',
            'nidn'          => $user['nik']   ?? '-',
            'email'         => $user['email'] ?? 'dosen@poltek.ac.id',
            'no_hp'         => '08123456789',
            'alamat'        => 'Kota Batam',
            'program_studi' => 'Teknik Informatika',
        ];
        return view('pages.dosen_matkul.profil', compact('dosen'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'email'  => 'required|email',
            'no_hp'  => 'required|string',
            'alamat' => 'required|string',
        ]);
        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => ['required', 'min:6', 'confirmed'],
        ]);
        return redirect()->back()->with('success', 'Password berhasil diubah');
    }
}