<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MahasiswaController extends Controller
{
    /**
     * Helper: ambil user dari session simple auth.
     */
    private function currentUserSession(): ?array
    {
        return session('user');
    }

    /**
     * Helper: ambil data mahasiswa yang sedang login.
     */
    private function currentMahasiswa()
    {
        $userSession = $this->currentUserSession();

        if (!$userSession) {
            return null;
        }

        $userId = $userSession['id'] ?? null;
        $nim = $userSession['nim'] ?? null;
        $email = $userSession['email'] ?? null;

        return DB::table('mahasiswa')
            ->leftJoin('users', 'mahasiswa.user_id', '=', 'users.id')
            ->select(
                'mahasiswa.id as mahasiswa_id',
                'mahasiswa.user_id',
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
                'users.role'
            )
            ->when($userId, function ($query) use ($userId) {
                $query->where('mahasiswa.user_id', $userId);
            })
            ->when(!$userId && $nim, function ($query) use ($nim) {
                $query->where('mahasiswa.nim', $nim);
            })
            ->when(!$userId && !$nim && $email, function ($query) use ($email) {
                $query->where('mahasiswa.email', $email);
            })
            ->first();
    }

    /**
     * Helper: ambil semester aktif.
     */
    private function semesterAktif()
    {
        return DB::table('semesters')
            ->where('is_active', 1)
            ->first();
    }

    /**
     * Halaman Beranda Mahasiswa
     */
    public function index()
    {
        $mahasiswa = $this->currentMahasiswa();
        $semesterAktif = $this->semesterAktif();

        if (!$mahasiswa) {
            return redirect()->route('login');
        }

        $mahasiswaId = $mahasiswa->mahasiswa_id;

        /*
        |--------------------------------------------------------------------------
        | Statistik Akademik dari tabel nilai
        |--------------------------------------------------------------------------
        */
        $nilaiRecords = DB::table('nilai')
            ->join('mata_kuliah', 'nilai.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->where('nilai.mahasiswa_id', $mahasiswaId)
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
            ->orderBy('nilai.created_at', 'desc')
            ->get();

        $totalSks = $nilaiRecords->sum('sks');

        $totalBobot = $nilaiRecords->sum(function ($item) {
            return ((int) $item->sks) * ((float) $item->bobot);
        });

        $ipk = $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0;

        $mataKuliahLulus = $nilaiRecords
            ->filter(function ($item) {
                return (float) $item->bobot >= 2.00;
            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Nilai Terbaru
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | KRS Aktif dari krs_mahasiswa dan krs_detail
        |--------------------------------------------------------------------------
        */
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
            'ipk' => $ipk,
            'mata_kuliah_lulus' => $mataKuliahLulus,

            'nilai_terbaru' => $nilaiTerbaru,
            'krs_aktif' => $krsAktif,
        ];

        return view('pages.mahasiswa.beranda', compact('data'));
    }

    /**
     * Halaman Profil Mahasiswa
     */
    public function profil()
    {
        $mahasiswa = $this->currentMahasiswa();

        if (!$mahasiswa) {
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
                ? 'Semester ' . $semesterAktif->semester
                : '-',
            'tahun_ajaran' => $semesterAktif->tahun_ajaran ?? '-',
        ];

        return view('pages.mahasiswa.profil', compact('data'));
    }

    /**
     * Update Profil Mahasiswa
     */
    public function updateProfil(Request $request)
    {
        $userSession = $this->currentUserSession();

        if (!$userSession) {
            return redirect()->route('login');
        }

        $userId = $userSession['id'] ?? null;

        $mahasiswa = DB::table('mahasiswa')
            ->where('user_id', $userId)
            ->first();

        if (!$mahasiswa) {
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

    /**
     * Update Password Mahasiswa
     */
    public function updatePassword(Request $request)
    {
        $userSession = $this->currentUserSession();

        if (!$userSession) {
            return redirect()->route('login');
        }

        $userId = $userSession['id'] ?? null;

        $validated = $request->validate([
            'password_lama' => ['required'],
            'password_baru' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = DB::table('users')->where('id', $userId)->first();

        if (!$user) {
            return back()->with('error', 'Akun user tidak ditemukan.');
        }

        if (!Hash::check($validated['password_lama'], $user->password)) {
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

    /**
     * Halaman Ambil KRS
     */
    public function ambilKrs()
    {
        $mahasiswa = $this->currentMahasiswa();
        $semesterAktif = $this->semesterAktif();

        if (!$mahasiswa) {
            return redirect()->route('login');
        }

        $statusKrs = 'Belum Mengajukan';
        $isReadOnlyKrs = false;

        if ($semesterAktif) {
            $krsAktif = DB::table('krs_mahasiswa')
                ->where('mahasiswa_id', $mahasiswa->mahasiswa_id)
                ->where('semester_id', $semesterAktif->id)
                ->first();

            if ($krsAktif) {
                $statusKrs = ucfirst($krsAktif->status);
                
                // Jika status menunggu atau disetujui, KRS tidak bisa diedit/diajukan ulang
                if (in_array($krsAktif->status, ['menunggu', 'disetujui'])) {
                    $isReadOnlyKrs = true;
                }
            }
        }

        // Ambil semua semester untuk dropdown filter (termasuk yang tidak aktif)
        $allSemesters = DB::table('semesters')->orderByDesc('id')->get();

        $data = [
            'nama' => $mahasiswa->nama ?? $mahasiswa->user_name ?? '-',
            'email' => $mahasiswa->email ?? $mahasiswa->user_email ?? '-',
            'semester_aktif' => $semesterAktif?->semester ?? '-',
            'semester_label' => $semesterAktif
                ? 'Semester ' . $semesterAktif->semester . ' ' . $semesterAktif->tahun_ajaran
                : '-',
            'total_sks' => 0,
            'status_krs' => $statusKrs,
            'max_sks' => 24,
            'tahun_ajaran_aktif' => $semesterAktif?->tahun_ajaran ?? '2025/2026',
            'is_semester_active' => $semesterAktif ? true : false,
            'is_read_only_krs' => $isReadOnlyKrs, // Kirim ke view
            'all_semesters' => $allSemesters,     // Kirim ke view
        ];

        return view('pages.mahasiswa.ambil-krs', compact('data'));
    }

    /**
     * API: Load Paket Semester berdasarkan filter
     */
    public function getPaketSemester(Request $request)
    {
        $semesterRequest = $request->input('semester');
        $tahunAjaran = $request->input('tahun_ajaran');

        // Cari semester berdasarkan filter (bisa aktif maupun tidak)
        $semesterQuery = DB::table('semesters');
        if ($tahunAjaran) {
            $semesterQuery->where('tahun_ajaran', $tahunAjaran);
        }
        if (strpos($semesterRequest, 'Genap') !== false) {
            $semesterQuery->where('semester', 'Genap');
        } elseif (strpos($semesterRequest, 'Ganjil') !== false) {
            $semesterQuery->where('semester', 'Ganjil');
        }
        
        $semester = $semesterQuery->first();

        if (!$semester) {
            return response()->json([
                'error' => true,
                'message' => 'Semester tidak ditemukan.',
            ], 404);
        }

        // Cek apakah semester ini read-only
        $isReadOnly = !(bool)$semester->is_active;

        $mahasiswa = $this->currentMahasiswa();
        if (!$mahasiswa) {
            return response()->json(['error' => true, 'message' => 'Data mahasiswa tidak ditemukan.'], 403);
        }

        // Jika semester aktif, cek apakah mahasiswa sudah mengajukan KRS
        if (!$isReadOnly) {
            $existingKrs = DB::table('krs_mahasiswa')
                ->where('mahasiswa_id', $mahasiswa->mahasiswa_id)
                ->where('semester_id', $semester->id)
                ->whereIn('status', ['menunggu', 'disetujui'])
                ->first();
                
            if ($existingKrs) {
                $isReadOnly = true; // Sudah mengajukan, jadi read only
            }
        }

        // 1. Cek mata kuliah yang pernah dapat D/E (Mengulang)
        $mengulangIds = DB::table('nilai')
            ->where('mahasiswa_id', $mahasiswa->mahasiswa_id)
            ->whereIn('nilai', ['D', 'E'])
            ->pluck('mata_kuliah_id')
            ->toArray();

        // 2. Ambil Semua Mata Kuliah yang ditawarkan di Semester Terpilih
        $allMk = DB::table('mata_kuliah')
            ->leftJoin('dosen', 'mata_kuliah.dosen_id', '=', 'dosen.id')
            ->where('mata_kuliah.semester_id', $semester->id)
            ->select(
                'mata_kuliah.id',
                'mata_kuliah.kode_mk as kode',
                'mata_kuliah.nama as matkul',
                'mata_kuliah.sks',
                'dosen.nama as dosen'
            )
            ->get();

        $mkWajib = [];
        $mkMengulang = [];

        foreach ($allMk as $mk) {
            if (in_array($mk->id, $mengulangIds)) {
                $nilaiLama = DB::table('nilai')
                    ->where('mahasiswa_id', $mahasiswa->mahasiswa_id)
                    ->where('mata_kuliah_id', $mk->id)
                    ->orderByDesc('created_at')
                    ->value('nilai');

                $mkMengulang[] = [
                    'id' => $mk->id,
                    'kode' => $mk->kode,
                    'matkul' => $mk->matkul,
                    'dosen' => $mk->dosen ?? '-',
                    'sks' => $mk->sks,
                    'isMengulang' => true,
                    'nilaiLama' => $nilaiLama ?? '-',
                ];
            } else {
                $mkWajib[] = [
                    'id' => $mk->id,
                    'kode' => $mk->kode,
                    'matkul' => $mk->matkul,
                    'dosen' => $mk->dosen ?? '-',
                    'sks' => $mk->sks,
                    'prasyarat' => '-',
                ];
            }
        }

        return response()->json([
            'error' => false,
            'semester' => 'Semester ' . $semester->semester . ' ' . $semester->tahun_ajaran,
            'tahun_ajaran' => $semester->tahun_ajaran,
            'is_read_only' => $isReadOnly, // Kirim status ke frontend
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
            'mata_kuliah_ids' => ['required', 'array'],
            'mata_kuliah_ids.*' => ['numeric', 'exists:mata_kuliah,id'],
            'semester' => ['required', 'string'],
            'tahun_ajaran' => ['required', 'string'],
        ]);

        try {
            $mahasiswa = $this->currentMahasiswa();
            $semesterAktif = $this->semesterAktif();

            if (!$mahasiswa) {
                return redirect()->route('login');
            }

            if (!$semesterAktif) {
                return back()->with('error', 'Belum ada semester aktif.');
            }

            $mataKuliahIds = $request->input('mata_kuliah_ids');
            $tahunAjaran = $request->input('tahun_ajaran');

            if ($tahunAjaran !== $semesterAktif->tahun_ajaran) {
                return back()->with('error', 'Hanya semester aktif yang dapat diajukan.');
            }

            $totalSks = DB::table('mata_kuliah')
                ->whereIn('id', $mataKuliahIds)
                ->sum('sks');

            if ($totalSks > 24) {
                return back()->with('error', 'Total SKS tidak boleh melebihi 24 SKS. SKS yang dipilih: ' . $totalSks);
            }

            // Cek Double Submission & Ditolak
            $existingKrs = DB::table('krs_mahasiswa')
                ->where('mahasiswa_id', $mahasiswa->mahasiswa_id)
                ->where('semester_id', $semesterAktif->id)
                ->first();

            if ($existingKrs) {
                // Jika ditolak, hapus KRS lama agar bisa mengajukan ulang
                if ($existingKrs->status === 'ditolak') {
                    DB::table('krs_detail')->where('krs_mahasiswa_id', $existingKrs->id)->delete();
                    DB::table('krs_mahasiswa')->where('id', $existingKrs->id)->delete();
                } else {
                    // Jika menunggu/disetujui, tolak pengajuan
                    return back()->with('warning', 'Anda sudah mengajukan KRS di semester ini. Status: ' . ucfirst($existingKrs->status));
                }
            }

            DB::beginTransaction();

            $krsId = DB::table('krs_mahasiswa')->insertGetId([
                'mahasiswa_id' => $mahasiswa->mahasiswa_id,
                'semester_id' => $semesterAktif->id,
                'tahun_ajaran' => $semesterAktif->tahun_ajaran,
                'semester' => $semesterAktif->semester,
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
                ->with('success', 'KRS berhasil diajukan! Total SKS: ' . $totalSks . ' SKS. Menunggu persetujuan dosen wali.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Lihat KRS Mahasiswa yang sudah diambil
     */
    public function viewKrs()
    {
        $mahasiswa = $this->currentMahasiswa();

        if (!$mahasiswa) {
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
                        'mata_kuliah.sks'
                    )
                    ->get();

                $krs->details = $details;

                return $krs;
            })
            ->groupBy(function ($item) {
                return $item->tahun_ajaran . ' - Semester ' . $item->semester_nama;
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

    /**
     * Halaman Lihat KHS
     */
    public function lihatKhs(Request $request)
    {
        $mahasiswa = $this->currentMahasiswa();

        if (!$mahasiswa) {
            return redirect()->route('login');
        }

        $nilai = [];
        $ipk = 0;
        $totalSks = 0;
        $mataKuliahCount = 0;

        $data = [
            'nama' => $mahasiswa->nama ?? $mahasiswa->user_name ?? '-',
            'email' => $mahasiswa->email ?? $mahasiswa->user_email ?? '-',
        ];

        return view('pages.mahasiswa.lihat-khs', compact('nilai', 'ipk', 'totalSks', 'mataKuliahCount', 'data'));
    }
}