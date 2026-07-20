<?php

namespace App\Http\Controllers;

use App\Support\PasswordVerifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MahasiswaController extends Controller
{
    private function currentUserSession(): ?array
    {
        return session('user');
    }

    private function currentMahasiswa()
    {
        $userSession = $this->currentUserSession();

        if (! $userSession) {
            return null;
        }

        $userId = $userSession['id'] ?? null;
        $nim = $userSession['nim'] ?? null;
        $email = $userSession['email'] ?? null;

        return DB::table('mahasiswa')
            ->leftJoin('users', 'mahasiswa.user_id', '=', 'users.id')
            ->leftJoin('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
            ->select(
                'mahasiswa.id as mahasiswa_id',
                'mahasiswa.user_id',
                'mahasiswa.prodi_id',
                'mahasiswa.nim',
                'mahasiswa.nama',
                'mahasiswa.email',
                'mahasiswa.no_hp',
                'mahasiswa.alamat',
                'mahasiswa.angkatan',
                'mahasiswa.kelas',
                'users.id as user_id',
                'users.name as user_name',
                'users.username',
                'users.email as user_email',
                'users.role',
                'prodi.nama_prodi as prodi'
            )
            ->when($userId, function ($query) use ($userId) {
                $query->where('mahasiswa.user_id', $userId);
            })
            ->when(! $userId && $nim, function ($query) use ($nim) {
                $query->where('mahasiswa.nim', $nim);
            })
            ->when(! $userId && ! $nim && $email, function ($query) use ($email) {
                $query->where('mahasiswa.email', $email);
            })
            ->first();
    }

    private function semesterAktif()
    {
        return DB::table('semesters')
            ->where('is_active', 1)
            ->first();
    }

    private function mahasiswaSemesterAktif(int $mahasiswaId, ?int $semesterId = null)
    {
        $query = DB::table('mahasiswa_semester')
            ->join('semesters', 'mahasiswa_semester.semester_id', '=', 'semesters.id')
            ->where('mahasiswa_semester.mahasiswa_id', $mahasiswaId)
            ->select(
                'mahasiswa_semester.*',
                'semesters.tahun_ajaran',
                'semesters.semester',
                'semesters.is_active'
            );

        if ($semesterId) {
            return $query->where('mahasiswa_semester.semester_id', $semesterId)->first();
        }

        return $query->where('semesters.is_active', 1)->first();
    }

    private function kelasPrefix($kelas): string
    {
        $kelas = $this->normalizeKelas($kelas);

        if ($kelas === '') {
            return '';
        }

        return explode('-', $kelas)[0] ?? $kelas;
    }

    private function normalizeKelas(?string $kelas): string
    {
        $kelas = strtoupper(trim((string) $kelas));
        $kelas = preg_replace('/\s+/', '-', $kelas);
        $kelas = preg_replace('/-+/', '-', $kelas);

        return trim($kelas, '-');
    }

    private function paketIdsUntukMahasiswa($mahasiswa, $semester, $progressSemester): array
    {
        if (! DB::getSchemaBuilder()->hasTable('paket_mata_kuliahs')) {
            return [];
        }

        return DB::table('paket_mata_kuliahs')
            ->leftJoin('semesters as paket_semesters', 'paket_mata_kuliahs.semester_id', '=', 'paket_semesters.id')
            ->when(DB::getSchemaBuilder()->hasColumn('paket_mata_kuliahs', 'deleted_at'), function ($query) {
                $query->whereNull('paket_mata_kuliahs.deleted_at');
            })
            ->when(! empty($mahasiswa->prodi_id), function ($query) use ($mahasiswa) {
                $query->where('paket_mata_kuliahs.prodi_id', $mahasiswa->prodi_id);
            })
            ->where(function ($query) use ($semester) {
                // Match packages that are either tied to the specific semester id
                // or defined by the same `semester_ke` as the requested semester.
                // Previously this compared to the student's progress semester_ke,
                // which allowed selecting packages for a different requested
                // semester when the student's progress matched that paket.
                $query->where('paket_mata_kuliahs.semester_id', $semester->id)
                    ->orWhere('paket_semesters.semester_ke', $semester->semester_ke);
            })
            ->pluck('paket_mata_kuliahs.id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->toArray();
    }

    private function formatMataKuliahUntukKrs($mk, bool $isMengulang = false): array
    {
        $namaDosen = $mk->dosen
            ? $mk->dosen.($mk->nik ? ' (NIK: '.$mk->nik.')' : '')
            : '-';

        $data = [
            'id' => (int) $mk->id,
            'kode' => $mk->kode,
            'matkul' => $mk->matkul,
            'dosen' => $namaDosen,
            'sks' => (int) $mk->sks,
            'kelas' => $mk->kelas ?? '-',
        ];

        if ($isMengulang) {
            $data['isMengulang'] = true;
            $data['nilaiLama'] = $mk->nilai_lama ?? '-';
            $data['semesterAsal'] = $mk->semester_asal ?? '-';
        } else {
            $data['prasyarat'] = $mk->prasyarat ?? '-';
        }

        return $data;
    }

    private function mataKuliahPaketWajib($mahasiswa, $semester, $progressSemester)
    {
        $paketIds = $this->paketIdsUntukMahasiswa($mahasiswa, $semester, $progressSemester);

        $query = DB::table('mata_kuliah')
            ->leftJoin('dosen', 'mata_kuliah.dosen_id', '=', 'dosen.id');

        if (! empty($paketIds)) {
            $query
                ->join('paket_mata_kuliah_details', 'mata_kuliah.id', '=', 'paket_mata_kuliah_details.mata_kuliah_id')
                ->whereIn('paket_mata_kuliah_details.paket_mata_kuliah_id', $paketIds);
        } else {
            $query->whereRaw('1 = 0');
        }

        return $query
            ->where('mata_kuliah.semester_ke', $semester->semester_ke)
            ->when(DB::getSchemaBuilder()->hasColumn('mata_kuliah', 'deleted_at'), function ($query) {
                $query->whereNull('mata_kuliah.deleted_at');
            })
            ->select(
                'mata_kuliah.id',
                'mata_kuliah.kode_mk as kode',
                'mata_kuliah.nama as matkul',
                'mata_kuliah.sks',
                'mata_kuliah.kelas',
                'mata_kuliah.prasyarat',
                'dosen.nama as dosen',
                'dosen.nik'
            )
            ->distinct()
            ->orderBy('mata_kuliah.kode_mk', 'asc')
            ->get()
            ->unique('id')
            ->values();
    }

    private function krsHasInvalidDetails($krs, ?string $kelasMahasiswa = null): bool
    {
        if (! $krs) {
            return false;
        }

        $semesterKe = (int) ($krs->semester_ke ?? 0);

        return DB::table('krs_detail')
            ->join('mata_kuliah', 'krs_detail.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->where('krs_detail.krs_mahasiswa_id', $krs->id)
            ->select('mata_kuliah.semester_ke')
            ->get()
            ->contains(function ($mk) use ($semesterKe) {
                return (int) $mk->semester_ke !== $semesterKe;
            });
    }

    private function mataKuliahMengulang($mahasiswa, $progressSemester, array $excludeIds = [])
    {
        $semesterKeAktif = (int) $progressSemester->semester_ke;
        $paritasAktif = $semesterKeAktif % 2;

        return DB::table('nilai')
            ->join('mata_kuliah', 'nilai.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->leftJoin('dosen', 'mata_kuliah.dosen_id', '=', 'dosen.id')
            ->where('nilai.mahasiswa_id', $mahasiswa->mahasiswa_id)
            ->where('nilai.status', 'final')
            ->whereNotIn('mata_kuliah.id', $excludeIds ?: [0])
            ->whereNotNull('mata_kuliah.semester_ke')
            ->where('mata_kuliah.semester_ke', '<', $semesterKeAktif)
            ->whereRaw('(mata_kuliah.semester_ke % 2) = ?', [$paritasAktif])
            ->when(DB::getSchemaBuilder()->hasColumn('mata_kuliah', 'deleted_at'), function ($query) {
                $query->whereNull('mata_kuliah.deleted_at');
            })
            ->select(
                'mata_kuliah.id',
                'mata_kuliah.kode_mk as kode',
                'mata_kuliah.nama as matkul',
                'mata_kuliah.sks',
                'mata_kuliah.kelas',
                'mata_kuliah.semester_ke as semester_asal',
                'dosen.nama as dosen',
                'dosen.nik',
                'nilai.nilai as nilai_lama',
                'nilai.created_at as nilai_created_at'
            )
            ->orderBy('nilai.created_at', 'desc')
            ->get()
            ->unique('kode')
            ->filter(fn ($mk) => in_array($mk->nilai_lama, ['D', 'E'], true))
            ->sortBy('kode')
            ->values();
    }

    public function index()
    {
        $mahasiswa = $this->currentMahasiswa();
        $semesterAktif = $this->semesterAktif();

        if (! $mahasiswa) {
            return redirect()->route('login');
        }

        $mahasiswaId = $mahasiswa->mahasiswa_id;

        $nilaiRecords = collect();
        $ipkRecords = DB::getSchemaBuilder()->hasTable('nilai')
            ? DB::table('nilai')
                ->where('mahasiswa_id', $mahasiswaId)
                ->where('status', 'final')
                ->select('bobot', 'sks')
                ->get()
            : collect();

        $totalSksIpk = $ipkRecords->sum('sks');
        $totalBobotIpk = $ipkRecords->sum(function ($item) {
            return ((int) $item->sks) * ((float) $item->bobot);
        });

        $ipk = $totalSksIpk > 0 ? round($totalBobotIpk / $totalSksIpk, 2) : 0;

        if ($semesterAktif) {
            $nilaiRecords = DB::table('nilai')
                ->join('mata_kuliah', 'nilai.mata_kuliah_id', '=', 'mata_kuliah.id')
                ->where('nilai.mahasiswa_id', $mahasiswaId)
                ->where('nilai.tahun_ajaran', $semesterAktif->tahun_ajaran)
                ->where('nilai.semester', $semesterAktif->semester_ke)
                ->where('nilai.status', 'final')
                ->select(
                    'nilai.id',
                    'nilai.mahasiswa_id',
                    'nilai.mata_kuliah_id',
                    'nilai.tahun_ajaran',
                    'nilai.semester',
                    'nilai.nilai',
                    'nilai.bobot',
                    'nilai.sks',
                    'mata_kuliah.kode_mk',
                    'mata_kuliah.nama as nama_mk'
                )
                ->orderBy('mata_kuliah.kode_mk', 'asc')
                ->get();
        }

        $totalSks = $nilaiRecords->sum('sks');

        $totalBobot = $nilaiRecords->sum(function ($item) {
            return ((int) $item->sks) * ((float) $item->bobot);
        });

        $ips = $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0;

        $mataKuliahLulus = $nilaiRecords
            ->filter(function ($item) {
                return (float) $item->bobot >= 2.00;
            })
            ->count();

        $nilaiTerbaru = $nilaiRecords
            ->take(5)
            ->map(function ($item) {
                return [
                    'matkul' => $item->nama_mk,
                    'sks' => $item->sks,
                    'nilai' => $item->nilai,
                    'bobot' => $item->bobot,
                ];
            })
            ->toArray();

        $krsAktif = [];

        if ($semesterAktif) {
            $krsHeader = DB::table('krs_mahasiswa')
                ->where('mahasiswa_id', $mahasiswaId)
                ->where('semester_id', $semesterAktif->id)
                ->latest('created_at')
                ->first();

            if ($krsHeader) {
                $krsAktif = DB::table('krs_detail')
                    ->join('mata_kuliah', 'krs_detail.mata_kuliah_id', '=', 'mata_kuliah.id')
                    ->where('krs_detail.krs_mahasiswa_id', $krsHeader->id)
                    ->select(
                        'mata_kuliah.kode_mk',
                        'mata_kuliah.nama',
                        'mata_kuliah.sks'
                    )
                    ->get()
                    ->map(function ($item) use ($krsHeader) {
                        return [
                            'kode' => $item->kode_mk,
                            'matkul' => $item->nama,
                            'sks' => $item->sks,
                            'status' => ucfirst($krsHeader->status),
                        ];
                    })
                    ->toArray();
            }
        }

        $data = [
            'nama' => $mahasiswa->nama ?? $mahasiswa->user_name ?? '-',
            'nim' => $mahasiswa->nim ?? '-',
            'prodi' => 'Teknik Informatika',
            'angkatan' => $mahasiswa->angkatan ?? '-',
            'email' => $mahasiswa->email ?? $mahasiswa->user_email ?? '-',
            'semester_aktif' => $semesterAktif ? $semesterAktif->semester : '-',
            'total_sks' => $totalSks,
            'ips' => $ips,
            'ipk' => $ipk,
            'mata_kuliah_lulus' => $mataKuliahLulus,
            'nilai_terbaru' => $nilaiTerbaru,
            'krs_aktif' => $krsAktif,
        ];

        return view('pages.mahasiswa.beranda', compact('data'));
    }

    public function profil()
    {
        $mahasiswa = $this->currentMahasiswa();

        if (! $mahasiswa) {
            return redirect()->route('login')
                ->with('error', 'Data mahasiswa tidak ditemukan. Silakan login ulang.');
        }

        $semesterAktif = $this->semesterAktif();

        $data = [
            'mahasiswa_id' => $mahasiswa->mahasiswa_id,
            'user_id' => $mahasiswa->user_id,
            'nama' => $mahasiswa->nama ?? $mahasiswa->user_name ?? '-',
            'nim' => $mahasiswa->nim ?? '-',
            'email' => $mahasiswa->email ?? $mahasiswa->user_email ?? '-',
            'no_hp' => $mahasiswa->no_hp ?? '',
            'alamat' => $mahasiswa->alamat ?? '',
            'angkatan' => $mahasiswa->angkatan ?? '-',
            'kelas' => $mahasiswa->kelas ?? '-',
            'program_studi' => 'Teknik Informatika',
            'semester_aktif' => $semesterAktif
                ? 'Semester '.$semesterAktif->semester
                : '-',
            'tahun_ajaran' => $semesterAktif->tahun_ajaran ?? '-',
        ];

        return view('pages.mahasiswa.profil', compact('data'));
    }

    public function updateProfil(Request $request)
    {
        $userSession = $this->currentUserSession();

        if (! $userSession) {
            return redirect()->route('login');
        }

        $userId = $userSession['id'] ?? null;

        $mahasiswa = DB::table('mahasiswa')
            ->where('user_id', $userId)
            ->first();

        if (! $mahasiswa) {
            return back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:500'],
        ]);

        DB::table('mahasiswa')
            ->where('id', $mahasiswa->id)
            ->update([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'no_hp' => $validated['no_hp'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'updated_at' => now(),
            ]);

        DB::table('users')
            ->where('id', $userId)
            ->update([
                'name' => $validated['nama'],
                'email' => $validated['email'],
                'updated_at' => now(),
            ]);

        $updatedUser = DB::table('users')->where('id', $userId)->first();

        session([
            'user' => [
                'id' => $updatedUser->id,
                'name' => $updatedUser->name,
                'username' => $updatedUser->username,
                'nim' => $updatedUser->nim,
                'nik' => $updatedUser->nik,
                'email' => $updatedUser->email,
                'role' => $updatedUser->role,
            ],
            'user_name' => $updatedUser->name,
        ]);

        return redirect()
            ->route('pages.mahasiswa.profil')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $userSession = $this->currentUserSession();

        if (! $userSession) {
            return redirect()->route('login');
        }

        $userId = $userSession['id'] ?? null;

        $validated = $request->validate([
            'password_lama' => ['required'],
            'password_baru' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = DB::table('users')->where('id', $userId)->first();

        if (! $user) {
            return back()->with('error', 'Akun user tidak ditemukan.');
        }

        if (! PasswordVerifier::check($validated['password_lama'], $user->password)) {
            return back()->withErrors([
                'password_lama' => 'Kata sandi lama tidak sesuai.',
            ]);
        }

        DB::table('users')
            ->where('id', $userId)
            ->update([
                'password' => Hash::make($validated['password_baru']),
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('pages.mahasiswa.profil')
            ->with('success', 'Kata sandi berhasil diperbarui!');
    }

    public function ambilKrs()
    {
        $mahasiswa = $this->currentMahasiswa();
        $semesterAktif = $this->semesterAktif();

        if (! $mahasiswa) {
            return redirect()->route('login');
        }

        $statusKrs = 'Belum Mengajukan';
        $isReadOnlyKrs = false;
        $totalSksDiambil = 0;

        if ($semesterAktif) {
            $krsAktif = DB::table('krs_mahasiswa')
                ->where('mahasiswa_id', $mahasiswa->mahasiswa_id)
                ->where('semester_id', $semesterAktif->id)
                ->latest('created_at')
                ->first();

            if ($krsAktif) {
                $statusKrs = ucfirst($krsAktif->status);
                $totalSksDiambil = (int) $krsAktif->total_sks;
                $hasInvalidKrs = $this->krsHasInvalidDetails($krsAktif, $mahasiswa->kelas ?? '');

                if ($hasInvalidKrs) {
                    $statusKrs = 'Perlu Diajukan Ulang';
                    $isReadOnlyKrs = false;
                } elseif (in_array($krsAktif->status, ['menunggu', 'disetujui'])) {
                    $isReadOnlyKrs = true;
                }
            }
        }

        $tahunAjaranList = DB::table('semesters')
            ->select('tahun_ajaran')
            ->distinct()
            ->orderBy('tahun_ajaran', 'desc')
            ->get();

        $semesterList = DB::table('semesters')
            ->select('semester', 'semester_ke')
            ->distinct()
            ->orderBy('semester_ke', 'asc')
            ->get();

        $data = [
            'nama' => $mahasiswa->nama ?? $mahasiswa->user_name ?? '-',
            'email' => $mahasiswa->email ?? $mahasiswa->user_email ?? '-',
            'semester_aktif' => $semesterAktif?->semester ?? '-',
            'semester_label' => $semesterAktif
                ? 'Semester '.$semesterAktif->semester.' '.$semesterAktif->tahun_ajaran
                : '-',
            'total_sks' => $totalSksDiambil,
            'sisa_sks' => max(0, 24 - $totalSksDiambil),
            'status_krs' => $statusKrs,
            'max_sks' => 24,
            'tahun_ajaran_aktif' => $semesterAktif?->tahun_ajaran ?? '-',
            'semester_aktif_value' => $semesterAktif?->semester ?? '-',
            'is_semester_active' => $semesterAktif ? true : false,
            'is_read_only_krs' => $isReadOnlyKrs,
            'tahun_ajaran_list' => $tahunAjaranList,
            'semester_list' => $semesterList,
            'semester_aktif_data' => $semesterAktif,
        ];

        return view('pages.mahasiswa.ambil-krs', compact('data'));
    }

    public function getPaketSemester(Request $request)
    {
        $semesterRequest = $request->input('semester');
        $tahunAjaran = $request->input('tahun_ajaran');

        $semester = DB::table('semesters')
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('semester', $semesterRequest)
            ->first();

        if (! $semester) {
            return response()->json([
                'error' => true,
                'message' => 'Semester tidak ditemukan.',
            ], 404);
        }

        $mahasiswa = $this->currentMahasiswa();
        $progressSemester = $mahasiswa ? $this->mahasiswaSemesterAktif($mahasiswa->mahasiswa_id, $semester->id) : null;

        if (! $mahasiswa) {
            return response()->json([
                'error' => true,
                'message' => 'Data mahasiswa tidak ditemukan.',
            ], 403);
        }

        if (! $progressSemester) {
            return response()->json([
                'error' => true,
                'message' => 'Semester mahasiswa belum diatur oleh admin.',
            ], 422);
        }

        if (! in_array($progressSemester->status, ['aktif', 'mengulang'], true)) {
            return response()->json([
                'error' => true,
                'message' => 'Status semester Anda '.ucfirst($progressSemester->status).', sehingga KRS belum dapat diajukan.',
            ], 422);
        }

        $isReadOnly = ! (bool) $semester->is_active;
        $totalSksDiambil = 0;

        $existingKrs = DB::table('krs_mahasiswa')
            ->where('mahasiswa_id', $mahasiswa->mahasiswa_id)
            ->where('semester_id', $semester->id)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->latest('created_at')
            ->first();

        if ($existingKrs) {
            $isReadOnly = ! $this->krsHasInvalidDetails($existingKrs, $mahasiswa->kelas ?? '');
            $totalSksDiambil = (int) $existingKrs->total_sks;
        }

        $kelasMahasiswa = $this->normalizeKelas($mahasiswa->kelas ?? '');
        $wajibRecords = $this->mataKuliahPaketWajib($mahasiswa, $semester, $progressSemester);
        $wajibIds = $wajibRecords->pluck('id')->map(fn ($id) => (int) $id)->toArray();
        $mengulangRecords = $this->mataKuliahMengulang($mahasiswa, $progressSemester, $wajibIds);

        $mkWajib = $wajibRecords
            ->map(fn ($mk) => $this->formatMataKuliahUntukKrs($mk))
            ->values()
            ->toArray();

        $mkMengulang = $mengulangRecords
            ->map(fn ($mk) => $this->formatMataKuliahUntukKrs($mk, true))
            ->values()
            ->toArray();

        return response()->json([
            'error' => false,
            'semester' => 'Semester '.$semester->semester.' '.$semester->tahun_ajaran,
            'tahun_ajaran' => $semester->tahun_ajaran,
            'semester_ke_mahasiswa' => (int) $progressSemester->semester_ke,
            'status_semester_mahasiswa' => $progressSemester->status,
            'kelas_mahasiswa' => $kelasMahasiswa,
            'is_read_only' => $isReadOnly,
            'is_active' => (bool) $semester->is_active,
            'total_sks_diambil' => $totalSksDiambil,
            'sisa_sks' => max(0, 24 - $totalSksDiambil),
            'paket_semester' => [
                'wajib' => $mkWajib,
                'mengulang' => $mkMengulang,
            ],
            'max_sks' => 24,
        ]);
    }

    public function storeKrs(Request $request)
    {
        $request->validate([
            'mata_kuliah_ids' => ['required', 'array'],
            'mata_kuliah_ids.*' => ['numeric', 'exists:mata_kuliah,id'],
            'semester' => ['required', 'string'],
            'tahun_ajaran' => ['required', 'string'],
        ]);

        $mahasiswa = $this->currentMahasiswa();
        $semesterAktif = $this->semesterAktif();

        if (! $mahasiswa) {
            return redirect()->route('login');
        }

        if (! $semesterAktif) {
            return back()->with('error', 'Belum ada semester aktif.');
        }

        $progressSemester = $this->mahasiswaSemesterAktif($mahasiswa->mahasiswa_id, $semesterAktif->id);

        if (! $progressSemester) {
            return back()->with('error', 'Semester Anda belum diatur oleh admin.');
        }

        if (! in_array($progressSemester->status, ['aktif', 'mengulang'], true)) {
            return back()->with('error', 'Status semester Anda '.ucfirst($progressSemester->status).', sehingga KRS belum dapat diajukan.');
        }

        $mataKuliahIds = array_unique($request->input('mata_kuliah_ids'));
        $tahunAjaran = $request->input('tahun_ajaran');
        $semesterInput = $request->input('semester');

        if ($tahunAjaran !== $semesterAktif->tahun_ajaran || $semesterInput !== $semesterAktif->semester) {
            return back()->with('error', 'Hanya semester aktif yang dapat diajukan.');
        }

        $kelasMahasiswa = $this->normalizeKelas($mahasiswa->kelas ?? '');
        $wajibRecords = $this->mataKuliahPaketWajib($mahasiswa, $semesterAktif, $progressSemester);
        $wajibIds = $wajibRecords->pluck('id')->map(fn ($id) => (int) $id)->unique()->values()->toArray();
        $mengulangIds = $this->mataKuliahMengulang($mahasiswa, $progressSemester, $wajibIds)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->toArray();

        $validMataKuliahIds = collect($wajibIds)
            ->merge($mengulangIds)
            ->unique()
            ->values()
            ->toArray();

        $mataKuliahIds = collect($mataKuliahIds)
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->toArray();

        if (count(array_diff($mataKuliahIds, $validMataKuliahIds)) > 0) {
            return back()->with('error', 'Ada mata kuliah yang tidak sesuai dengan aturan semester aktif atau aturan mengulang. Silakan pilih ulang KRS.');
        }

        if (count($wajibIds) > 0 && count(array_diff($wajibIds, $mataKuliahIds)) > 0) {
            return back()->with('error', 'Mata kuliah paket semester aktif harus dipilih lengkap sebelum KRS diajukan.');
        }

        $totalSks = DB::table('mata_kuliah')
            ->whereIn('id', $mataKuliahIds)
            ->sum('sks');

        if ($totalSks > 24) {
            return back()->with('error', 'Total SKS tidak boleh melebihi 24 SKS. SKS yang dipilih: '.$totalSks);
        }

        try {
            DB::beginTransaction();

            $existingKrs = DB::table('krs_mahasiswa')
                ->where('mahasiswa_id', $mahasiswa->mahasiswa_id)
                ->where('semester_id', $semesterAktif->id)
                ->first();

            if ($existingKrs) {
                if ($existingKrs->status === 'ditolak' || $this->krsHasInvalidDetails($existingKrs, $mahasiswa->kelas ?? '')) {
                    DB::table('krs_detail')
                        ->where('krs_mahasiswa_id', $existingKrs->id)
                        ->delete();

                    DB::table('krs_mahasiswa')
                        ->where('id', $existingKrs->id)
                        ->delete();
                } else {
                    DB::rollBack();

                    return back()->with(
                        'warning',
                        'Anda sudah mengajukan KRS di semester ini. Status: '.ucfirst($existingKrs->status)
                    );
                }
            }

            $krsId = DB::table('krs_mahasiswa')->insertGetId([
                'mahasiswa_id' => $mahasiswa->mahasiswa_id,
                'semester_id' => $semesterAktif->id,
                'tahun_ajaran' => $semesterAktif->tahun_ajaran,
                'semester' => $semesterAktif->semester,
                'semester_ke' => $progressSemester->semester_ke,
                'kelas' => $kelasMahasiswa,
                'status' => 'menunggu',
                'total_sks' => $totalSks,
                'catatan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($mataKuliahIds as $mataKuliahId) {
                DB::table('krs_detail')->insert([
                    'krs_mahasiswa_id' => $krsId,
                    'mata_kuliah_id' => $mataKuliahId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()
                ->route('pages.mahasiswa.ambil-krs')
                ->with('success', 'KRS berhasil diajukan! Total SKS: '.$totalSks.' SKS. Menunggu persetujuan dosen wali.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function viewKrs()
    {
        $mahasiswa = $this->currentMahasiswa();

        if (! $mahasiswa) {
            return redirect()->route('login');
        }

        $krsRecords = DB::table('krs_mahasiswa')
            ->join('semesters', 'krs_mahasiswa.semester_id', '=', 'semesters.id')
            ->where('krs_mahasiswa.mahasiswa_id', $mahasiswa->mahasiswa_id)
            ->select(
                'krs_mahasiswa.*',
                'semesters.semester as semester_nama'
            )
            ->orderBy('krs_mahasiswa.tahun_ajaran', 'desc')
            ->orderBy('krs_mahasiswa.created_at', 'desc')
            ->get()
            ->map(function ($krs) {
                $details = DB::table('krs_detail')
                    ->join('mata_kuliah', 'krs_detail.mata_kuliah_id', '=', 'mata_kuliah.id')
                    ->where('krs_detail.krs_mahasiswa_id', $krs->id)
                    ->select(
                        'mata_kuliah.kode_mk',
                        'mata_kuliah.nama',
                        'mata_kuliah.sks',
                        'mata_kuliah.kelas'
                    )
                    ->get();

                $krs->details = $details;

                return $krs;
            })
            ->groupBy(function ($item) {
                return $item->tahun_ajaran.' - Semester '.$item->semester_nama;
            });

        $statistik = [];

        foreach ($krsRecords as $periode => $krsGroup) {
            $totalSks = 0;
            $totalMk = 0;
            $disetujui = 0;
            $ditolak = 0;
            $menunggu = 0;

            foreach ($krsGroup as $krs) {
                $totalSks += $krs->total_sks;
                $totalMk += count($krs->details);

                if ($krs->status === 'disetujui') {
                    $disetujui++;
                } elseif ($krs->status === 'ditolak') {
                    $ditolak++;
                } else {
                    $menunggu++;
                }
            }

            $statistik[$periode] = [
                'total_sks' => $totalSks,
                'disetujui' => $disetujui,
                'ditolak' => $ditolak,
                'menunggu' => $menunggu,
                'total_mk' => $totalMk,
            ];
        }

        return view('pages.mahasiswa.lihat-krs', [
            'krsRecords' => $krsRecords,
            'statistik' => $statistik,
        ]);
    }

    public function lihatKhs(Request $request)
    {
        return app(KhsMahasiswaController::class)->index($request);
    }
}
