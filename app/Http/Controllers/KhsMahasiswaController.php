<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KhsMahasiswaController extends Controller
{
    private function currentUserSession(): ?array
    {
        return session('user');
    }

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
                'users.name as user_name',
                'users.email as user_email',
                'users.username',
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

    private function nilaiColor($nilai): string
    {
        return match ($nilai) {
            'A' => '#22c55e',
            'A-' => '#84cc16',
            'B+' => '#eab308',
            'B' => '#f97316',
            'B-' => '#ef4444',
            'C+' => '#dc2626',
            'C' => '#7f1d1d',
            'D' => '#4b5563',
            'E' => '#1f2937',
            default => '#666666',
        };
    }

    private function predikat($ip): array
    {
        if ($ip >= 3.75) {
            return ['label' => 'Dengan Pujian', 'badge' => 'nb-badge-success'];
        }

        if ($ip >= 3.50) {
            return ['label' => 'Sangat Memuaskan', 'badge' => 'nb-badge-primary'];
        }

        if ($ip >= 3.00) {
            return ['label' => 'Memuaskan', 'badge' => 'nb-badge-warning'];
        }

        return ['label' => 'Cukup', 'badge' => 'nb-badge-stable'];
    }

    private function convertSemesterFilter($semesterFilter)
    {
        if (!$semesterFilter) {
            return null;
        }

        $semesterMap = [
            'Ganjil' => 1,
            'Genap' => 2,
            'ganjil' => 1,
            'genap' => 2,
            '1' => 1,
            '2' => 2,
            1 => 1,
            2 => 2,
        ];

        return $semesterMap[$semesterFilter] ?? $semesterFilter;
    }

    public function index(Request $request)
    {
        $mahasiswa = $this->currentMahasiswa();

        if (!$mahasiswa) {
            return redirect()->route('login')
                ->with('error', 'Data mahasiswa tidak ditemukan. Silakan login ulang.');
        }

        $tahunFilter = $request->input('tahun_ajaran');
        $semesterFilter = $request->input('semester');
        $semesterValue = $this->convertSemesterFilter($semesterFilter);

        /*
        |--------------------------------------------------------------------------
        | Ambil nilai untuk Daftar Nilai
        |--------------------------------------------------------------------------
        */
        $nilaiQuery = DB::table('nilai')
            ->join('mata_kuliah', 'nilai.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->where('nilai.mahasiswa_id', $mahasiswa->mahasiswa_id)
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
            ->orderBy('nilai.tahun_ajaran', 'desc')
            ->orderBy('nilai.semester', 'desc')
            ->orderBy('mata_kuliah.kode_mk', 'asc');

        if ($tahunFilter) {
            $nilaiQuery->where('nilai.tahun_ajaran', $tahunFilter);
        }

        if ($semesterValue) {
            $nilaiQuery->where('nilai.semester', $semesterValue);
        }

        $nilai = $nilaiQuery->get()->map(function ($item) {
            $item->color = $this->nilaiColor($item->nilai);
            $item->angka = (float) $item->bobot;
            $item->kn = ((int) $item->sks) * ((float) $item->bobot);
            return $item;
        });

        $nilaiCount = $nilai->count();

        /*
        |--------------------------------------------------------------------------
        | Ambil semester aktif
        |--------------------------------------------------------------------------
        */
        $semesterAktif = DB::table('semesters')
            ->where('is_active', 1)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Card Atas: IPS, Total SKS, dan Mata Kuliah Semester Aktif
        |--------------------------------------------------------------------------
        */
        $nilaiSemesterAktif = collect();

        if ($semesterAktif) {
            $nilaiSemesterAktif = DB::table('nilai')
                ->where('mahasiswa_id', $mahasiswa->mahasiswa_id)
                ->where('tahun_ajaran', $semesterAktif->tahun_ajaran)
                ->where('semester', $semesterAktif->semester_ke)
                ->get()
                ->map(function ($item) {
                    $item->kn = ((int) $item->sks) * ((float) $item->bobot);
                    return $item;
                });
        }

        $totalSks = $nilaiSemesterAktif->sum('sks');
        $totalKn = $nilaiSemesterAktif->sum('kn');
        $mataKuliahCount = $nilaiSemesterAktif->count();

        // Ini IPS, karena hanya semester aktif
        $ipsSemesterAktif = $totalSks > 0 ? round($totalKn / $totalSks, 2) : 0;

        /*
        |--------------------------------------------------------------------------
        | List Tahun Ajaran untuk Filter
        |--------------------------------------------------------------------------
        */
        $listTahun = DB::table('nilai')
            ->where('mahasiswa_id', $mahasiswa->mahasiswa_id)
            ->select('tahun_ajaran')
            ->distinct()
            ->orderBy('tahun_ajaran', 'desc')
            ->pluck('tahun_ajaran');

        /*
        |--------------------------------------------------------------------------
        | Tabel Indeks Prestasi Per Semester
        |--------------------------------------------------------------------------
        | IPS = per semester
        | IPK Kumulatif = akumulasi dari semester awal sampai semester tersebut
        |--------------------------------------------------------------------------
        */
        $runningSks = 0;
        $runningKn = 0;

        $ipSemester = DB::table('nilai')
            ->where('mahasiswa_id', $mahasiswa->mahasiswa_id)
            ->select('tahun_ajaran', 'semester')
            ->selectRaw('COUNT(*) as mk')
            ->selectRaw('SUM(sks) as sks')
            ->selectRaw('SUM(sks * bobot) as total_kn')
            ->groupBy('tahun_ajaran', 'semester')
            ->orderBy('tahun_ajaran', 'asc')
            ->orderBy('semester', 'asc')
            ->get()
            ->map(function ($item) use (&$runningSks, &$runningKn) {
                $sksSemester = (int) $item->sks;
                $knSemester = (float) $item->total_kn;

                $ips = $sksSemester > 0 ? round($knSemester / $sksSemester, 2) : 0;

                $runningSks += $sksSemester;
                $runningKn += $knSemester;

                $ipkKumulatif = $runningSks > 0 ? round($runningKn / $runningSks, 2) : 0;

                $item->ips = $ips;
                $item->ipk = $ipkKumulatif;
                $item->predikat = $this->predikat($ips);

                return $item;
            });

        $data = [
            'nama' => $mahasiswa->nama ?? $mahasiswa->user_name ?? '-',
            'nim' => $mahasiswa->nim ?? '-',
            'email' => $mahasiswa->email ?? $mahasiswa->user_email ?? '-',
            'kelas' => $mahasiswa->kelas ?? '-',
            'angkatan' => $mahasiswa->angkatan ?? '-',
            'program_studi' => 'Teknik Informatika',
        ];

        return view('pages.mahasiswa.lihat-khs', compact(
            'data',
            'nilai',
            'nilaiCount',
            'ipsSemesterAktif',
            'totalSks',
            'mataKuliahCount',
            'listTahun',
            'ipSemester',
            'tahunFilter',
            'semesterFilter',
            'semesterAktif'
        ));
    }
}