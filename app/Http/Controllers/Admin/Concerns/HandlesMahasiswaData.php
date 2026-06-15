<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

trait HandlesMahasiswaData
{
    protected function mahasiswaListQuery()
    {
        $query = DB::table('mahasiswa')
            ->select([
                'mahasiswa.id',
                'mahasiswa.dosen_wali_id',
                'mahasiswa.prodi_id',
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
                fn ($query) => $query->whereNull('mahasiswa.deleted_at')
            );
        if (Schema::hasTable('nilai')) {
            $ipkSubquery = DB::table('nilai')
                ->select(
                    'mahasiswa_id',
                    DB::raw('COALESCE(ROUND(SUM(COALESCE(bobot, 0) * COALESCE(sks, 0)) / NULLIF(SUM(COALESCE(sks, 0)), 0), 2), 0) as ipk')
                )
                ->groupBy('mahasiswa_id');
            $query->leftJoinSub($ipkSubquery, 'ipk_data', function ($join) {
                $join->on('mahasiswa.id', '=', 'ipk_data.mahasiswa_id');
            })->addSelect(DB::raw('COALESCE(ipk_data.ipk, 0) as ipk'));
        } else {
            $query->addSelect(DB::raw('0 as ipk'));
        }
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

    protected function getKelasGroupOptions()
    {
        return collect(['A', 'B', 'C', 'D']);
    }

    protected function getSesiKelasOptions()
    {
        return collect(['PAGI', 'MALAM']);
    }

    protected function getKelasOptions()
    {
        $kelas = collect(['IF1A-PAGI', 'IF1B-PAGI', 'IF2A-PAGI', 'IF2B-PAGI']);
        if (Schema::hasTable('kelas')) {
            $kelas = $kelas->merge(
                DB::table('kelas')
                    ->whereNotNull('nama_kelas')
                    ->pluck('nama_kelas')
            );
        }
        if (Schema::hasTable('mahasiswa')) {
            $kelas = $kelas->merge(
                DB::table('mahasiswa')
                    ->when(
                        Schema::hasColumn('mahasiswa', 'deleted_at'),
                        fn ($query) => $query->whereNull('deleted_at')
                    )
                    ->whereNotNull('kelas')
                    ->where('kelas', '!=', '')
                    ->pluck('kelas')
            );
        }
        if (Schema::hasTable('mata_kuliah') && Schema::hasColumn('mata_kuliah', 'kelas')) {
            $kelas = $kelas->merge(
                DB::table('mata_kuliah')
                    ->whereNotNull('kelas')
                    ->where('kelas', '!=', '')
                    ->pluck('kelas')
            );
        }

        return $kelas
            ->map(fn ($kelas) => $this->normalizeKelas($kelas))
            ->filter()
            ->unique()
            ->sort()
            ->values();
    }

    protected function normalizeKelas(?string $kelas): string
    {
        $kelas = strtoupper(trim((string) $kelas));
        $kelas = preg_replace('/\s+/', '-', $kelas);
        $kelas = preg_replace('/-+/', '-', $kelas);

        return trim($kelas, '-');
    }

    protected function buildKelasName(string $prodi, int $semesterKe, string $grup, string $sesi): string
    {
        $prefix = $this->prodiPrefix($prodi);
        $grup = strtoupper(trim($grup));
        $sesi = strtoupper(trim($sesi));

        return $this->normalizeKelas($prefix.$semesterKe.$grup.'-'.$sesi);
    }

    protected function parseKelasParts(?string $kelas): array
    {
        $kelas = $this->normalizeKelas($kelas);
        if (preg_match('/^(.+?)(\d{1,2})([A-Z])-(.+)$/', $kelas, $matches)) {
            return [
                'semester_ke' => (int) $matches[2],
                'kelas_grup' => $matches[3],
                'sesi_kelas' => strtoupper($matches[4]),
            ];
        }

        return [
            'semester_ke' => 1,
            'kelas_grup' => 'A',
            'sesi_kelas' => 'PAGI',
        ];
    }

    protected function syncMahasiswaKelasForSemester(Mahasiswa $mahasiswa, int $semesterKe): void
    {
        $parts = $this->parseKelasParts($mahasiswa->kelas);
        $prodi = optional($mahasiswa->prodi)->nama_prodi ?? '';
        if ($prodi === '' && $mahasiswa->prodi_id && Schema::hasTable('prodi')) {
            $prodi = (string) DB::table('prodi')->where('id', $mahasiswa->prodi_id)->value('nama_prodi');
        }
        if ($prodi === '') {
            return;
        }
        $mahasiswa->update([
            'kelas' => $this->buildKelasName(
                $prodi,
                $semesterKe,
                $parts['kelas_grup'],
                $parts['sesi_kelas']
            ),
        ]);
    }

    protected function getAngkatanOptions()
    {
        $tahunSekarang = (int) date('Y');
        $fallback = collect(range($tahunSekarang, $tahunSekarang - 5));
        if (! Schema::hasTable('mahasiswa')) {
            return $fallback;
        }
        $angkatans = Mahasiswa::whereNotNull('angkatan')
            ->distinct()
            ->pluck('angkatan')
            ->sortDesc()
            ->values();

        return $angkatans->isEmpty() ? $fallback : $angkatans;
    }

    protected function getDosenWaliOptions()
    {
        if (! Schema::hasTable('dosen')) {
            return collect();
        }

        return Dosen::orderBy('nama', 'asc')->get(['id', 'nama']);
    }

    protected function mahasiswaUserData(array $data, bool $includePassword = true): array
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
}
