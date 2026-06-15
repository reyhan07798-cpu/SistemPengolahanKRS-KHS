<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

trait HandlesPaketMataKuliahData
{
    protected function paketMataKuliahListQuery()
    {
        $mataKuliahSummary = DB::table('paket_mata_kuliah_details')
            ->join('mata_kuliah', 'paket_mata_kuliah_details.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->when(
                Schema::hasColumn('mata_kuliah', 'deleted_at'),
                fn ($query) => $query->whereNull('mata_kuliah.deleted_at')
            )
            ->select(
                'paket_mata_kuliah_details.paket_mata_kuliah_id',
                'mata_kuliah.kode_mk',
                'mata_kuliah.nama',
                'mata_kuliah.sks'
            )
            ->groupBy(
                'paket_mata_kuliah_details.paket_mata_kuliah_id',
                'mata_kuliah.kode_mk',
                'mata_kuliah.nama',
                'mata_kuliah.sks'
            );

        return DB::table('paket_mata_kuliahs')
            ->when(
                Schema::hasColumn('paket_mata_kuliahs', 'deleted_at'),
                fn ($query) => $query->whereNull('paket_mata_kuliahs.deleted_at')
            )
            ->leftJoin('semesters', 'paket_mata_kuliahs.semester_id', '=', 'semesters.id')
            ->leftJoin('prodi', 'paket_mata_kuliahs.prodi_id', '=', 'prodi.id')
            ->leftJoinSub($mataKuliahSummary, 'mata_kuliah_summary', function ($join) {
                $join->on('paket_mata_kuliahs.id', '=', 'mata_kuliah_summary.paket_mata_kuliah_id');
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
                DB::raw('COALESCE(SUM(mata_kuliah_summary.sks), 0) as total_sks'),
                DB::raw('COUNT(mata_kuliah_summary.kode_mk) as jumlah_mk')
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

    protected function getMataKuliahOptions()
    {
        if (! Schema::hasTable('mata_kuliah')) {
            return collect();
        }
        $query = DB::table('mata_kuliah');
        if (Schema::hasTable('prodi') && Schema::hasColumn('mata_kuliah', 'prodi_id')) {
            $query->leftJoin('prodi', 'mata_kuliah.prodi_id', '=', 'prodi.id');
        }
        $query->select(
            DB::raw('MIN(mata_kuliah.id) as id'),
            DB::raw('mata_kuliah.kode_mk as kode'),
            'mata_kuliah.nama',
            'mata_kuliah.sks',
            'mata_kuliah.semester_ke',
            DB::raw('COUNT(DISTINCT mata_kuliah.kelas) as kelas_count')
        );
        if (Schema::hasColumn('mata_kuliah', 'prodi_id')) {
            $query->addSelect('mata_kuliah.prodi_id');
        } else {
            $query->addSelect(DB::raw('NULL as prodi_id'));
        }
        if (Schema::hasTable('prodi') && Schema::hasColumn('mata_kuliah', 'prodi_id')) {
            $query->addSelect(DB::raw("COALESCE(prodi.nama_prodi, '-') as prodi"));
        } else {
            $query->addSelect(DB::raw("'-' as prodi"));
        }
        $groupByColumns = [
            'mata_kuliah.kode_mk',
            'mata_kuliah.nama',
            'mata_kuliah.sks',
            'mata_kuliah.semester_ke',
        ];
        if (Schema::hasColumn('mata_kuliah', 'prodi_id')) {
            $groupByColumns[] = 'mata_kuliah.prodi_id';
        }
        if (Schema::hasTable('prodi') && Schema::hasColumn('mata_kuliah', 'prodi_id')) {
            $groupByColumns[] = 'prodi.nama_prodi';
        }

        return $query
            ->when(
                Schema::hasColumn('mata_kuliah', 'deleted_at'),
                fn ($query) => $query->whereNull('mata_kuliah.deleted_at')
            )
            ->groupBy($groupByColumns)
            ->orderBy('mata_kuliah.semester_ke')
            ->orderBy('mata_kuliah.kode_mk')
            ->get();
    }

    protected function getPaketSemesterOptions()
    {
        $fromMataKuliah = Schema::hasTable('mata_kuliah')
        ? DB::table('mata_kuliah')
            ->whereNotNull('semester_ke')
            ->distinct()
            ->pluck('semester_ke')
            ->map(fn ($semester) => (string) $semester)
        : collect();

        return collect(range(1, 8))
            ->map(fn ($semester) => (string) $semester)
            ->merge($fromMataKuliah)
            ->unique()
            ->sort()
            ->values();
    }

    protected function resolveSemesterId(int $semesterKe): ?int
    {
        if (! Schema::hasTable('semesters')) {
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
            ->value('tahun_ajaran') ?? date('Y').'/'.((int) date('Y') + 1);
        $label = $semesterKe % 2 === 0 ? 'Genap' : 'Ganjil';

        return DB::table('semesters')->insertGetId([
            'nama' => 'Semester '.$semesterKe.' '.$tahunAjaran,
            'tahun_ajaran' => $tahunAjaran,
            'semester' => $label,
            'semester_ke' => $semesterKe,
            'tanggal_mulai' => date('Y').'-01-01',
            'tanggal_selesai' => date('Y').'-06-30',
            'is_active' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    protected function syncPaketMataKuliahDetails(int $paketId, array $mataKuliahIds): void
    {
        $rows = collect($mataKuliahIds)
            ->unique()
            ->map(fn ($mataKuliahId) => [
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

    protected function canonicalMataKuliahIds(array $mataKuliahIds): array
    {
        $rows = DB::table('mata_kuliah')
            ->whereIn('id', collect($mataKuliahIds)->unique()->values())
            ->get(['kode_mk', 'nama', 'sks', 'semester_ke', 'prodi_id']);
        if ($rows->isEmpty()) {
            return [];
        }
        $canonicalIds = collect();
        foreach ($rows as $row) {
            $canonicalQuery = DB::table('mata_kuliah')
                ->where('kode_mk', $row->kode_mk)
                ->where('nama', $row->nama)
                ->where('sks', $row->sks)
                ->where('semester_ke', $row->semester_ke);
            if (Schema::hasColumn('mata_kuliah', 'prodi_id')) {
                $canonicalQuery->where('prodi_id', $row->prodi_id);
            }
            $canonicalIds->push((int) $canonicalQuery->min('id'));
        }

        return $canonicalIds
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }

    protected function archiveExistingPaketMataKuliah(?int $prodiId, ?int $semesterId, ?int $exceptId = null): void
    {
        if (! $prodiId || ! $semesterId || ! Schema::hasTable('paket_mata_kuliahs')) {
            return;
        }
        $query = DB::table('paket_mata_kuliahs')
            ->where('prodi_id', $prodiId)
            ->where('semester_id', $semesterId)
            ->when($exceptId, fn ($query) => $query->where('id', '!=', $exceptId));
        if (Schema::hasColumn('paket_mata_kuliahs', 'deleted_at')) {
            $query->whereNull('deleted_at')->update([
                'deleted_at' => now(),
                'updated_at' => now(),
            ]);

            return;
        }
        $query->delete();
    }

    protected function validatePaketMataKuliahSks(array $mataKuliahIds): void
    {
        $totalSks = (int) DB::table('mata_kuliah')
            ->whereIn('id', collect($mataKuliahIds)->unique()->values())
            ->sum('sks');
        if ($totalSks > 24) {
            throw ValidationException::withMessages([
                'mata_kuliah' => 'Total SKS paket tidak boleh lebih dari 24 SKS. Total saat ini: '.$totalSks.' SKS.',
            ]);
        }
    }

    protected function validatePaketMataKuliahScope(array $mataKuliahIds, int $semesterKe, ?int $prodiId): void
    {
        $uniqueIds = collect($mataKuliahIds)->unique()->values();
        $query = DB::table('mata_kuliah')
            ->whereIn('id', $uniqueIds)
            ->where('semester_ke', $semesterKe);
        if (Schema::hasColumn('mata_kuliah', 'prodi_id') && $prodiId) {
            $query->where('prodi_id', $prodiId);
        }
        $validCount = $query->count();
        if ($validCount !== $uniqueIds->count()) {
            throw ValidationException::withMessages([
                'mata_kuliah' => 'Mata kuliah yang dipilih harus sesuai dengan semester dan program studi paket.',
            ]);
        }
    }
}
