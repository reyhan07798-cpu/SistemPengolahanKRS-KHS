<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait HandlesDashboardData
{
    protected function dashboardMahasiswaRankingQuery()
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
                fn ($query) => $query->whereNull('mahasiswa.deleted_at')
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
}
