<?php

namespace App\Http\Controllers;

use App\Support\PasswordVerifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class DosenMKController extends Controller
{
    private function dosenId(): int
    {
        return (int) (session('user.dosen_id') ?? 0);
    }

    private function dosenNik(): string
    {
        return (string) (session('user.nik') ?? '');
    }

    private function semesterAktif()
    {
        return DB::table('semesters')
            ->where('is_active', 1)
            ->first();
    }

    private function onlyExistingColumns(string $table, array $data): array
    {
        $columns = Schema::getColumnListing($table);

        return collect($data)->only($columns)->toArray();
    }

    private function getMataKuliahDiampu()
    {
        $dosenId = $this->dosenId();
        $nik = $this->dosenNik();

        return DB::table('mata_kuliah')
            ->join('semesters', 'mata_kuliah.semester_id', '=', 'semesters.id')
            ->leftJoin('dosen_matakuliah', 'mata_kuliah.id', '=', 'dosen_matakuliah.mata_kuliah_id')
            ->leftJoin('dosen', 'dosen_matakuliah.dosen_id', '=', 'dosen.id')
            ->leftJoin('kelas', 'dosen_matakuliah.kelas_id', '=', 'kelas.id')
            ->where(function ($query) use ($dosenId, $nik) {
                if ($dosenId) {
                    $query->where('dosen_matakuliah.dosen_id', $dosenId)
                        ->orWhere('mata_kuliah.dosen_id', $dosenId);
                }

                if ($nik) {
                    $query->orWhere('dosen.nik', $nik)
                        ->orWhere('mata_kuliah.dosen_nik', $nik);
                }
            })
            ->select(
                'mata_kuliah.id',
                'mata_kuliah.kode_mk',
                'mata_kuliah.nama',
                'mata_kuliah.sks',
                'mata_kuliah.semester_ke',
                DB::raw('COALESCE(kelas.nama_kelas, mata_kuliah.kelas) as kelas'),
                'mata_kuliah.tahun_ajaran',
                'mata_kuliah.semester_id',
                'mata_kuliah.dosen_nik',
                'semesters.tahun_ajaran as sem_tahun',
                'semesters.semester as sem_nama',
                'semesters.semester_ke as sem_ke',
                'semesters.is_active'
            )
            ->distinct()
            ->get();
    }

    private function getMahasiswaByMK($mkId, ?string $kelas = null)
    {
        $query = DB::table('krs_detail')
            ->join('krs_mahasiswa', 'krs_detail.krs_mahasiswa_id', '=', 'krs_mahasiswa.id')
            ->join('mahasiswa', 'krs_mahasiswa.mahasiswa_id', '=', 'mahasiswa.id')
            ->where('krs_detail.mata_kuliah_id', $mkId)
            ->where('krs_mahasiswa.status', 'disetujui');

        if ($kelas) {
            $kelasPendek = str_replace('-PAGI', '-', $kelas);

            $query->where(function ($q) use ($kelas, $kelasPendek) {
                $q->where('mahasiswa.kelas', $kelas)
                    ->orWhere('mahasiswa.kelas', $kelasPendek);
            });
        }

        return $query
            ->select(
                'mahasiswa.id',
                'mahasiswa.nim',
                'mahasiswa.nama',
                'mahasiswa.email',
                'mahasiswa.kelas'
            )
            ->orderBy('mahasiswa.nama')
            ->get();
    }

    private function kelasDariKrsUntukMkIds($mkIds)
    {
        $ids = collect($mkIds)
            ->filter()
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return collect();
        }

        return DB::table('krs_detail')
            ->join('krs_mahasiswa', 'krs_detail.krs_mahasiswa_id', '=', 'krs_mahasiswa.id')
            ->join('mahasiswa', 'krs_mahasiswa.mahasiswa_id', '=', 'mahasiswa.id')
            ->whereIn('krs_detail.mata_kuliah_id', $ids)
            ->where('krs_mahasiswa.status', 'disetujui')
            ->whereNotNull('mahasiswa.kelas')
            ->pluck('mahasiswa.kelas');
    }

    private function kelasListUntukMataKuliah($mataKuliahRows): array
    {
        $rows = collect($mataKuliahRows);

        return $rows
            ->pluck('kelas')
            ->merge($this->kelasDariKrsUntukMkIds($rows->pluck('id')))
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }

    private function mataKuliahUntukKelas($mataKuliahRows, ?string $kelas)
    {
        $rows = collect($mataKuliahRows)->values();

        if ($rows->isEmpty()) {
            return null;
        }

        if (!$kelas) {
            return $rows->first();
        }

        $kelasPendek = str_replace('-PAGI', '-', $kelas);

        $exact = $rows->first(function ($mk) use ($kelas, $kelasPendek) {
            return ($mk->kelas ?? null) === $kelas || ($mk->kelas ?? null) === $kelasPendek;
        });

        if ($exact) {
            return $exact;
        }

        foreach ($rows as $mk) {
            if ($this->getMahasiswaByMK($mk->id, $kelas)->isNotEmpty()) {
                $selected = clone $mk;
                $selected->kelas = $kelas;

                return $selected;
            }
        }

        return null;
    }

    private function queryMataKuliahDiampuByKode(string $kodeMK, ?int $semesterId = null)
    {
        $dosenId = $this->dosenId();
        $nik = $this->dosenNik();

        $query = DB::table('mata_kuliah')
            ->join('semesters', 'mata_kuliah.semester_id', '=', 'semesters.id')
            ->leftJoin('dosen_matakuliah', 'mata_kuliah.id', '=', 'dosen_matakuliah.mata_kuliah_id')
            ->leftJoin('dosen', 'dosen_matakuliah.dosen_id', '=', 'dosen.id')
            ->leftJoin('kelas', 'dosen_matakuliah.kelas_id', '=', 'kelas.id')
            ->where('mata_kuliah.kode_mk', $kodeMK)
            ->where(function ($query) use ($dosenId, $nik) {
                if ($dosenId) {
                    $query->where('dosen_matakuliah.dosen_id', $dosenId)
                        ->orWhere('mata_kuliah.dosen_id', $dosenId);
                }

                if ($nik) {
                    $query->orWhere('dosen.nik', $nik)
                        ->orWhere('mata_kuliah.dosen_nik', $nik);
                }
            })
            ->select(
                'mata_kuliah.*',
                DB::raw('COALESCE(kelas.nama_kelas, mata_kuliah.kelas) as kelas_final'),
                'semesters.is_active',
                'semesters.semester as sem_nama',
                'semesters.tahun_ajaran as sem_tahun',
                'semesters.id as sem_id'
            )
            ->distinct();

        // Jika semester_id diberikan, prioritaskan semester tersebut
        if ($semesterId) {
            $query->orderByRaw('CASE WHEN semesters.id = ? THEN 0 ELSE 1 END', [$semesterId]);
        } else {
            $query->orderByDesc('semesters.is_active');
        }

        return $query->orderByDesc('semesters.id')->get();
    }

    private function mataKuliahDiampuUntukKelas(string $kodeMK, string $kelas, ?int $semesterId = null)
    {
        $rows = $this->queryMataKuliahDiampuByKode($kodeMK, $semesterId);
        $kelasPendek = str_replace('-PAGI', '-', $kelas);

        $exact = $rows->first(function ($mk) use ($kelas, $kelasPendek) {
            return ($mk->kelas_final ?? null) === $kelas
                || ($mk->kelas_final ?? null) === $kelasPendek
                || ($mk->kelas ?? null) === $kelas
                || ($mk->kelas ?? null) === $kelasPendek;
        });

        if ($exact) {
            return $exact;
        }

        foreach ($rows as $mk) {
            if ($this->getMahasiswaByMK($mk->id, $kelas)->isNotEmpty()) {
                $selected = clone $mk;
                $selected->kelas_final = $kelas;

                return $selected;
            }
        }

        return null;
    }

    private function mahasiswaSudahKrsDisetujui($mahasiswaId, $mataKuliahId, $semesterId): bool
    {
        return DB::table('krs_detail')
            ->join('krs_mahasiswa', 'krs_detail.krs_mahasiswa_id', '=', 'krs_mahasiswa.id')
            ->where('krs_mahasiswa.mahasiswa_id', $mahasiswaId)
            ->where('krs_mahasiswa.semester_id', $semesterId)
            ->where('krs_detail.mata_kuliah_id', $mataKuliahId)
            ->where('krs_mahasiswa.status', 'disetujui')
            ->exists();
    }

    public static function konversiNilai(float $nilai): array
    {
        if ($nilai >= 85)
            return ['grade' => 'A', 'mutu' => 4.00];
        if ($nilai >= 80)
            return ['grade' => 'A-', 'mutu' => 3.75];
        if ($nilai >= 75)
            return ['grade' => 'B+', 'mutu' => 3.50];
        if ($nilai >= 70)
            return ['grade' => 'B', 'mutu' => 3.25];
        if ($nilai >= 65)
            return ['grade' => 'B-', 'mutu' => 3.00];
        if ($nilai >= 60)
            return ['grade' => 'C+', 'mutu' => 2.75];
        if ($nilai >= 55)
            return ['grade' => 'C', 'mutu' => 2.50];
        if ($nilai >= 40)
            return ['grade' => 'D', 'mutu' => 1.00];

        return ['grade' => 'E', 'mutu' => 0.00];
    }

    public function index()
    {
        $mataKuliahDiampu = $this->getMataKuliahDiampu();

        $totalMhs = 0;
        $nilaiDiinput = 0;
        $belumDinilai = 0;

        foreach ($mataKuliahDiampu as $mk) {
            $mahasiswaKrs = $this->getMahasiswaByMK($mk->id, $mk->kelas);
            $jmlMhs = $mahasiswaKrs->count();

            $mahasiswaIds = $mahasiswaKrs->pluck('id')->toArray();

            $sudahDinilai = 0;

            if (!empty($mahasiswaIds)) {
                $sudahDinilai = DB::table('nilai')
                    ->whereIn('mahasiswa_id', $mahasiswaIds)
                    ->where('mata_kuliah_id', $mk->id)
                    ->whereNotNull('nilai')
                    ->count();
            }

            $totalMhs += $jmlMhs;
            $nilaiDiinput += $sudahDinilai;
            $belumDinilai += max(0, $jmlMhs - $sudahDinilai);
        }

        $stats = [
            'mata_kuliah_diampu' => $mataKuliahDiampu->count(),
            'total_mahasiswa' => $totalMhs,
            'nilai_diinput' => $nilaiDiinput,
            'belum_dinilai' => $belumDinilai,
        ];

        $mkIds = $mataKuliahDiampu->pluck('id');

        $mahasiswaTerbaru = DB::table('krs_detail')
            ->join('krs_mahasiswa', 'krs_detail.krs_mahasiswa_id', '=', 'krs_mahasiswa.id')
            ->join('mahasiswa', 'krs_mahasiswa.mahasiswa_id', '=', 'mahasiswa.id')
            ->leftJoin('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
            ->join('nilai', function ($join) {
                $join->on('nilai.mahasiswa_id', '=', 'mahasiswa.id')
                    ->on('nilai.mata_kuliah_id', '=', 'krs_detail.mata_kuliah_id');
            })
            ->where('krs_mahasiswa.status', 'disetujui')
            ->whereIn('krs_detail.mata_kuliah_id', $mkIds)
            ->whereNotNull('nilai.bobot')
            ->select(
                'mahasiswa.nama',
                'mahasiswa.nim',
                'mahasiswa.kelas',
                'prodi.nama_prodi as prodi',
                DB::raw('AVG(nilai.bobot) as rata_nilai')
            )
            ->groupBy(
                'mahasiswa.id',
                'mahasiswa.nama',
                'mahasiswa.nim',
                'mahasiswa.kelas',
                'prodi.nama_prodi'
            )
            ->orderByDesc('rata_nilai')
            ->limit(5)
            ->get()
            ->map(function ($m) {
                return [
                    'nama' => $m->nama,
                    'nim' => $m->nim,
                    'kelas' => $m->kelas ?? '-',
                    'prodi' => $m->prodi ?? 'Teknik Informatika',
                    'rata_nilai' => round((float) $m->rata_nilai, 2),
                ];
            })
            ->toArray();

        $mataKuliah = $mataKuliahDiampu->map(function ($mk) {
            return [
                'id' => $mk->id,
                'kode' => $mk->kode_mk,
                'nama' => $mk->nama,
                'sks' => $mk->sks,
                'kelas' => $mk->kelas ?? '-',
                'mahasiswa' => $this->getMahasiswaByMK($mk->id, $mk->kelas)->count(),
                'kapasitas' => 40,
                'is_active' => (bool) $mk->is_active,
            ];
        })->toArray();

        return view('pages.dosen_matkul.beranda', compact(
            'stats',
            'mataKuliah',
            'mahasiswaTerbaru'
        ));
    }

    public function inputNilai(Request $request)
    {
        $allMataKuliahDiampu = $this->getMataKuliahDiampu();
        $semAktif = $this->semesterAktif();
        $allSem = DB::table('semesters')->orderByDesc('id')->get();

        $filterTahunAjaran = $request->input('tahun_ajaran', '');
        $filterSemesterId = $request->input('semester_id', '');
        $filterKodeMK = $request->input('kode_mk', '');
        $filterKelas = $request->input('kelas', '');

        // Tidak memaksa ke semester aktif secara default.
        // Dosen bisa melihat semua semester yang pernah dia ampu.
        // Jika ingin default ke semester aktif, user harus pilih sendiri.

        $mataKuliahDiampu = $allMataKuliahDiampu->filter(function ($mk) use ($filterTahunAjaran, $filterSemesterId) {
            if ($filterSemesterId && $mk->semester_id != $filterSemesterId) {
                return false;
            }

            if ($filterTahunAjaran && $mk->sem_tahun != $filterTahunAjaran) {
                return false;
            }

            return true;
        });

        $tahunAjaranList = $allMataKuliahDiampu
            ->pluck('sem_tahun')
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        $mataKuliahList = $mataKuliahDiampu
            ->groupBy('kode_mk')
            ->map(function ($group) {
                $first = $group->first();

                return [
                    'kode' => $first->kode_mk,
                    'nama' => $first->nama,
                    'is_active' => (bool) $first->is_active,
                ];
            })
            ->values()
            ->toArray();

        $mahasiswaList = [];
        $mkAktif = null;
        $kelasDariMK = [];
        $isReadOnly = false;

        if ($filterKodeMK) {
            $mkCandidates = $mataKuliahDiampu
                ->where('kode_mk', $filterKodeMK)
                ->values();

            $kelasDariMK = $this->kelasListUntukMataKuliah($mkCandidates);

            if (count($kelasDariMK) === 1 && !$filterKelas) {
                $filterKelas = $kelasDariMK[0];
            }

            if ($filterKelas) {
                $mk = $this->mataKuliahUntukKelas($mkCandidates, $filterKelas);

                if ($mk) {
                    $mkAktif = $mk;
                    $isReadOnly = !(bool) $mk->is_active;

                    $mahasiswaRows = $this->getMahasiswaByMK($mk->id, $filterKelas);

                    $mahasiswaList = $mahasiswaRows->map(function ($mhs) use ($mk, $filterKelas) {
                        $kelasPendek = str_replace('-PAGI', '-', $filterKelas);

                        $nilaiRow = DB::table('nilai')
                            ->where('mahasiswa_id', $mhs->id)
                            ->where('mata_kuliah_id', $mk->id)
                            ->where('semester_id', $mk->semester_id)
                            ->where(function ($q) use ($filterKelas, $kelasPendek) {
                                $q->where('kelas', $filterKelas)
                                    ->orWhere('kelas', $kelasPendek)
                                    ->orWhereNull('kelas');
                            })
                            ->latest('updated_at')
                            ->first();

                        return [
                            'id' => $mhs->id,
                            'nim' => $mhs->nim,
                            'nama' => $mhs->nama,
                            'kelas' => $mhs->kelas ?? '-',

                            'nilai_tugas' => $nilaiRow->nilai_tugas ?? null,
                            'nilai_praktikum' => $nilaiRow->nilai_praktikum ?? null,
                            'nilai_uts' => $nilaiRow->nilai_uts ?? null,
                            'nilai_uas' => $nilaiRow->nilai_uas ?? null,
                            'nilai_kehadiran' => $nilaiRow->nilai_kehadiran ?? null,
                            'nilai_akhir' => $nilaiRow->nilai_akhir ?? null,
                            'grade' => $nilaiRow->nilai ?? null,
                            'status' => $nilaiRow->status ?? null,

                            'bobot_tugas' => $nilaiRow->bobot_tugas ?? 20,
                            'bobot_praktikum' => $nilaiRow->bobot_praktikum ?? 15,
                            'bobot_uts' => $nilaiRow->bobot_uts ?? 30,
                            'bobot_uas' => $nilaiRow->bobot_uas ?? 30,
                            'bobot_kehadiran' => $nilaiRow->bobot_kehadiran ?? 5,
                        ];
                    })->toArray();
                }
            }
        }

        return view('pages.dosen_matkul.input-nilai', compact(
            'mataKuliahList',
            'mahasiswaList',
            'mkAktif',
            'filterKodeMK',
            'filterKelas',
            'kelasDariMK',
            'isReadOnly',
            'allSem',
            'filterSemesterId',
            'filterTahunAjaran',
            'tahunAjaranList'
        ));
    }

    public function simpanNilai(Request $request)
    {
        $kodeMK = $request->input('kode_mk');
        $kelas = $request->input('kelas');
        $mahasiswaId = $request->input('mahasiswa_id');

        if (!$kodeMK || !$kelas || !$mahasiswaId) {
            return response()->json([
                'success' => false,
                'message' => 'Mata kuliah, kelas, dan mahasiswa wajib dipilih.',
            ], 422);
        }

        $nik = $this->dosenNik();
        $semesterId = $request->input('semester_id') ? (int) $request->input('semester_id') : null;

        $mk = $this->mataKuliahDiampuUntukKelas($kodeMK, $kelas, $semesterId);

        if (!$mk) {
            return response()->json([
                'success' => false,
                'message' => 'Mata kuliah atau kelas tidak ditemukan.',
            ], 404);
        }

        if (!(bool) $mk->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa menyimpan nilai di semester yang tidak aktif.',
            ], 403);
        }

        $bolehInput = $this->mahasiswaSudahKrsDisetujui($mahasiswaId, $mk->id, $mk->sem_id);

        if (!$bolehInput) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa ini belum memiliki KRS disetujui untuk mata kuliah tersebut.',
            ], 403);
        }

        $bobotTugas = (float) $request->input('bobot_tugas', 20);
        $bobotPraktikum = (float) $request->input('bobot_praktikum', 15);
        $bobotUts = (float) $request->input('bobot_uts', 30);
        $bobotUas = (float) $request->input('bobot_uas', 30);
        $bobotKehadiran = (float) $request->input('bobot_kehadiran', 5);

        foreach ([
            'Bobot tugas' => $bobotTugas,
            'Bobot praktikum' => $bobotPraktikum,
            'Bobot UTS' => $bobotUts,
            'Bobot UAS' => $bobotUas,
            'Bobot kehadiran' => $bobotKehadiran,
        ] as $label => $bobot) {
            if ($bobot < 0 || $bobot > 100) {
                return response()->json([
                    'success' => false,
                    'message' => "$label harus di antara 0 sampai 100.",
                ], 422);
            }
        }

        $totalBobot = $bobotTugas + $bobotPraktikum + $bobotUts + $bobotUas + $bobotKehadiran;

        if (abs($totalBobot - 100) > 0.01) {
            return response()->json([
                'success' => false,
                'message' => "Total bobot harus 100%. Sekarang: {$totalBobot}%",
            ], 422);
        }

        $nTugas = (float) $request->input("nilai_tugas.$mahasiswaId", 0);
        $nPraktikum = (float) $request->input("nilai_praktikum.$mahasiswaId", 0);
        $nUts = (float) $request->input("nilai_uts.$mahasiswaId", 0);
        $nUas = (float) $request->input("nilai_uas.$mahasiswaId", 0);
        $nKehadiran = (float) $request->input("nilai_kehadiran.$mahasiswaId", 0);

        foreach ([
            'Nilai tugas' => $nTugas,
            'Nilai praktikum' => $nPraktikum,
            'Nilai UTS' => $nUts,
            'Nilai UAS' => $nUas,
            'Nilai kehadiran' => $nKehadiran,
        ] as $label => $nilai) {
            if ($nilai < 0 || $nilai > 100) {
                return response()->json([
                    'success' => false,
                    'message' => "$label harus di antara 0 sampai 100.",
                ], 422);
            }
        }

        $nilaiAkhir = (
            ($nTugas * $bobotTugas) +
            ($nPraktikum * $bobotPraktikum) +
            ($nUts * $bobotUts) +
            ($nUas * $bobotUas) +
            ($nKehadiran * $bobotKehadiran)
        ) / 100;

        $konversi = self::konversiNilai($nilaiAkhir);

        $kelasFinal = $mk->kelas_final ?? $kelas;
        $kelasPendek = str_replace('-PAGI', '-', $kelasFinal);

        $data = $this->onlyExistingColumns('nilai', [
            'mahasiswa_id' => $mahasiswaId,
            'mata_kuliah_id' => $mk->id,
            'semester_id' => $mk->sem_id,
            'tahun_ajaran' => $mk->sem_tahun ?? '2025/2026',
            'semester' => $mk->semester_ke ?? 0,
            'kelas' => $kelasFinal,

            'nilai' => $konversi['grade'],
            'bobot' => $konversi['mutu'],
            'sks' => $mk->sks,

            'nilai_tugas' => $nTugas,
            'nilai_praktikum' => $nPraktikum,
            'nilai_uts' => $nUts,
            'nilai_uas' => $nUas,
            'nilai_kehadiran' => $nKehadiran,
            'nilai_akhir' => round($nilaiAkhir, 2),

            'bobot_tugas' => $bobotTugas,
            'bobot_praktikum' => $bobotPraktikum,
            'bobot_uts' => $bobotUts,
            'bobot_uas' => $bobotUas,
            'bobot_kehadiran' => $bobotKehadiran,

            'dosen_nik' => $nik,
            'status' => 'draft',
            'updated_at' => now(),
        ]);

        $existing = DB::table('nilai')
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('mata_kuliah_id', $mk->id)
            ->where('semester_id', $mk->sem_id)
            ->where(function ($q) use ($kelasFinal, $kelasPendek) {
                $q->where('kelas', $kelasFinal)
                    ->orWhere('kelas', $kelasPendek)
                    ->orWhereNull('kelas');
            })
            ->latest('id')
            ->first();

        if ($existing) {
            DB::table('nilai')
                ->where('id', $existing->id)
                ->update($data);
        } else {
            $data['created_at'] = now();

            DB::table('nilai')->insert($data);
        }

        $nama = DB::table('mahasiswa')
            ->where('id', $mahasiswaId)
            ->value('nama') ?? 'Mahasiswa';

        return response()->json([
            'success' => true,
            'message' => "Nilai $nama berhasil disimpan.",
            'nilai_akhir' => round($nilaiAkhir, 2),
            'grade' => $konversi['grade'],
            'mutu' => $konversi['mutu'],
        ]);
    }

    public function finalisasiNilai(Request $request)
    {
        $kodeMK = $request->input('kode_mk');
        $kelas = $request->input('kelas');

        $filterTahunAjaran = $request->input('tahun_ajaran', '');
        $filterSemesterId = $request->input('semester_id', '');
        $filterMK = $request->input('mata_kuliah_id', '');
        $filterKelas = $request->input('kelas', '');

        $mataKuliahDiampu = $allMataKuliahDiampu->filter(function ($mk) use ($filterTahunAjaran, $filterSemesterId) {
            if ($filterSemesterId && $mk->semester_id != $filterSemesterId) {
                return false;
            }

            if ($filterTahunAjaran && $mk->sem_tahun != $filterTahunAjaran) {
                return false;
            }

            return true;
        });

        $tahunAjaranList = $allMataKuliahDiampu
            ->pluck('sem_tahun')
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        $daftarMK = $mataKuliahDiampu
            ->map(function ($mk) {
                return [
                    'id' => $mk->id,
                    'kode_mk' => $mk->kode_mk,
                    'nama' => $mk->nama,
                    'kelas' => $mk->kelas ?? '-',
                ];
            })
            ->values()
            ->toArray();

        $selectedMK = null;

        if ($filterMK) {
            $selectedMK = $mataKuliahDiampu
                ->where('id', (int) $filterMK)
                ->first();
        }

        if ($selectedMK) {
            $mkIds = collect([$selectedMK->id]);

            $kelasDariMK = collect([$selectedMK->kelas])
                ->filter()
                ->unique()
                ->values()
                ->toArray();

            if (!$filterKelas && count($kelasDariMK) === 1) {
                $filterKelas = $kelasDariMK[0];
            }
        } else {
            $mkIds = $mataKuliahDiampu
                ->pluck('id')
                ->values();

            $kelasDariMK = $mataKuliahDiampu
                ->pluck('kelas')
                ->filter()
                ->unique()
                ->sort()
                ->values()
                ->toArray();
        }

        $nilaiTerbaru = DB::table('nilai')
            ->selectRaw('MAX(id) as latest_id, mahasiswa_id, mata_kuliah_id, semester_id')
            ->groupBy('mahasiswa_id', 'mata_kuliah_id', 'semester_id');

        $query = DB::table('krs_detail')
            ->join('krs_mahasiswa', 'krs_detail.krs_mahasiswa_id', '=', 'krs_mahasiswa.id')
            ->join('mahasiswa', 'krs_mahasiswa.mahasiswa_id', '=', 'mahasiswa.id')
            ->join('mata_kuliah', 'krs_detail.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->leftJoinSub($nilaiTerbaru, 'nilai_terbaru', function ($join) {
                $join->on('nilai_terbaru.mahasiswa_id', '=', 'mahasiswa.id')
                    ->on('nilai_terbaru.mata_kuliah_id', '=', 'mata_kuliah.id')
                    ->on('nilai_terbaru.semester_id', '=', 'krs_mahasiswa.semester_id');
            })
            ->leftJoin('nilai', 'nilai.id', '=', 'nilai_terbaru.latest_id')
            ->where('krs_mahasiswa.status', 'disetujui')
            ->whereIn('krs_detail.mata_kuliah_id', $mkIds)
            ->select(
                'mahasiswa.id as mahasiswa_id',
                'mahasiswa.nim',
                'mahasiswa.nama',
                'mahasiswa.kelas as kelas_mhs',

                'mata_kuliah.id as mata_kuliah_id',
                'mata_kuliah.kode_mk',
                'mata_kuliah.nama as nama_mk',
                'mata_kuliah.kelas as kelas_mk',

                'nilai.nilai as grade',
                'nilai.bobot as mutu',
                'nilai.nilai_akhir',
                'nilai.nilai_tugas',
                'nilai.nilai_praktikum',
                'nilai.nilai_uts',
                'nilai.nilai_uas',
                'nilai.nilai_kehadiran',
                'nilai.tahun_ajaran',
                'nilai.semester',
                'nilai.semester_id',
                'nilai.kelas as kelas_nilai'
            );

        if ($filterSemesterId) {
            $query->where('krs_mahasiswa.semester_id', $filterSemesterId);
        }

        if ($filterTahunAjaran) {
            $query->where('krs_mahasiswa.tahun_ajaran', $filterTahunAjaran);
        }

        if ($filterKelas) {
            $kelasPendek = str_replace('-PAGI', '-', $filterKelas);

            $query->where(function ($q) use ($filterKelas, $kelasPendek) {
                $q->where('mahasiswa.kelas', $filterKelas)
                    ->orWhere('mahasiswa.kelas', $kelasPendek)
                    ->orWhere('mata_kuliah.kelas', $filterKelas)
                    ->orWhere('mata_kuliah.kelas', $kelasPendek);
            });
        }

        $mahasiswa = $query
            ->orderBy('mahasiswa.nama')
            ->orderBy('mata_kuliah.kode_mk')
            ->get()
            ->unique(function ($item) {
                return $item->mahasiswa_id . '-' . $item->mata_kuliah_id;
            })
            ->values()
            ->map(function ($m, $i) {
                return [
                    'no' => $i + 1,
                    'mahasiswa_id' => $m->mahasiswa_id,
                    'nim' => $m->nim,
                    'nama' => $m->nama,
                    'kelas_mhs' => $m->kelas_mhs,
                    'kode_mk' => $m->kode_mk,
                    'nama_mk' => $m->nama_mk,

                    'nilai_tugas' => $m->nilai_tugas,
                    'nilai_praktikum' => $m->nilai_praktikum,
                    'nilai_uts' => $m->nilai_uts,
                    'nilai_uas' => $m->nilai_uas,
                    'nilai_kehadiran' => $m->nilai_kehadiran,
                    'nilai_akhir' => $m->nilai_akhir,
                    'grade' => $m->grade,
                    'mutu' => $m->mutu,
                ];
            })
            ->toArray();

        $stats = [
            'total_mahasiswa' => count($mahasiswa),
            'nilai_terinput' => collect($mahasiswa)->filter(function ($m) {
                return !is_null($m['grade']);
            })->count(),
        ];

        return view('pages.dosen_matkul.lihat-nilai', compact(
            'stats',
            'mahasiswa',
            'daftarMK',
            'allSem',
            'filterMK',
            'filterKelas',
            'filterSemesterId',
            'kelasDariMK',
            'filterTahunAjaran',
            'tahunAjaranList'
        ));
    }

    public function getKelasByMK(Request $request)
    {
        $mataKuliahId = $request->input('mata_kuliah_id');
        $kodeMK = $request->input('kode_mk');
        $filterTahunAjaran = $request->input('tahun_ajaran', '');
        $filterSemesterId = $request->input('semester_id', '');
        $semAktif = $this->semesterAktif();

        if (!$mataKuliahId && !$filterSemesterId && !$filterTahunAjaran) {
            $filterSemesterId = $semAktif ? $semAktif->id : '';
        }

        $dosenId = $this->dosenId();
        $nik = $this->dosenNik();

        $query = DB::table('mata_kuliah')
            ->join('semesters', 'mata_kuliah.semester_id', '=', 'semesters.id')
            ->leftJoin('dosen_matakuliah', 'mata_kuliah.id', '=', 'dosen_matakuliah.mata_kuliah_id')
            ->leftJoin('dosen', 'dosen_matakuliah.dosen_id', '=', 'dosen.id')
            ->leftJoin('kelas', 'dosen_matakuliah.kelas_id', '=', 'kelas.id')
            ->where(function ($q) use ($mataKuliahId, $kodeMK) {
                if ($mataKuliahId) {
                    $q->where('mata_kuliah.id', $mataKuliahId);
                } elseif ($kodeMK) {
                    $q->where('mata_kuliah.kode_mk', $kodeMK);
                }
            })
            ->where(function ($query) use ($dosenId, $nik) {
                if ($dosenId) {
                    $query->where('dosen_matakuliah.dosen_id', $dosenId)
                        ->orWhere('mata_kuliah.dosen_id', $dosenId);
                }

                if ($nik) {
                    $query->orWhere('dosen.nik', $nik)
                        ->orWhere('mata_kuliah.dosen_nik', $nik);
                }
            })
            ->when($filterSemesterId, function ($q) use ($filterSemesterId) {
                $q->where('mata_kuliah.semester_id', $filterSemesterId);
            })
            ->when($filterTahunAjaran, function ($q) use ($filterTahunAjaran) {
                $q->where('semesters.tahun_ajaran', $filterTahunAjaran);
            });

        $mkIds = (clone $query)
            ->distinct()
            ->pluck('mata_kuliah.id');

        $kelasList = $query
            ->selectRaw('COALESCE(kelas.nama_kelas, mata_kuliah.kelas) as nama_kelas')
            ->whereNotNull(DB::raw('COALESCE(kelas.nama_kelas, mata_kuliah.kelas)'))
            ->distinct()
            ->pluck('nama_kelas')
            ->merge($this->kelasDariKrsUntukMkIds($mkIds))
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $isAnyActive = DB::table('mata_kuliah')
            ->join('semesters', 'mata_kuliah.semester_id', '=', 'semesters.id')
            ->when($mataKuliahId, function ($q) use ($mataKuliahId) {
                $q->where('mata_kuliah.id', $mataKuliahId);
            })
            ->when(!$mataKuliahId && $kodeMK, function ($q) use ($kodeMK) {
                $q->where('mata_kuliah.kode_mk', $kodeMK);
            })
            ->when($filterSemesterId, function ($q) use ($filterSemesterId) {
                $q->where('mata_kuliah.semester_id', $filterSemesterId);
            })
            ->when($filterTahunAjaran, function ($q) use ($filterTahunAjaran) {
                $q->where('semesters.tahun_ajaran', $filterTahunAjaran);
            })
            ->where('semesters.is_active', 1)
            ->exists();

        return response()->json([
            'kelas' => $kelasList,
            'is_active' => $isAnyActive,
        ]);
    }

    public function profil()
    {
        $dosenId = $this->dosenId();
        $nik = $this->dosenNik();

        $dbDosen = null;

        if ($dosenId) {
            $dbDosen = DB::table('dosen')->where('id', $dosenId)->first();
        } elseif ($nik) {
            $dbDosen = DB::table('dosen')->where('nik', $nik)->first();
        }

        $sess = session('user', []);

        $dosen = [
            'nama' => $dbDosen->nama ?? $sess['name'] ?? 'Dosen Mata Kuliah',
            'nip' => $dbDosen->nip ?? '-',
            'nidn' => $dbDosen->nik ?? $nik ?? '-',
            'email' => $dbDosen->email ?? $sess['email'] ?? '-',
            'no_hp' => $dbDosen->no_hp ?? '-',
            'alamat' => $dbDosen->alamat ?? 'Kota Batam',
            'program_studi' => 'Teknik Informatika',
        ];

        return view('pages.dosen_matkul.profil', compact('dosen'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);

        $dosenId = $this->dosenId();
        $nik = $this->dosenNik();

        $dbDosen = $dosenId
            ? DB::table('dosen')->where('id', $dosenId)->first()
            : DB::table('dosen')->where('nik', $nik)->first();

        if ($dbDosen) {
            DB::table('dosen')
                ->where('id', $dbDosen->id)
                ->update([
                    'nama' => $validated['nama'],
                    'email' => $validated['email'],
                    'no_hp' => $validated['no_hp'] ?? null,
                    'alamat' => $validated['alamat'] ?? null,
                    'updated_at' => now(),
                ]);

            if ($dbDosen->user_id) {
                DB::table('users')
                    ->where('id', $dbDosen->user_id)
                    ->update([
                        'name' => $validated['nama'],
                        'email' => $validated['email'],
                        'updated_at' => now(),
                    ]);
            }
        }

        $user = session('user', []);
        $user['name'] = $validated['nama'];
        $user['email'] = $validated['email'];

        session([
            'user' => $user,
            'user_name' => $validated['nama'],
        ]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required|string',
            'password_baru' => 'required|string|min:6',
            'password_baru_confirmation' => 'required|same:password_baru',
        ], [
            'password_baru_confirmation.same' => 'Konfirmasi password tidak cocok.',
        ]);

        $dosenId = $this->dosenId();
        $nik = $this->dosenNik();

        $dbDosen = $dosenId
            ? DB::table('dosen')->where('id', $dosenId)->first()
            : DB::table('dosen')->where('nik', $nik)->first();

        if (!$dbDosen || !$dbDosen->user_id) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $userDb = DB::table('users')->where('id', $dbDosen->user_id)->first();

        $valid = PasswordVerifier::check($request->password_lama, $userDb->password);

        if (!$valid) {
            return redirect()->back()->withErrors([
                'password_lama' => 'Password lama tidak sesuai.',
            ]);
        }

        DB::table('users')
            ->where('id', $dbDosen->user_id)
            ->update([
                'password' => Hash::make($request->password_baru),
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Password berhasil diubah.');
    }
}
