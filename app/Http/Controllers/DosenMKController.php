<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DosenMKController extends Controller
{
    private function dosenId(): int   { return (int)(session('user.dosen_id') ?? 0); }
    private function dosenNik(): string { return (string)(session('user.nik') ?? ''); }

    private function semesterAktif()
    {
        return DB::table('semesters')->where('is_active', 1)->first();
    }

    private function onlyExistingColumns(string $table, array $data): array
    {
        $columns = Schema::getColumnListing($table);
        return collect($data)->only($columns)->toArray();
    }

    // Ambil mata kuliah yang diampu dosen ini
    private function getMataKuliahDiampu()
    {
        $dosenId = $this->dosenId();
        $nik     = $this->dosenNik();

        return DB::table('mata_kuliah')
            ->join('semesters', 'mata_kuliah.semester_id', '=', 'semesters.id')
            ->when($dosenId, fn($q) => $q->where('mata_kuliah.dosen_id', $dosenId))
            ->when(!$dosenId && $nik, fn($q) => $q->where('mata_kuliah.dosen_nik', $nik))
            ->select(
                'mata_kuliah.id',
                'mata_kuliah.kode_mk',
                'mata_kuliah.nama',
                'mata_kuliah.sks',
                'mata_kuliah.semester_ke',
                'mata_kuliah.kelas',
                'mata_kuliah.tahun_ajaran',
                'mata_kuliah.semester_id',
                'mata_kuliah.dosen_nik',
                'semesters.tahun_ajaran as sem_tahun',
                'semesters.semester as sem_nama',
                'semesters.is_active'
            )
            ->get();
    }

    // Mahasiswa yang mengambil MK ini (dari krs_detail + krs_mahasiswa disetujui)
    private function getMahasiswaByMK($mkId, ?string $kelas = null)
    {
        $query = DB::table('krs_detail')
            ->join('krs_mahasiswa', 'krs_detail.krs_mahasiswa_id', '=', 'krs_mahasiswa.id')
            ->join('mahasiswa', 'krs_mahasiswa.mahasiswa_id', '=', 'mahasiswa.id')
            ->where('krs_detail.mata_kuliah_id', $mkId)
            ->where('krs_mahasiswa.status', 'disetujui');

        if ($kelas) $query->where('mahasiswa.kelas', $kelas);

        return $query->select(
            'mahasiswa.id', 'mahasiswa.nim', 'mahasiswa.nama',
            'mahasiswa.email', 'mahasiswa.kelas'
        )->orderBy('mahasiswa.nama')->get();
    }

    public static function konversiNilai(float $nilai): array
    {
        if ($nilai >= 85) return ['grade' => 'A',  'mutu' => 4.00];
        if ($nilai >= 80) return ['grade' => 'A-', 'mutu' => 3.75];
        if ($nilai >= 75) return ['grade' => 'B+', 'mutu' => 3.50];
        if ($nilai >= 70) return ['grade' => 'B',  'mutu' => 3.25];
        if ($nilai >= 65) return ['grade' => 'B-', 'mutu' => 3.00];
        if ($nilai >= 60) return ['grade' => 'C+', 'mutu' => 2.75];
        if ($nilai >= 55) return ['grade' => 'C',  'mutu' => 2.50];
        if ($nilai >= 40) return ['grade' => 'D',  'mutu' => 1.00];
        return ['grade' => 'E', 'mutu' => 0.00];
    }

    // ═══════════════════════════════════════════════════════
    //  BERANDA
    // ═══════════════════════════════════════════════════════
    public function index()
    {
        $mataKuliahDiampu = $this->getMataKuliahDiampu();
        $dosenId = $this->dosenId();
        $nik     = $this->dosenNik();

        $totalMhs = $nilaiDiinput = $belumDinilai = 0;
        foreach ($mataKuliahDiampu as $mk) {
            $jmlMhs = $this->getMahasiswaByMK($mk->id)->count();
            $sudahDinilai = DB::table('nilai')
                ->where('mata_kuliah_id', $mk->id)->count();
            $totalMhs    += $jmlMhs;
            $nilaiDiinput += $sudahDinilai;
            $belumDinilai += max(0, $jmlMhs - $sudahDinilai);
        }

        $stats = [
            'mata_kuliah_diampu' => $mataKuliahDiampu->count(),
            'total_mahasiswa'    => $totalMhs,
            'nilai_diinput'      => $nilaiDiinput,
            'belum_dinilai'      => $belumDinilai,
        ];

        $mkIds = $mataKuliahDiampu->pluck('id');
        $mahasiswaTerbaru = DB::table('nilai')
            ->join('mahasiswa', 'nilai.mahasiswa_id', '=', 'mahasiswa.id')
            ->leftJoin('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
            ->whereIn('nilai.mata_kuliah_id', $mkIds)
            ->select(
                'mahasiswa.nama',
                'mahasiswa.nim',
                'mahasiswa.kelas',
                'prodi.nama_prodi as prodi',
                DB::raw('AVG(nilai.bobot) as rata_nilai')
            )
            ->groupBy('mahasiswa.id', 'mahasiswa.nama', 'mahasiswa.nim', 'mahasiswa.kelas', 'prodi.nama_prodi')
            ->orderByDesc('rata_nilai')->limit(5)->get()
            ->map(fn($m) => [
                'nama'       => $m->nama,
                'nim'        => $m->nim,
                'kelas'      => $m->kelas ?? '-',
                'prodi'      => $m->prodi ?? 'Belum ada prodi',
                'rata_nilai' => round((float)$m->rata_nilai, 2),
            ])->toArray();

        $mataKuliah = $mataKuliahDiampu->map(fn($mk) => [
            'id'        => $mk->id,
            'kode'      => $mk->kode_mk,
            'nama'      => $mk->nama,
            'sks'       => $mk->sks,
            'kelas'     => $mk->kelas ?? '-',
            'mahasiswa' => $this->getMahasiswaByMK($mk->id)->count(),
            'kapasitas' => 40,
            'is_active' => (bool)$mk->is_active,
        ])->toArray();

        return view('pages.dosen_matkul.beranda', compact('stats','mataKuliah','mahasiswaTerbaru'));
    }

    // ═══════════════════════════════════════════════════════
    //  INPUT NILAI — GET (tampilkan halaman)
    // ═══════════════════════════════════════════════════════
    public function inputNilai(Request $request)
    {
        $allMataKuliahDiampu = $this->getMataKuliahDiampu();
        $semAktif = $this->semesterAktif();
        $allSem   = DB::table('semesters')->orderByDesc('id')->get();

        $filterTahunAjaran = $request->input('tahun_ajaran', '');
        $filterSemesterId  = $request->input('semester_id', '');
        $filterKodeMK      = $request->input('kode_mk', ''); // Diubah dari mata_kuliah_id
        $filterKelas       = $request->input('kelas', '');

        if (!$filterSemesterId && !$filterTahunAjaran) {
            $filterSemesterId = $semAktif ? $semAktif->id : '';
        }

        $mataKuliahDiampu = $allMataKuliahDiampu->filter(function($mk) use ($filterTahunAjaran, $filterSemesterId) {
            if ($filterSemesterId && $mk->semester_id != $filterSemesterId) return false;
            if ($filterTahunAjaran && $mk->sem_tahun != $filterTahunAjaran) return false;
            return true;
        });

        $tahunAjaranList = $allMataKuliahDiampu->pluck('sem_tahun')->unique()->sort()->values()->toArray();

        // Group MK by kode_mk agar tidak duplikat di dropdown
        $mataKuliahList = $mataKuliahDiampu->groupBy('kode_mk')->map(function($group) {
            $first = $group->first();
            return [
                'kode'      => $first->kode_mk,
                'nama'      => $first->nama,
                'is_active' => (bool)$first->is_active,
            ];
        })->values()->toArray();

        $mahasiswaList = [];
        $mkAktif       = null;
        $kelasDariMK   = [];
        $isReadOnly    = false;

        if ($filterKodeMK) {
            // Ambil semua kelas dari kode_mk ini
            $kelasDariMK = $allMataKuliahDiampu
                ->where('kode_mk', $filterKodeMK)
                ->pluck('kelas')
                ->sort()->values()->toArray();

            if (count($kelasDariMK) === 1 && !$filterKelas) {
                $filterKelas = $kelasDariMK[0];
            }

            // Cari MK spesifik berdasarkan Kode MK + Kelas
            if ($filterKelas) {
                $mk = DB::table('mata_kuliah')
                    ->join('semesters','mata_kuliah.semester_id','=','semesters.id')
                    ->where('mata_kuliah.kode_mk', $filterKodeMK)
                    ->where('mata_kuliah.kelas', $filterKelas)
                    ->where(function($q) {
                        $q->where('mata_kuliah.dosen_id', $this->dosenId())
                          ->orWhere('mata_kuliah.dosen_nik', $this->dosenNik());
                    })
                    ->select('mata_kuliah.*','semesters.is_active','semesters.semester as sem_nama','semesters.tahun_ajaran as sem_tahun')
                    ->first();

                if ($mk) {
                    $mkAktif   = $mk;
                    $isReadOnly = !(bool)$mk->is_active;

                    $mahasiswaRows = $this->getMahasiswaByMK($mk->id, null);

                    $mahasiswaList = $mahasiswaRows->map(function ($mhs) use ($mk) {
                        $nilaiRow = DB::table('nilai')
                            ->where('mahasiswa_id', $mhs->id)
                            ->where('mata_kuliah_id', $mk->id)
                            ->first();
                        return [
                            'id'              => $mhs->id,
                            'nim'             => $mhs->nim,
                            'nama'            => $mhs->nama,
                            'kelas'           => $mhs->kelas ?? '-',
                            'nilai_tugas'     => $nilaiRow->nilai_tugas     ?? null,
                            'nilai_praktikum' => $nilaiRow->nilai_praktikum ?? null,
                            'nilai_uts'       => $nilaiRow->nilai_uts        ?? null,
                            'nilai_uas'       => $nilaiRow->nilai_uas        ?? null,
                            'nilai_kehadiran' => $nilaiRow->nilai_kehadiran  ?? null,
                            'nilai_akhir'     => $nilaiRow->nilai_akhir      ?? null,
                            'grade'           => $nilaiRow->nilai            ?? null,
                            'bobot_tugas'     => $nilaiRow->bobot_tugas     ?? 20,
                            'bobot_praktikum' => $nilaiRow->bobot_praktikum ?? 15,
                            'bobot_uts'       => $nilaiRow->bobot_uts        ?? 30,
                            'bobot_uas'       => $nilaiRow->bobot_uas        ?? 30,
                            'bobot_kehadiran' => $nilaiRow->bobot_kehadiran  ?? 5,
                        ];
                    })->toArray();
                }
            }
        }

        return view('pages.dosen_matkul.input-nilai', compact(
            'mataKuliahList','mahasiswaList','mkAktif',
            'filterKodeMK','filterKelas','kelasDariMK','isReadOnly',
            'allSem','filterSemesterId','filterTahunAjaran','tahunAjaranList'
        ));
    }

    // ═══════════════════════════════════════════════════════
    //  SIMPAN NILAI — AJAX POST
    // ═══════════════════════════════════════════════════════
    public function simpanNilai(Request $request)
    {
        $kodeMK      = $request->input('kode_mk');
        $kelas       = $request->input('kelas');
        $mahasiswaId = $request->input('mahasiswa_id');
        $nik         = $this->dosenNik();

        // Cari ID MK yang spesifik berdasarkan Kode + Kelas
        $mk = DB::table('mata_kuliah')
            ->join('semesters','mata_kuliah.semester_id','=','semesters.id')
            ->where('mata_kuliah.kode_mk', $kodeMK)
            ->where('mata_kuliah.kelas', $kelas)
            ->where(function($q) use ($nik) {
                $q->where('mata_kuliah.dosen_nik', $nik)
                  ->orWhere('mata_kuliah.dosen_id', $this->dosenId());
            })
            ->select('mata_kuliah.*','semesters.is_active','semesters.semester as sem_nama','semesters.tahun_ajaran as sem_tahun','semesters.id as sem_id')
            ->first();

        if (!$mk) return response()->json(['success'=>false,'message'=>'Mata kuliah/kelas tidak ditemukan.'], 404);
        if (!(bool)$mk->is_active) return response()->json(['success'=>false,'message'=>'Tidak bisa menyimpan nilai di semester yang tidak aktif.'], 403);

        $bobotTugas     = (float)$request->input('bobot_tugas', 20);
        $bobotPraktikum = (float)$request->input('bobot_praktikum', 15);
        $bobotUts       = (float)$request->input('bobot_uts', 30);
        $bobotUas       = (float)$request->input('bobot_uas', 30);
        $bobotKehadiran = (float)$request->input('bobot_kehadiran', 5);
        $totalBobot     = $bobotTugas + $bobotPraktikum + $bobotUts + $bobotUas + $bobotKehadiran;

        if (abs($totalBobot - 100) > 0.01) {
            return response()->json(['success'=>false,'message'=>"Total bobot harus 100%. Sekarang: {$totalBobot}%"], 422);
        }

        $nTugas     = (float)$request->input("nilai_tugas.$mahasiswaId", 0);
        $nPraktikum = (float)$request->input("nilai_praktikum.$mahasiswaId", 0);
        $nUts       = (float)$request->input("nilai_uts.$mahasiswaId", 0);
        $nUas       = (float)$request->input("nilai_uas.$mahasiswaId", 0);
        $nKehadiran = (float)$request->input("nilai_kehadiran.$mahasiswaId", 0);

        $nilaiAkhir = ($nTugas*$bobotTugas + $nPraktikum*$bobotPraktikum + $nUts*$bobotUts + $nUas*$bobotUas + $nKehadiran*$bobotKehadiran) / 100;
        $k = self::konversiNilai($nilaiAkhir);

        $data = $this->onlyExistingColumns('nilai', [
            'mahasiswa_id'    => $mahasiswaId,
            'mata_kuliah_id'  => $mk->id,
            'semester_id'     => $mk->sem_id,
            'tahun_ajaran'    => $mk->sem_tahun ?? '2025/2026',
            'semester'        => $mk->semester_ke ?? 0,
            'kelas'           => $mk->kelas,
            'nilai'           => $k['grade'],
            'bobot'           => $k['mutu'],
            'sks'             => $mk->sks,
            'nilai_tugas'     => $nTugas,
            'nilai_praktikum' => $nPraktikum,
            'nilai_uts'       => $nUts,
            'nilai_uas'       => $nUas,
            'nilai_kehadiran' => $nKehadiran,
            'nilai_akhir'     => round($nilaiAkhir, 2),
            'bobot_tugas'     => $bobotTugas,
            'bobot_praktikum' => $bobotPraktikum,
            'bobot_uts'       => $bobotUts,
            'bobot_uas'       => $bobotUas,
            'bobot_kehadiran' => $bobotKehadiran,
            'dosen_nik'       => $nik,
            'updated_at'      => now(),
        ]);

        $existing = DB::table('nilai')
            ->where('mahasiswa_id',$mahasiswaId)
            ->where('mata_kuliah_id',$mk->id)->first();

        if ($existing) {
            DB::table('nilai')->where('id',$existing->id)->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('nilai')->insert($data);
        }

        $nama = DB::table('mahasiswa')->where('id',$mahasiswaId)->value('nama') ?? 'Mahasiswa';
        return response()->json([
            'success'     => true,
            'message'     => "Nilai $nama berhasil disimpan.",
            'nilai_akhir' => round($nilaiAkhir, 2),
            'grade'       => $k['grade'],
            'mutu'        => $k['mutu'],
        ]);
    }

    // ═══════════════════════════════════════════════════════
    //  LIHAT NILAI — GET
    // ═══════════════════════════════════════════════════════
    public function lihatNilai(Request $request)
    {
        $allMataKuliahDiampu = $this->getMataKuliahDiampu();
        $allSem = DB::table('semesters')->orderByDesc('id')->get();

        $filterTahunAjaran = $request->input('tahun_ajaran', '');
        $filterSemesterId  = $request->input('semester_id', '');
        $filterMK          = $request->input('mata_kuliah_id', '');
        $filterKelas       = $request->input('kelas', '');

        // Filter MK berdasarkan tahun ajaran / semester
        $mataKuliahDiampu = $allMataKuliahDiampu->filter(function($mk) use ($filterTahunAjaran, $filterSemesterId) {
            if ($filterSemesterId && $mk->semester_id != $filterSemesterId) return false;
            if ($filterTahunAjaran && $mk->sem_tahun != $filterTahunAjaran) return false;
            return true;
        });

        $mkIds = $mataKuliahDiampu->pluck('id');
        $tahunAjaranList = $allMataKuliahDiampu->pluck('sem_tahun')->unique()->sort()->values()->toArray();

        $daftarMK = $mataKuliahDiampu->map(fn($mk) => [
            'id'     => $mk->id,
            'kode_mk'=> $mk->kode_mk,
            'nama'   => $mk->nama,
            'kelas'  => $mk->kelas ?? '-',
        ])->values()->toArray();

        // Kelas dari MK terpilih (auto filter)
        $kelasDariMK = [];
        if ($filterMK) {
            $kelasDariMK = DB::table('krs_detail')
                ->join('krs_mahasiswa','krs_detail.krs_mahasiswa_id','=','krs_mahasiswa.id')
                ->join('mahasiswa','krs_mahasiswa.mahasiswa_id','=','mahasiswa.id')
                ->where('krs_detail.mata_kuliah_id', $filterMK)
                ->where('krs_mahasiswa.status','disetujui')
                ->whereNotNull('mahasiswa.kelas')
                ->distinct()->pluck('mahasiswa.kelas')
                ->sort()->values()->toArray();

            if (count($kelasDariMK) === 1 && !$filterKelas) {
                $filterKelas = $kelasDariMK[0];
            }
        }

        $allSem = DB::table('semesters')->orderByDesc('id')->get();

        $query = DB::table('nilai')
            ->join('mahasiswa','nilai.mahasiswa_id','=','mahasiswa.id')
            ->join('mata_kuliah','nilai.mata_kuliah_id','=','mata_kuliah.id')
            ->whereIn('nilai.mata_kuliah_id', $mkIds)
            ->select(
                'mahasiswa.id as mahasiswa_id','mahasiswa.nim','mahasiswa.nama',
                'mahasiswa.kelas as kelas_mhs',
                'mata_kuliah.kode_mk','mata_kuliah.nama as nama_mk',
                'nilai.nilai as grade','nilai.bobot as mutu','nilai.nilai_akhir',
                'nilai.nilai_tugas','nilai.nilai_praktikum','nilai.nilai_uts',
                'nilai.nilai_uas','nilai.nilai_kehadiran',
                'nilai.tahun_ajaran','nilai.semester','nilai.semester_id'
            );

        if ($filterMK)         $query->where('nilai.mata_kuliah_id', $filterMK);
        if ($filterKelas)      $query->where('mahasiswa.kelas', $filterKelas);
        if ($filterSemesterId) $query->where('nilai.semester_id', $filterSemesterId);

        $mahasiswa = $query->orderBy('mahasiswa.nama')->get()
            ->map(fn($m,$i) => array_merge((array)$m, ['no' => $i+1]))->toArray();

        $stats = [
            'total_mahasiswa' => count($mahasiswa),
            'nilai_terinput'  => collect($mahasiswa)->filter(fn($m) => !is_null($m['grade']))->count(),
            'rata_nilai'      => count($mahasiswa) > 0
                ? number_format(collect($mahasiswa)->avg('nilai_akhir') ?? 0, 2)
                : '0.00',
        ];

        return view('pages.dosen_matkul.lihat-nilai', compact(
            'stats','mahasiswa','daftarMK','allSem',
            'filterMK','filterKelas','filterSemesterId','kelasDariMK',
            'filterTahunAjaran','tahunAjaranList'
        ));
    }

    // ═══════════════════════════════════════════════════════
    //  API — kelas dari MK (untuk filter auto via JS)
    // ═══════════════════════════════════════════════════════
    public function getKelasByMK(Request $request)
    {
        $kodeMK = $request->input('kode_mk');
        if (!$kodeMK) return response()->json([]);

        $dosenId = $this->dosenId();
        $nik = $this->dosenNik();

        $kelasList = DB::table('mata_kuliah')
            ->where('kode_mk', $kodeMK)
            ->where(function($q) use ($dosenId, $nik) {
                $q->where('dosen_id', $dosenId)->orWhere('dosen_nik', $nik);
            })
            ->whereNotNull('kelas')
            ->distinct()->pluck('kelas')
            ->sort()->values();

        $isAnyActive = DB::table('mata_kuliah')
            ->join('semesters','mata_kuliah.semester_id','=','semesters.id')
            ->where('kode_mk', $kodeMK)
            ->where('semesters.is_active', 1)
            ->exists();

        return response()->json([
            'kelas'     => $kelasList,
            'is_active' => $isAnyActive,
        ]);
    }

    // ═══════════════════════════════════════════════════════
    //  PROFIL DOSEN MK
    // ═══════════════════════════════════════════════════════
    public function profil()
    {
        $dosenId = $this->dosenId();
        $nik     = $this->dosenNik();

        $dbDosen = null;
        if ($dosenId) {
            $dbDosen = DB::table('dosen')->where('id', $dosenId)->first();
        } elseif ($nik) {
            $dbDosen = DB::table('dosen')->where('nik', $nik)->first();
        }

        $sess  = session('user', []);
        $dosen = [
            'nama'          => $dbDosen->nama   ?? $sess['name']  ?? 'Dosen Mata Kuliah',
            'nip'           => $dbDosen->nip    ?? '-',
            'nidn'          => $dbDosen->nik    ?? $nik ?? '-',
            'email'         => $dbDosen->email  ?? $sess['email'] ?? '-',
            'no_hp'         => $dbDosen->no_hp  ?? '-',
            'alamat'        => $dbDosen->alamat ?? 'Kota Batam',
            'program_studi' => 'Teknik Informatika',
        ];

        return view('pages.dosen_matkul.profil', compact('dosen'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:100',
            'email'  => 'required|email|max:100',
            'no_hp'  => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);

        $dosenId = $this->dosenId();
        $nik     = $this->dosenNik();

        $dbDosen = $dosenId
            ? DB::table('dosen')->where('id', $dosenId)->first()
            : DB::table('dosen')->where('nik', $nik)->first();

        if ($dbDosen) {
            DB::table('dosen')->where('id', $dbDosen->id)->update([
                'nama' => $validated['nama'], 'email' => $validated['email'],
                'no_hp' => $validated['no_hp'] ?? null, 'alamat' => $validated['alamat'] ?? null,
                'updated_at' => now(),
            ]);
            if ($dbDosen->user_id) {
                DB::table('users')->where('id',$dbDosen->user_id)->update([
                    'name' => $validated['nama'], 'email' => $validated['email'], 'updated_at' => now(),
                ]);
            }
        }

        $user = session('user', []);
        $user['name'] = $validated['nama']; $user['email'] = $validated['email'];
        session(['user' => $user, 'user_name' => $validated['nama']]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required|string',
            'password_baru' => 'required|string|min:6',
            'password_baru_confirmation' => 'required|same:password_baru',
        ], ['password_baru_confirmation.same' => 'Konfirmasi password tidak cocok.']);

        $dosenId = $this->dosenId();
        $nik     = $this->dosenNik();

        $dbDosen = $dosenId
            ? DB::table('dosen')->where('id', $dosenId)->first()
            : DB::table('dosen')->where('nik', $nik)->first();

        if (!$dbDosen || !$dbDosen->user_id) return redirect()->back()->with('error','Data tidak ditemukan.');

        $userDb = DB::table('users')->where('id', $dbDosen->user_id)->first();
        $valid = ($request->password_lama === $userDb->password)
              || \Illuminate\Support\Facades\Hash::check($request->password_lama, $userDb->password);

        if (!$valid) return redirect()->back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);

        DB::table('users')->where('id', $dbDosen->user_id)->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password_baru),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Password berhasil diubah.');
    }
}
