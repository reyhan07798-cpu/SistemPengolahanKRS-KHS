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

    public function index(Request $request)
    {
        $mahasiswa = $this->currentMahasiswa();

        if (!$mahasiswa) {
            return redirect()->route('login')
                ->with('error', 'Data mahasiswa tidak ditemukan. Silakan login ulang.');
        }

        $tahunFilter = $request->input('tahun_ajaran');
        $semesterFilter = $request->input('semester');

        $nilaiQuery = DB::table('nilai')
            ->join('mata_kuliah', 'nilai.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->leftJoin('semesters', 'nilai.semester_id', '=', 'semesters.id')
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
            ->orderBy('nilai.semester', 'asc');

        if ($tahunFilter) {
            $nilaiQuery->where('nilai.tahun_ajaran', $tahunFilter);
        }

        if ($semesterFilter) {
            $nilaiQuery->where('nilai.semester', $semesterFilter);
        }

        $nilai = $nilaiQuery->get()->map(function ($item) {
            $item->color = $this->nilaiColor($item->nilai);
            $item->angka = (float) $item->bobot;
            $item->kn = ((int) $item->sks) * ((float) $item->bobot);
            return $item;
        });

        $totalSks = $nilai->sum('sks');
        $totalKn = $nilai->sum('kn');
        $mataKuliahCount = $nilai->count();
        $ipk = $totalSks > 0 ? round($totalKn / $totalSks, 2) : 0;

        $listTahun = DB::table('nilai')
            ->where('mahasiswa_id', $mahasiswa->mahasiswa_id)
            ->select('tahun_ajaran')
            ->distinct()
            ->orderBy('tahun_ajaran', 'desc')
            ->pluck('tahun_ajaran');

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
            ->map(function ($item) {
                $ips = $item->sks > 0 ? round($item->total_kn / $item->sks, 2) : 0;

                $item->ips = $ips;
                $item->ipk = $ips;
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
            'ipk',
            'totalSks',
            'mataKuliahCount',
            'listTahun',
            'ipSemester',
            'tahunFilter',
            'semesterFilter'
        ));
    }
}