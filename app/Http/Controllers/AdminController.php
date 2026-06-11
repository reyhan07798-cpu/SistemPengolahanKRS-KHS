<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\User;
use App\Models\MataKuliah;
use App\Models\MahasiswaSemester;
use App\Models\TahunAjaran;
use App\Models\PaketMataKuliah;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    // ==========================================
    // 1. DASHBOARD ADMIN
    // ==========================================
    public function dashboardAdmin()
    {
        $totalMahasiswa = Schema::hasTable('mahasiswa')
            ? DB::table('mahasiswa')
                ->when(
                    Schema::hasColumn('mahasiswa', 'deleted_at'),
                    fn($query) => $query->whereNull('deleted_at')
                )
                ->count()
            : 0;

        $totalDosen = Schema::hasTable('dosen')
            ? DB::table('dosen')
                ->when(
                    Schema::hasColumn('dosen', 'deleted_at'),
                    fn($query) => $query->whereNull('deleted_at')
                )
                ->count()
            : 0;

        $totalMataKuliah = Schema::hasTable('mata_kuliah')
            ? DB::table('mata_kuliah')
                ->when(
                    Schema::hasColumn('mata_kuliah', 'deleted_at'),
                    fn($query) => $query->whereNull('deleted_at')
                )
                ->count()
            : 0;

        $prodis = $this->getProdiOptions();

        $mahasiswa = Schema::hasTable('mahasiswa')
            ? $this->dashboardMahasiswaRankingQuery()
                ->get()
                ->map(function ($mahasiswa) {
                    $mahasiswa->ipk = (float) $mahasiswa->ipk;

                    return $mahasiswa;
                })
            : collect();

        $mahasiswaDenganNilai = $mahasiswa->where('ipk', '>', 0);
        $avgIpk = $mahasiswaDenganNilai->count() > 0
            ? $mahasiswaDenganNilai->avg('ipk')
            : 0;

        $angkatans = $mahasiswa->pluck('angkatan')->unique()->sortDesc()->values();

        return view('pages.admin.dashboard_admin', compact(
            'mahasiswa', 'totalMahasiswa', 'totalDosen', 'totalMataKuliah', 'avgIpk', 'prodis', 'angkatans'
        ));
    }

    // ==========================================
    // 2. MAHASISWA CRUD
    // ==========================================

    private function dashboardMahasiswaRankingQuery()
    {
        $query = DB::table('mahasiswa')
            ->select([
                'mahasiswa.id',
                'mahasiswa.nim',
                'mahasiswa.nama',
                'mahasiswa.kelas',
                'mahasiswa.angkatan',
            ])
            ->when(
                Schema::hasColumn('mahasiswa', 'deleted_at'),
                fn($query) => $query->whereNull('mahasiswa.deleted_at')
            );

        if (Schema::hasTable('prodi') && Schema::hasColumn('mahasiswa', 'prodi_id')) {
            $query->leftJoin('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
                ->addSelect(DB::raw("COALESCE(prodi.nama_prodi, '-') as prodi"));
        } else {
            $query->addSelect(DB::raw("'-' as prodi"));
        }

        if (Schema::hasTable('nilai')) {
            $query->leftJoin('nilai', 'mahasiswa.id', '=', 'nilai.mahasiswa_id')
                ->addSelect(DB::raw('COALESCE(ROUND(SUM(COALESCE(nilai.bobot, 0) * COALESCE(nilai.sks, 0)) / NULLIF(SUM(COALESCE(nilai.sks, 0)), 0), 2), 0) as ipk'))
                ->groupBy(
                    'mahasiswa.id',
                    'mahasiswa.nim',
                    'mahasiswa.nama',
                    'mahasiswa.kelas',
                    'mahasiswa.angkatan',
                    'prodi.nama_prodi'
                );
        } else {
            $query->addSelect(DB::raw('0 as ipk'));

            if (Schema::hasTable('prodi') && Schema::hasColumn('mahasiswa', 'prodi_id')) {
                $query->groupBy(
                    'mahasiswa.id',
                    'mahasiswa.nim',
                    'mahasiswa.nama',
                    'mahasiswa.kelas',
                    'mahasiswa.angkatan',
                    'prodi.nama_prodi'
                );
            }
        }

        return $query
            ->orderByDesc('ipk')
            ->orderBy('mahasiswa.nama');
    }

    public function indexMahasiswa()
    {
        $mahasiswa = Schema::hasTable('mahasiswa')
            ? $this->mahasiswaListQuery()->get()
            : collect();
        $prodis = $this->getProdiOptions();
        $angkatans = $this->getAngkatanOptions();
        $dosens = $this->getDosenWaliOptions();

        return view('pages.admin.data_mahasiswa', compact('mahasiswa', 'prodis', 'angkatans', 'dosens'));
    }

    public function createMahasiswa()
    {
        $prodis = $this->getProdiOptions();
        $angkatans = $this->getAngkatanOptions();
        $dosens = $this->getDosenWaliOptions();

        return view('pages.admin.mahasiswa_create', compact('prodis', 'angkatans', 'dosens'));
    }

    public function storeMahasiswa(Request $request)
    {
        $validated = $request->validate([
            'nim' => [
                'required',
                'string',
                'max:20',
                Rule::unique('mahasiswa', 'nim'),
                Rule::unique('users', 'nim'),
            ],
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('mahasiswa', 'email'),
                Rule::unique('users', 'email'),
            ],
            'prodi' => 'required|string|max:100',
            'angkatan' => 'required|integer|min:2000|max:2100',
            'kelas' => 'required|string|max:20',
            'dosen_wali_id' => 'nullable|exists:dosen,id',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'password' => 'required|string|min:4',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $user = User::create($this->mahasiswaUserData($validated));

                $mahasiswa = Mahasiswa::create([
                    'user_id' => $user->id,
                    'dosen_wali_id' => $validated['dosen_wali_id'] ?? null,
                    'prodi_id' => $this->resolveProdiId($validated['prodi']),
                    'nim' => $validated['nim'],
                    'nama' => $validated['nama'],
                    'email' => $validated['email'],
                    'no_hp' => $validated['no_hp'] ?? null,
                    'alamat' => $validated['alamat'] ?? null,
                    'angkatan' => $validated['angkatan'],
                    'kelas' => $validated['kelas'],
                ]);

                $activeSemester = DB::table('semesters')->where('is_active', true)->first();
                if ($activeSemester && Schema::hasTable('mahasiswa_semester')) {
                    MahasiswaSemester::create([
                        'mahasiswa_id' => $mahasiswa->id,
                        'semester_id' => $activeSemester->id,
                        'semester_ke' => $activeSemester->semester_ke ?? 1,
                        'status' => 'aktif',
                    ]);
                }
            });

            return redirect()->route('pages.admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil ditambahkan! Akun login mahasiswa sudah dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    public function editMahasiswa($id)
    {
        $mahasiswa = Mahasiswa::with(['prodi', 'dosenWali'])->findOrFail($id);
        $prodis = $this->getProdiOptions();
        $angkatans = $this->getAngkatanOptions();
        $dosens = $this->getDosenWaliOptions();

        return view('pages.admin.mahasiswa_edit', compact('mahasiswa', 'prodis', 'angkatans', 'dosens'));
    }

    public function updateMahasiswa(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $validated = $request->validate([
            'nim' => [
                'required',
                'string',
                'max:20',
                Rule::unique('mahasiswa', 'nim')->ignore($mahasiswa->id),
                Rule::unique('users', 'nim')->ignore($mahasiswa->user_id),
            ],
            'nama' => 'required|string|max:255',
            'prodi' => 'required|string|max:100',
            'kelas' => 'required|string|max:20',
            'angkatan' => 'required|integer|min:2000|max:2100',
            'dosen_wali_id' => 'nullable|exists:dosen,id',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('mahasiswa', 'email')->ignore($mahasiswa->id),
                Rule::unique('users', 'email')->ignore($mahasiswa->user_id),
            ],
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'password' => 'nullable|string|min:4',
        ]);

        try {
            DB::transaction(function () use ($mahasiswa, $validated, $request) {
                $mahasiswa->update([
                    'dosen_wali_id' => $validated['dosen_wali_id'] ?? null,
                    'prodi_id' => $this->resolveProdiId($validated['prodi']),
                    'nim' => $validated['nim'],
                    'nama' => $validated['nama'],
                    'email' => $validated['email'],
                    'no_hp' => $validated['no_hp'] ?? null,
                    'alamat' => $validated['alamat'] ?? null,
                    'angkatan' => $validated['angkatan'],
                    'kelas' => $validated['kelas'],
                ]);

                $user = $mahasiswa->user;
                if ($user) {
                    $userData = $this->mahasiswaUserData($validated, false);

                    if ($request->filled('password')) {
                        $userData['password'] = Hash::make($validated['password']);
                    }

                    $user->update($userData);
                }
            });

            return redirect()->route('pages.admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage())->withInput();
        }
    }

    public function destroyMahasiswa($id)
    {
        try {
            if (Schema::hasTable('mahasiswa')) {
                if (!Schema::hasColumn('mahasiswa', 'deleted_at')) {
                    return redirect()
                        ->route('pages.admin.mahasiswa.index')
                        ->with('error', 'Kolom soft delete belum tersedia. Jalankan migration terlebih dahulu.');
                }

                $mahasiswa = Mahasiswa::findOrFail($id);
                $user = $mahasiswa->user;

                DB::transaction(function () use ($mahasiswa, $user) {
                    $mahasiswa->delete();

                    if ($user && Schema::hasColumn('users', 'deleted_at')) {
                        $user->delete();
                    }
                });
            }
            return redirect()->route('pages.admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus dari tampilan admin!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    private function mahasiswaListQuery()
    {
        $query = DB::table('mahasiswa')
            ->select([
                'mahasiswa.id',
                'mahasiswa.nim',
                'mahasiswa.nama',
                'mahasiswa.email',
                'mahasiswa.no_hp',
                'mahasiswa.alamat',
                'mahasiswa.angkatan',
                'mahasiswa.kelas',
                'mahasiswa.created_at',
            ])
            ->when(
                Schema::hasColumn('mahasiswa', 'deleted_at'),
                fn($query) => $query->whereNull('mahasiswa.deleted_at')
            );

        if (Schema::hasTable('prodi') && Schema::hasColumn('mahasiswa', 'prodi_id')) {
            $query->leftJoin('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
                ->addSelect('prodi.nama_prodi as prodi');
        } else {
            $query->addSelect(DB::raw('NULL as prodi'));
        }

        if (Schema::hasTable('dosen') && Schema::hasColumn('mahasiswa', 'dosen_wali_id')) {
            $query->leftJoin('dosen', 'mahasiswa.dosen_wali_id', '=', 'dosen.id')
                ->addSelect('dosen.nama as dosen_wali');
        } else {
            $query->addSelect(DB::raw('NULL as dosen_wali'));
        }

        return $query->orderBy('mahasiswa.created_at', 'desc');
    }

    private function getProdiOptions()
    {
        $fallback = collect([
            'Teknik Informatika',
            'Sistem Informasi',
            'Teknologi Rekayasa Multimedia',
            'Rekayasa Keamanan Siber',
            'Rekayasa Perangkat Lunak',
            'Teknologi Permainan',
            'Animasi',
        ]);

        if (!Schema::hasTable('prodi')) {
            return $fallback;
        }

        $prodis = Prodi::orderBy('nama_prodi')->pluck('nama_prodi')->filter()->values();

        return $prodis->isEmpty() ? $fallback : $prodis;
    }

    private function getKelasOptions()
    {
        if (!Schema::hasTable('mahasiswa')) {
            return collect();
        }

        return DB::table('mahasiswa')
            ->when(
                Schema::hasColumn('mahasiswa', 'deleted_at'),
                fn($query) => $query->whereNull('deleted_at')
            )
            ->whereNotNull('kelas')
            ->where('kelas', '!=', '')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas')
            ->filter()
            ->values();
    }

    private function getAngkatanOptions()
    {
        $tahunSekarang = (int) date('Y');
        $fallback = collect(range($tahunSekarang, $tahunSekarang - 5));

        if (!Schema::hasTable('mahasiswa')) {
            return $fallback;
        }

        $angkatans = Mahasiswa::whereNotNull('angkatan')
            ->distinct()
            ->pluck('angkatan')
            ->sortDesc()
            ->values();

        return $angkatans->isEmpty() ? $fallback : $angkatans;
    }

    private function getDosenWaliOptions()
    {
        if (!Schema::hasTable('dosen')) {
            return collect();
        }

        return Dosen::orderBy('nama', 'asc')->get(['id', 'nama']);
    }

    private function resolveProdiId(string $namaProdi): ?int
    {
        if (!Schema::hasTable('prodi')) {
            return null;
        }

        $prodi = Prodi::where('nama_prodi', $namaProdi)->first();
        if ($prodi) {
            return $prodi->id;
        }

        return Prodi::create([
            'kode_prodi' => $this->makeUniqueProdiCode($namaProdi),
            'nama_prodi' => $namaProdi,
        ])->id;
    }

    private function makeUniqueProdiCode(string $namaProdi): string
    {
        $base = strtoupper(collect(explode(' ', $namaProdi))
            ->filter()
            ->map(fn($word) => substr($word, 0, 1))
            ->implode(''));
        $base = substr($base ?: 'PRD', 0, 7);
        $kode = $base;
        $counter = 1;

        while (Prodi::where('kode_prodi', $kode)->exists()) {
            $kode = substr($base, 0, 7) . $counter;
            $counter++;
        }

        return substr($kode, 0, 10);
    }

    private function mahasiswaUserData(array $data, bool $includePassword = true): array
    {
        $userData = [
            'name' => $data['nama'],
            'email' => $data['email'],
        ];

        if ($includePassword) {
            $userData['password'] = Hash::make($data['password']);
        }

        if (Schema::hasColumn('users', 'nim')) {
            $userData['nim'] = $data['nim'];
        }

        if (Schema::hasColumn('users', 'role')) {
            $userData['role'] = 'mahasiswa';
        }

        if (Schema::hasColumn('users', 'username')) {
            $userData['username'] = $data['nim'];
        }

        return $userData;
    }

    // ==========================================
    // 2B. SEMESTER MAHASISWA
    // ==========================================

    public function indexSemesterMahasiswa(Request $request)
    {
        $semesters = DB::table('semesters')
            ->orderByDesc('tahun_ajaran')
            ->orderByDesc('semester_ke')
            ->get();

        $activeSemester = $semesters->firstWhere('is_active', 1) ?? $semesters->first();
        $selectedSemesterId = (int) ($request->input('semester_id') ?: ($activeSemester->id ?? 0));
        $selectedSemester = $semesters->firstWhere('id', $selectedSemesterId) ?? $activeSemester;
        $selectedProdi = $request->input('prodi', '');
        $selectedKelas = $request->input('kelas', '');
        $prodis = $this->getProdiOptions();
        $kelasList = $this->getKelasOptions();

        $mahasiswa = collect();

        if ($selectedSemester && Schema::hasTable('mahasiswa_semester')) {
            $mahasiswa = DB::table('mahasiswa')
                ->leftJoin('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
                ->leftJoin('mahasiswa_semester', function ($join) use ($selectedSemester) {
                    $join->on('mahasiswa.id', '=', 'mahasiswa_semester.mahasiswa_id')
                        ->where('mahasiswa_semester.semester_id', $selectedSemester->id);
                })
                ->select(
                    'mahasiswa.id',
                    'mahasiswa.nim',
                    'mahasiswa.nama',
                    'mahasiswa.kelas',
                    'mahasiswa.angkatan',
                    DB::raw("COALESCE(prodi.nama_prodi, '-') as prodi"),
                    'mahasiswa_semester.id as progress_id',
                    'mahasiswa_semester.semester_ke',
                    'mahasiswa_semester.status',
                    'mahasiswa_semester.catatan'
                )
                ->when(
                    Schema::hasColumn('mahasiswa', 'deleted_at'),
                    fn($query) => $query->whereNull('mahasiswa.deleted_at')
                )
                ->when($selectedProdi !== '', function ($query) use ($selectedProdi) {
                    $query->where('prodi.nama_prodi', $selectedProdi);
                })
                ->when($selectedKelas !== '', function ($query) use ($selectedKelas) {
                    $query->where('mahasiswa.kelas', $selectedKelas);
                })
                ->orderBy('mahasiswa.kelas')
                ->orderBy('mahasiswa.nama')
                ->get();
        }

        $stats = [
            'total' => $mahasiswa->count(),
            'aktif' => $mahasiswa->where('status', 'aktif')->count(),
            'cuti' => $mahasiswa->where('status', 'cuti')->count(),
            'mengulang' => $mahasiswa->where('status', 'mengulang')->count(),
            'belum_diatur' => $mahasiswa->whereNull('progress_id')->count(),
        ];

        return view('pages.admin.semester_mahasiswa', compact(
            'semesters',
            'selectedSemester',
            'selectedSemesterId',
            'selectedProdi',
            'selectedKelas',
            'prodis',
            'kelasList',
            'mahasiswa',
            'stats'
        ));
    }

    public function updateSemesterMahasiswa(Request $request, $mahasiswaId)
    {
        $validated = $request->validate([
            'semester_id' => 'required|exists:semesters,id',
            'semester_ke' => 'required|integer|min:1|max:14',
            'status' => ['required', Rule::in(['aktif', 'cuti', 'mengulang', 'lulus', 'nonaktif'])],
            'catatan' => 'nullable|string|max:500',
            'prodi' => 'nullable|string|max:100',
            'kelas' => 'nullable|string|max:50',
        ]);

        Mahasiswa::findOrFail($mahasiswaId);

        MahasiswaSemester::updateOrCreate(
            [
                'mahasiswa_id' => $mahasiswaId,
                'semester_id' => $validated['semester_id'],
            ],
            [
                'semester_ke' => $validated['semester_ke'],
                'status' => $validated['status'],
                'catatan' => $validated['catatan'] ?? null,
            ]
        );

        return redirect()
            ->route('pages.admin.semester-mahasiswa.index', [
                'semester_id' => $validated['semester_id'],
                'prodi' => $validated['prodi'] ?? null,
                'kelas' => $validated['kelas'] ?? null,
            ])
            ->with('success', 'Status semester mahasiswa berhasil diperbarui.');
    }

    public function promoteSemesterMahasiswa(Request $request)
    {
        $validated = $request->validate([
            'from_semester_id' => 'required|exists:semesters,id|different:to_semester_id',
            'to_semester_id' => 'required|exists:semesters,id',
            'prodi' => 'nullable|string|max:100',
            'kelas' => 'nullable|string|max:50',
        ]);

        $targetSemesterId = (int) $validated['to_semester_id'];
        $sourceRecords = DB::table('mahasiswa_semester')
            ->join('mahasiswa', 'mahasiswa_semester.mahasiswa_id', '=', 'mahasiswa.id')
            ->leftJoin('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
            ->select('mahasiswa_semester.*')
            ->where('semester_id', $validated['from_semester_id'])
            ->where('status', 'aktif')
            ->when(!empty($validated['prodi']), function ($query) use ($validated) {
                $query->where('prodi.nama_prodi', $validated['prodi']);
            })
            ->when(!empty($validated['kelas']), function ($query) use ($validated) {
                $query->where('mahasiswa.kelas', $validated['kelas']);
            })
            ->get();

        $created = 0;
        $skipped = 0;

        DB::transaction(function () use ($sourceRecords, $targetSemesterId, &$created, &$skipped) {
            foreach ($sourceRecords as $record) {
                $exists = DB::table('mahasiswa_semester')
                    ->where('mahasiswa_id', $record->mahasiswa_id)
                    ->where('semester_id', $targetSemesterId)
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                DB::table('mahasiswa_semester')->insert([
                    'mahasiswa_id' => $record->mahasiswa_id,
                    'semester_id' => $targetSemesterId,
                    'semester_ke' => min(14, ((int) $record->semester_ke) + 1),
                    'status' => 'aktif',
                    'catatan' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $created++;
            }
        });

        return redirect()
            ->route('pages.admin.semester-mahasiswa.index', [
                'semester_id' => $targetSemesterId,
                'prodi' => $validated['prodi'] ?? null,
                'kelas' => $validated['kelas'] ?? null,
            ])
            ->with('success', "Naik semester selesai. {$created} mahasiswa dibuat, {$skipped} dilewati karena sudah ada di semester tujuan.");
    }

   // ==========================================
// 3. DOSEN CRUD
// ==========================================

public function indexDosen()
{
    $prodis = collect([
        'Teknik Informatika',
        'Sistem Informasi',
        'Teknologi Rekayasa Multimedia',
        'Rekayasa Keamanan Siber',
        'Rekayasa Perangkat Lunak',
        'Teknologi Permainan',
        'Animasi',
    ]);

    $dosen = Schema::hasTable('dosen')
        ? Dosen::orderBy('created_at', 'desc')->get()
        : collect();

    return view('pages.admin.data_dosen', compact('dosen', 'prodis'));
}

public function createDosen()
{
    $prodis = collect([
        'Teknik Informatika',
        'Sistem Informasi',
        'Teknologi Rekayasa Multimedia',
        'Rekayasa Keamanan Siber',
        'Rekayasa Perangkat Lunak',
        'Teknologi Permainan',
        'Animasi',
    ]);

    return view('pages.admin.dosen_create', compact('prodis'));
}

public function storeDosen(Request $request)
{
    $validated = $request->validate([
        'nik' => 'required|string|max:30|unique:dosen,nik',
        'nip' => 'nullable|string|max:30|unique:dosen,nip',
        'nama' => 'required|string|max:100',
        'email' => 'required|email|max:100|unique:dosen,email|unique:users,email',
        'tipe_dosen' => 'required|string',
        'fakultas' => 'required|string|max:100',
        'alamat' => 'nullable|string',
        'no_hp' => 'nullable|string|max:20',
        'password' => 'required|string|min:4',
    ]);

    try {
        $user = User::create([
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if (Schema::hasColumn('users', 'nik')) {
            $user->update(['nik' => $validated['nik']]);
        }

        if (Schema::hasColumn('users', 'role')) {
            $role = 'dosen_mk';
            $tipe = strtolower($validated['tipe_dosen']);

            if (
                $tipe === 'keduanya' ||
                str_contains($tipe, 'wali') &&
                (str_contains($tipe, 'mata kuliah') || str_contains($tipe, 'mk') || str_contains($tipe, 'keduanya'))
            ) {
                $role = 'dosen';
            } elseif (str_contains($tipe, 'wali')) {
                $role = 'dosen_wali';
            }

            $user->update(['role' => $role]);
        }

        Dosen::create([
            'user_id' => $user->id,
            'nik' => $validated['nik'],
            'nip' => $validated['nip'] ?? null,
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'tipe_dosen' => $validated['tipe_dosen'],
            'fakultas' => $validated['fakultas'],
        ]);

        return redirect()
            ->route('pages.admin.dosen.index')
            ->with('success', 'Data dosen berhasil ditambahkan! Akun login sudah siap digunakan.');
    } catch (\Exception $e) {
        return redirect()
            ->back()
            ->with('error', 'Gagal menyimpan data: ' . $e->getMessage())
            ->withInput();
    }
}

public function editDosen($id)
{
    $dosen = Dosen::findOrFail($id);

    $prodis = collect([
        'Teknik Informatika',
        'Sistem Informasi',
        'Teknologi Rekayasa Multimedia',
        'Rekayasa Keamanan Siber',
        'Rekayasa Perangkat Lunak',
        'Teknologi Permainan',
        'Animasi',
    ]);

    return view('pages.admin.dosen_edit', compact('dosen', 'prodis'));
}

public function updateDosen(Request $request, $id)
{
    $dosen = Dosen::findOrFail($id);

    $validated = $request->validate([
        'nik' => 'required|string|max:30|unique:dosen,nik,' . $id,
        'nip' => 'nullable|string|max:30|unique:dosen,nip,' . $id,
        'nama' => 'required|string|max:100',
        'email' => [
            'required',
            'email',
            'max:100',
            Rule::unique('dosen', 'email')->ignore($id),
            Rule::unique('users', 'email')->ignore($dosen->user_id),
        ],
        'tipe_dosen' => 'required|string',
        'fakultas' => 'required|string|max:100',
        'alamat' => 'nullable|string',
        'no_hp' => 'nullable|string|max:20',
        'password' => 'nullable|string|min:4',
    ]);

    try {
        if ($dosen->user_id) {
            $user = User::find($dosen->user_id);

            if ($user) {
                $updateUser = [
                    'name' => $validated['nama'],
                    'email' => $validated['email'],
                ];

                if ($request->filled('password')) {
                    $updateUser['password'] = Hash::make($validated['password']);
                }

                if (Schema::hasColumn('users', 'nik')) {
                    $updateUser['nik'] = $validated['nik'];
                }

                if (Schema::hasColumn('users', 'role')) {
                    $role = 'dosen_mk';
                    $tipe = strtolower($validated['tipe_dosen']);

                    if (
                        $tipe === 'keduanya' ||
                        str_contains($tipe, 'wali') &&
                        (str_contains($tipe, 'mata kuliah') || str_contains($tipe, 'mk') || str_contains($tipe, 'keduanya'))
                    ) {
                        $role = 'dosen';
                    } elseif (str_contains($tipe, 'wali')) {
                        $role = 'dosen_wali';
                    }

                    $updateUser['role'] = $role;
                }

                $user->update($updateUser);
            }
        }

        $dosen->update([
            'nik' => $validated['nik'],
            'nip' => $validated['nip'] ?? null,
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'tipe_dosen' => $validated['tipe_dosen'],
            'fakultas' => $validated['fakultas'],
        ]);

        return redirect()
            ->route('pages.admin.dosen.index')
            ->with('success', 'Data dosen berhasil diupdate!');
    } catch (\Exception $e) {
        return redirect()
            ->back()
            ->with('error', 'Gagal update data: ' . $e->getMessage())
            ->withInput();
    }
}

public function destroyDosen($id)
{
    $successMessage = 'Data dosen berhasil dihapus dari tampilan admin.';

    try {
        if (!Schema::hasTable('dosen')) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Tabel dosen tidak ditemukan.'], 404);
            }

            return redirect()
                ->route('pages.admin.dosen.index')
                ->with('error', 'Tabel dosen tidak ditemukan.');
        }

        if (!Schema::hasColumn('dosen', 'deleted_at')) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Kolom soft delete belum tersedia. Jalankan migration terlebih dahulu.'], 500);
            }

            return redirect()
                ->route('pages.admin.dosen.index')
                ->with('error', 'Kolom soft delete belum tersedia. Jalankan migration terlebih dahulu.');
        }

        $dosen = Dosen::findOrFail($id);

        DB::transaction(function () use ($dosen) {
            $user = $dosen->user_id ? User::find($dosen->user_id) : null;

            $dosen->delete();

            if ($user && Schema::hasColumn('users', 'deleted_at')) {
                $user->delete();
            }
        });

        if (request()->expectsJson()) {
            return response()->json(['message' => $successMessage]);
        }

        return redirect()
            ->route('pages.admin.dosen.index')
            ->with('success', $successMessage);
    } catch (\Exception $e) {
        if (request()->expectsJson()) {
            return response()->json(['message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }

        return redirect()
            ->route('pages.admin.dosen.index')
            ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
    }
}

// ==========================================
// 4. MATA KULIAH CRUD
// ==========================================

public function indexMatakuliah()
{
    $dosens = Schema::hasTable('dosen')
        ? DB::table('dosen')
            ->when(
                Schema::hasColumn('dosen', 'deleted_at'),
                fn($query) => $query->whereNull('deleted_at')
            )
            ->orderBy('nama', 'asc')
            ->get()
        : collect();

    $semesters = collect(['1', '2', '3', '4', '5', '6', '7', '8']);

    $matakuliah = Schema::hasTable('mata_kuliah')
        ? DB::table('mata_kuliah')
            ->leftJoin('dosen', 'mata_kuliah.dosen_id', '=', 'dosen.id')
            ->select(
                'mata_kuliah.id',
                'mata_kuliah.kode_mk',
                'mata_kuliah.nama',
                'mata_kuliah.sks',
                'mata_kuliah.semester_ke',
                'mata_kuliah.dosen_id',
                'mata_kuliah.created_at',
                'mata_kuliah.updated_at',
                'dosen.nama as dosen_pengampu'
            )
            ->when(
                Schema::hasColumn('mata_kuliah', 'deleted_at'),
                fn($query) => $query->whereNull('mata_kuliah.deleted_at')
            )
            ->orderBy('mata_kuliah.created_at', 'desc')
            ->get()
        : collect();

    return view('pages.admin.data_matakuliah', compact(
        'matakuliah',
        'dosens',
        'semesters'
    ));
}

public function createMatakuliah()
{
    $dosens = Schema::hasTable('dosen')
        ? DB::table('dosen')
            ->when(
                Schema::hasColumn('dosen', 'deleted_at'),
                fn($query) => $query->whereNull('deleted_at')
            )
            ->orderBy('nama', 'asc')
            ->get()
        : collect();

    $semesters = collect(['1', '2', '3', '4', '5', '6', '7', '8']);

    return view('pages.admin.matakuliah_create', compact(
        'dosens',
        'semesters'
    ));
}

public function storeMatakuliah(Request $request)
{
    $validated = $request->validate([
        'kode_mk' => 'required|string|max:30|unique:mata_kuliah,kode_mk',
        'nama' => 'required|string|max:100',
        'sks' => 'required|integer|min:1|max:6',
        'semester_ke' => 'required|integer|min:1|max:8',
        'dosen_id' => 'nullable|exists:dosen,id',
    ], [
        'kode_mk.required' => 'Kode mata kuliah wajib diisi.',
        'kode_mk.unique' => 'Kode mata kuliah sudah digunakan.',
        'nama.required' => 'Nama mata kuliah wajib diisi.',
        'sks.required' => 'SKS wajib diisi.',
        'semester_ke.required' => 'Semester wajib dipilih.',
    ]);

    try {
        DB::table('mata_kuliah')->insert([
            'dosen_id' => $validated['dosen_id'] ?? null,
            'kode_mk' => $validated['kode_mk'],
            'nama' => $validated['nama'],
            'sks' => $validated['sks'],
            'semester_ke' => $validated['semester_ke'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('pages.admin.matakuliah.index')
            ->with('success', 'Data mata kuliah berhasil ditambahkan!');
    } catch (\Exception $e) {
        return back()
            ->with('error', 'Gagal menyimpan mata kuliah: ' . $e->getMessage())
            ->withInput();
    }
}

public function editMatakuliah($id)
{
    $matakuliah = DB::table('mata_kuliah')->where('id', $id)->first();

    if (!$matakuliah) {
        abort(404, 'Data mata kuliah tidak ditemukan.');
    }

    $dosens = Schema::hasTable('dosen')
        ? DB::table('dosen')->orderBy('nama', 'asc')->get()
        : collect();

    $semesters = collect(['1', '2', '3', '4', '5', '6', '7', '8']);

    return view('pages.admin.matakuliah_edit', compact(
        'matakuliah',
        'dosens',
        'semesters'
    ));
}

public function updateMatakuliah(Request $request, $id)
{
    $validated = $request->validate([
        'kode_mk' => 'required|string|max:30|unique:mata_kuliah,kode_mk,' . $id,
        'nama' => 'required|string|max:100',
        'sks' => 'required|integer|min:1|max:6',
        'semester_ke' => 'required|integer|min:1|max:8',
        'dosen_id' => 'nullable|exists:dosen,id',
    ], [
        'kode_mk.required' => 'Kode mata kuliah wajib diisi.',
        'kode_mk.unique' => 'Kode mata kuliah sudah digunakan.',
        'nama.required' => 'Nama mata kuliah wajib diisi.',
        'sks.required' => 'SKS wajib diisi.',
        'semester_ke.required' => 'Semester wajib dipilih.',
    ]);

    try {
        DB::table('mata_kuliah')
            ->where('id', $id)
            ->update([
                'dosen_id' => $validated['dosen_id'] ?? null,
                'kode_mk' => $validated['kode_mk'],
                'nama' => $validated['nama'],
                'sks' => $validated['sks'],
                'semester_ke' => $validated['semester_ke'],
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('pages.admin.matakuliah.index')
            ->with('success', 'Data mata kuliah berhasil diperbarui!');
    } catch (\Exception $e) {
        return back()
            ->with('error', 'Gagal memperbarui mata kuliah: ' . $e->getMessage())
            ->withInput();
    }
}

public function destroyMatakuliah(Request $request, $id)
{
    try {
        if (!Schema::hasTable('mata_kuliah')) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Tabel mata kuliah tidak ditemukan.'], 404);
            }

            return back()->with('error', 'Tabel mata kuliah tidak ditemukan.');
        }

        $matakuliah = DB::table('mata_kuliah')->where('id', $id)->first();

        if (!$matakuliah) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Data mata kuliah tidak ditemukan.'], 404);
            }

            return back()->with('error', 'Data mata kuliah tidak ditemukan.');
        }

        if (!Schema::hasColumn('mata_kuliah', 'deleted_at')) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Kolom soft delete belum tersedia. Jalankan migration terlebih dahulu.'], 500);
            }

            return back()->with('error', 'Kolom soft delete belum tersedia. Jalankan migration terlebih dahulu.');
        }

        $updateData = [
            'deleted_at' => now(),
        ];

        if (Schema::hasColumn('mata_kuliah', 'updated_at')) {
            $updateData['updated_at'] = now();
        }

        DB::table('mata_kuliah')->where('id', $id)->update($updateData);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Data mata kuliah berhasil dihapus dari tampilan admin!']);
        }

        return redirect()
            ->route('pages.admin.matakuliah.index')
            ->with('success', 'Data mata kuliah berhasil dihapus dari tampilan admin!');
    } catch (\Exception $e) {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Gagal menghapus mata kuliah: ' . $e->getMessage()], 500);
        }

        return back()
            ->with('error', 'Gagal menghapus mata kuliah: ' . $e->getMessage());
    }
}

    // ==========================================
    // 5. TAHUN AJARAN CRUD
    // ==========================================

    public function indexTahunAjaran()
    {
        if (Schema::hasTable('tahun_ajarans')) {
            $tahunAjaran = TahunAjaran::orderBy('created_at', 'desc')
                ->get()
                ->map(function ($tahunAjaran) {
                    $tahunAjaran->status = $this->normalizeTahunAjaranStatus($tahunAjaran->status);

                    return $tahunAjaran;
                });
        } else {
            $tahunAjaran = collect([
                (object)['id' => 1, 'semester' => 'Ganjil', 'tahun_ajaran' => '2024/2025', 'status' => 'Nonaktif'],
                (object)['id' => 2, 'semester' => 'Genap', 'tahun_ajaran' => '2025/2026', 'status' => 'Aktif'],
                (object)['id' => 3, 'semester' => 'Ganjil', 'tahun_ajaran' => '2026/2027', 'status' => 'Nonaktif'],
                (object)['id' => 4, 'semester' => 'Genap', 'tahun_ajaran' => '2027/2028', 'status' => 'Nonaktif'],
            ]);
        }

        return view('pages.admin.data_tahunajaran', compact('tahunAjaran'));
    }

    public function createTahunAjaran()
    {
        return view('pages.admin.tahunajaran_create');
    }

    public function storeTahunAjaran(Request $request)
    {
        $validated = $request->validate([
            'semester' => 'required|string|in:Ganjil,Genap',
            'tahun_ajaran' => [
                'required',
                'string',
                'max:20',
                'regex:/^\d{4}\/\d{4}$/',
                Rule::unique('tahun_ajarans', 'tahun_ajaran')
                    ->where(fn($query) => $query->where('semester', $request->semester)),
            ],
        ], [
            'semester.required' => 'Semester wajib dipilih.',
            'semester.in' => 'Semester harus Ganjil atau Genap.',
            'tahun_ajaran.required' => 'Tahun ajaran wajib dipilih.',
            'tahun_ajaran.regex' => 'Format tahun ajaran harus seperti 2025/2026.',
            'tahun_ajaran.unique' => 'Kombinasi semester dan tahun ajaran sudah ada.',
        ]);

        try {
            $status = $request->has('status') ? 'Aktif' : 'Nonaktif';

            if (Schema::hasTable('tahun_ajarans')) {
                DB::transaction(function () use ($validated, $status) {
                    if ($status === 'Aktif') {
                        TahunAjaran::where('status', 'Aktif')
                            ->orWhere('status', 'aktif')
                            ->update(['status' => 'Nonaktif']);
                    }

                    $tahunAjaran = TahunAjaran::create([
                        'semester' => $validated['semester'],
                        'tahun_ajaran' => $validated['tahun_ajaran'],
                        'status' => $status,
                    ]);

                    $this->syncTahunAjaranToSemester($tahunAjaran);
                });
            }
            return redirect()->route('pages.admin.tahunajaran.index')->with('success', 'Data tahun ajaran berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    public function editTahunAjaran($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);
        $tahunAjaran->status = $this->normalizeTahunAjaranStatus($tahunAjaran->status);

        return view('pages.admin.tahunajaran_edit', compact('tahunAjaran'));
    }

    public function updateTahunAjaran(Request $request, $id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);

        $validated = $request->validate([
            'semester' => 'required|string|in:Ganjil,Genap',
            'tahun_ajaran' => [
                'required',
                'string',
                'max:20',
                'regex:/^\d{4}\/\d{4}$/',
                Rule::unique('tahun_ajarans', 'tahun_ajaran')
                    ->where(fn($query) => $query->where('semester', $request->semester))
                    ->ignore($tahunAjaran->id),
            ],
        ], [
            'semester.required' => 'Semester wajib dipilih.',
            'semester.in' => 'Semester harus Ganjil atau Genap.',
            'tahun_ajaran.required' => 'Tahun ajaran wajib dipilih.',
            'tahun_ajaran.regex' => 'Format tahun ajaran harus seperti 2025/2026.',
            'tahun_ajaran.unique' => 'Kombinasi semester dan tahun ajaran sudah ada.',
        ]);

        try {
            $status = $request->has('status') ? 'Aktif' : 'Nonaktif';

            DB::transaction(function () use ($tahunAjaran, $validated, $status) {
                if ($status === 'Aktif') {
                    TahunAjaran::where('id', '!=', $tahunAjaran->id)
                        ->where(function ($query) {
                            $query->where('status', 'Aktif')
                                ->orWhere('status', 'aktif');
                        })
                        ->update(['status' => 'Nonaktif']);
                }

                $tahunAjaran->update([
                    'semester' => $validated['semester'],
                    'tahun_ajaran' => $validated['tahun_ajaran'],
                    'status' => $status,
                ]);

                $this->syncTahunAjaranToSemester($tahunAjaran);
            });

            return redirect()->route('pages.admin.tahunajaran.index')->with('success', 'Data tahun ajaran berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage())->withInput();
        }
    }

    public function destroyTahunAjaran($id)
    {
        try {
            if (Schema::hasTable('tahun_ajarans')) {
                $tahunAjaran = TahunAjaran::findOrFail($id);
                $this->setSemesterActiveState($tahunAjaran->semester, $tahunAjaran->tahun_ajaran, false);
                $tahunAjaran->delete();
            }
            return redirect()->route('pages.admin.tahunajaran.index')->with('success', 'Data tahun ajaran berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }

    private function normalizeTahunAjaranStatus(?string $status): string
    {
        return strtolower((string) $status) === 'aktif' ? 'Aktif' : 'Nonaktif';
    }

    private function syncTahunAjaranToSemester(TahunAjaran $tahunAjaran): void
    {
        if (!Schema::hasTable('semesters')) {
            return;
        }

        $isActive = $this->normalizeTahunAjaranStatus($tahunAjaran->status) === 'Aktif';

        if ($isActive && Schema::hasColumn('semesters', 'is_active')) {
            DB::table('semesters')->update(['is_active' => 0]);
        }

        $semesterData = $this->semesterSyncData(
            $tahunAjaran->semester,
            $tahunAjaran->tahun_ajaran,
            $isActive
        );

        $existing = DB::table('semesters')
            ->where('tahun_ajaran', $tahunAjaran->tahun_ajaran)
            ->when(
                Schema::hasColumn('semesters', 'semester'),
                fn($query) => $query->where('semester', $tahunAjaran->semester),
                fn($query) => $query->where('semester_ke', $this->semesterKeFromLabel($tahunAjaran->semester))
            )
            ->first();

        if ($existing) {
            unset($semesterData['created_at']);

            DB::table('semesters')->where('id', $existing->id)->update($semesterData);
            return;
        }

        DB::table('semesters')->insert($semesterData);
    }

    private function setSemesterActiveState(string $semester, string $tahunAjaran, bool $isActive): void
    {
        if (!Schema::hasTable('semesters') || !Schema::hasColumn('semesters', 'is_active')) {
            return;
        }

        DB::table('semesters')
            ->where('tahun_ajaran', $tahunAjaran)
            ->when(
                Schema::hasColumn('semesters', 'semester'),
                fn($query) => $query->where('semester', $semester),
                fn($query) => $query->where('semester_ke', $this->semesterKeFromLabel($semester))
            )
            ->update(['is_active' => $isActive ? 1 : 0]);
    }

    private function semesterSyncData(string $semester, string $tahunAjaran, bool $isActive): array
    {
        [$mulai, $selesai] = $this->defaultSemesterDates($semester, $tahunAjaran);

        return $this->onlyExistingColumns('semesters', [
            'nama' => 'Semester ' . $semester . ' ' . $tahunAjaran,
            'semester' => $semester,
            'tahun_ajaran' => $tahunAjaran,
            'semester_ke' => $this->semesterKeFromLabel($semester),
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai,
            'is_active' => $isActive ? 1 : 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function semesterKeFromLabel(string $semester): int
    {
        return strtolower($semester) === 'genap' ? 2 : 1;
    }

    private function defaultSemesterDates(string $semester, string $tahunAjaran): array
    {
        [$tahunMulai, $tahunSelesai] = array_pad(explode('/', $tahunAjaran), 2, date('Y'));

        if (strtolower($semester) === 'genap') {
            return [$tahunSelesai . '-02-01', $tahunSelesai . '-07-31'];
        }

        return [$tahunMulai . '-08-01', $tahunSelesai . '-01-31'];
    }

    private function onlyExistingColumns(string $table, array $data): array
    {
        $columns = Schema::getColumnListing($table);

        return collect($data)->only($columns)->toArray();
    }
    
    // ==========================================
    // 6. PAKET MATA KULIAH CRUD
    // ==========================================

    public function indexPaketMK()
    {
        $allMataKuliah = $this->getMataKuliahOptions();
        $prodis = $this->getProdiOptions();
        $semesters = $this->getPaketSemesterOptions();
        $paketMK = Schema::hasTable('paket_mata_kuliahs')
            ? $this->paketMataKuliahListQuery()->get()
            : collect();

        return view('pages.admin.data_paketmk', compact('paketMK', 'allMataKuliah', 'prodis', 'semesters'));
    }

    public function createPaketMK()
    {
        $allMataKuliah = $this->getMataKuliahOptions();
        $prodis = $this->getProdiOptions();
        $semesters = $this->getPaketSemesterOptions();

        return view('pages.admin.paketmk_create', compact('allMataKuliah', 'prodis', 'semesters'));
    }

    public function storePaketMK(Request $request)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'semester' => 'required|integer|min:1|max:8',
            'prodi' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'mata_kuliah' => 'required|array|min:1',
            'mata_kuliah.*' => 'integer|exists:mata_kuliah,id',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $paketId = DB::table('paket_mata_kuliahs')->insertGetId([
                    'nama_paket' => $validated['nama_paket'],
                    'semester_id' => $this->resolveSemesterId((int) $validated['semester']),
                    'prodi_id' => $this->resolveProdiId($validated['prodi']),
                    'deskripsi' => $validated['deskripsi'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->syncPaketMataKuliahDetails($paketId, $validated['mata_kuliah']);
            });

            return redirect()->route('pages.admin.paketmk.index')->with('success', 'Data paket berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    public function editPaketMK($id)
    {
        $paketMK = $this->paketMataKuliahListQuery()
            ->where('paket_mata_kuliahs.id', $id)
            ->first();

        if (!$paketMK) {
            abort(404, 'Data paket mata kuliah tidak ditemukan.');
        }

        $allMataKuliah = $this->getMataKuliahOptions();
        $prodis = $this->getProdiOptions();
        $semesters = $this->getPaketSemesterOptions();
        $selectedMataKuliahIds = DB::table('paket_mata_kuliah_details')
            ->where('paket_mata_kuliah_id', $id)
            ->pluck('mata_kuliah_id')
            ->map(fn($id) => (int) $id)
            ->toArray();

        return view('pages.admin.paketmk_edit', compact(
            'paketMK',
            'allMataKuliah',
            'prodis',
            'semesters',
            'selectedMataKuliahIds'
        ));
    }

    public function updatePaketMK(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'semester' => 'required|integer|min:1|max:8',
            'prodi' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'mata_kuliah' => 'required|array|min:1',
            'mata_kuliah.*' => 'integer|exists:mata_kuliah,id',
        ]);

        try {
            DB::transaction(function () use ($validated, $id) {
                DB::table('paket_mata_kuliahs')
                    ->where('id', $id)
                    ->update([
                        'nama_paket' => $validated['nama_paket'],
                        'semester_id' => $this->resolveSemesterId((int) $validated['semester']),
                        'prodi_id' => $this->resolveProdiId($validated['prodi']),
                        'deskripsi' => $validated['deskripsi'] ?? null,
                        'updated_at' => now(),
                    ]);

                $this->syncPaketMataKuliahDetails($id, $validated['mata_kuliah']);
            });

            return redirect()->route('pages.admin.paketmk.index')->with('success', 'Data paket berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage())->withInput();
        }
    }

    public function destroyPaketMK($id)
    {
        try {
            if (Schema::hasTable('paket_mata_kuliahs')) {
                $paket = PaketMataKuliah::findOrFail($id);
                $paket->delete();
            }
            return redirect()->route('pages.admin.paketmk.index')->with('success', 'Data paket berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    private function paketMataKuliahListQuery()
    {
        return DB::table('paket_mata_kuliahs')
            ->when(
                Schema::hasColumn('paket_mata_kuliahs', 'deleted_at'),
                fn($query) => $query->whereNull('paket_mata_kuliahs.deleted_at')
            )
            ->leftJoin('semesters', 'paket_mata_kuliahs.semester_id', '=', 'semesters.id')
            ->leftJoin('prodi', 'paket_mata_kuliahs.prodi_id', '=', 'prodi.id')
            ->leftJoin('paket_mata_kuliah_details', 'paket_mata_kuliahs.id', '=', 'paket_mata_kuliah_details.paket_mata_kuliah_id')
            ->leftJoin('mata_kuliah', function ($join) {
                $join->on('paket_mata_kuliah_details.mata_kuliah_id', '=', 'mata_kuliah.id');

                if (Schema::hasColumn('mata_kuliah', 'deleted_at')) {
                    $join->whereNull('mata_kuliah.deleted_at');
                }
            })
            ->select(
                'paket_mata_kuliahs.id',
                'paket_mata_kuliahs.nama_paket',
                'paket_mata_kuliahs.semester_id',
                'paket_mata_kuliahs.prodi_id',
                'paket_mata_kuliahs.deskripsi',
                'paket_mata_kuliahs.created_at',
                DB::raw('COALESCE(semesters.semester_ke, 0) as semester'),
                DB::raw("COALESCE(prodi.nama_prodi, '-') as prodi"),
                DB::raw('COALESCE(SUM(mata_kuliah.sks), 0) as total_sks'),
                DB::raw('COUNT(mata_kuliah.id) as jumlah_mk')
            )
            ->groupBy(
                'paket_mata_kuliahs.id',
                'paket_mata_kuliahs.nama_paket',
                'paket_mata_kuliahs.semester_id',
                'paket_mata_kuliahs.prodi_id',
                'paket_mata_kuliahs.deskripsi',
                'paket_mata_kuliahs.created_at',
                'semesters.semester_ke',
                'prodi.nama_prodi'
            )
            ->orderBy('paket_mata_kuliahs.created_at', 'desc');
    }

    private function getMataKuliahOptions()
    {
        if (!Schema::hasTable('mata_kuliah')) {
            return collect();
        }

        return DB::table('mata_kuliah')
            ->select(
                'id',
                DB::raw('kode_mk as kode'),
                'nama',
                'sks',
                'semester_ke'
            )
            ->when(
                Schema::hasColumn('mata_kuliah', 'deleted_at'),
                fn($query) => $query->whereNull('deleted_at')
            )
            ->orderBy('semester_ke')
            ->orderBy('kode_mk')
            ->get();
    }

    private function getPaketSemesterOptions()
    {
        $fromMataKuliah = Schema::hasTable('mata_kuliah')
            ? DB::table('mata_kuliah')
                ->whereNotNull('semester_ke')
                ->distinct()
                ->pluck('semester_ke')
                ->map(fn($semester) => (string) $semester)
            : collect();

        return collect(range(1, 8))
            ->map(fn($semester) => (string) $semester)
            ->merge($fromMataKuliah)
            ->unique()
            ->sort()
            ->values();
    }

    private function resolveSemesterId(int $semesterKe): ?int
    {
        if (!Schema::hasTable('semesters')) {
            return null;
        }

        $semesterId = DB::table('semesters')
            ->where('semester_ke', $semesterKe)
            ->orderByDesc('is_active')
            ->orderByDesc('created_at')
            ->value('id');

        if ($semesterId) {
            return $semesterId;
        }

        $tahunAjaran = DB::table('semesters')
            ->where('is_active', true)
            ->value('tahun_ajaran') ?? date('Y') . '/' . ((int) date('Y') + 1);
        $label = $semesterKe % 2 === 0 ? 'Genap' : 'Ganjil';

        return DB::table('semesters')->insertGetId([
            'nama' => 'Semester ' . $semesterKe . ' ' . $tahunAjaran,
            'tahun_ajaran' => $tahunAjaran,
            'semester' => $label,
            'semester_ke' => $semesterKe,
            'tanggal_mulai' => date('Y') . '-01-01',
            'tanggal_selesai' => date('Y') . '-06-30',
            'is_active' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function syncPaketMataKuliahDetails(int $paketId, array $mataKuliahIds): void
    {
        $rows = collect($mataKuliahIds)
            ->unique()
            ->map(fn($mataKuliahId) => [
                'paket_mata_kuliah_id' => $paketId,
                'mata_kuliah_id' => $mataKuliahId,
                'created_at' => now(),
                'updated_at' => now(),
            ])
            ->values()
            ->toArray();

        DB::table('paket_mata_kuliah_details')
            ->where('paket_mata_kuliah_id', $paketId)
            ->delete();

        DB::table('paket_mata_kuliah_details')->insert($rows);
    }
}
