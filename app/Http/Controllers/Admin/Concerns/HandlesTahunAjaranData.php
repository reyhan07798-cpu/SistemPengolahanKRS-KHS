<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\TahunAjaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait HandlesTahunAjaranData
{
    protected function normalizeTahunAjaranStatus(?string $status): string
    {
        return strtolower((string) $status) === 'aktif' ? 'Aktif' : 'Nonaktif';
    }

    protected function syncTahunAjaranToSemester(TahunAjaran $tahunAjaran): void
    {
        if (! Schema::hasTable('semesters')) {
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
                fn ($query) => $query->where('semester', $tahunAjaran->semester),
                fn ($query) => $query->where('semester_ke', $this->semesterKeFromLabel($tahunAjaran->semester))
            )
            ->first();
        if ($existing) {
            unset($semesterData['created_at']);
            DB::table('semesters')->where('id', $existing->id)->update($semesterData);

            return;
        }
        DB::table('semesters')->insert($semesterData);
    }

    protected function setSemesterActiveState(string $semester, string $tahunAjaran, bool $isActive): void
    {
        if (! Schema::hasTable('semesters') || ! Schema::hasColumn('semesters', 'is_active')) {
            return;
        }
        DB::table('semesters')
            ->where('tahun_ajaran', $tahunAjaran)
            ->when(
                Schema::hasColumn('semesters', 'semester'),
                fn ($query) => $query->where('semester', $semester),
                fn ($query) => $query->where('semester_ke', $this->semesterKeFromLabel($semester))
            )
            ->update(['is_active' => $isActive ? 1 : 0]);
    }

    protected function semesterSyncData(string $semester, string $tahunAjaran, bool $isActive): array
    {
        [$mulai, $selesai] = $this->defaultSemesterDates($semester, $tahunAjaran);

        return $this->onlyExistingColumns('semesters', [
            'nama' => 'Semester '.$semester.' '.$tahunAjaran,
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

    protected function semesterKeFromLabel(string $semester): int
    {
        return strtolower($semester) === 'genap' ? 2 : 1;
    }

    protected function defaultSemesterDates(string $semester, string $tahunAjaran): array
    {
        [$tahunMulai, $tahunSelesai] = array_pad(explode('/', $tahunAjaran), 2, date('Y'));
        if (strtolower($semester) === 'genap') {
            return [$tahunSelesai.'-02-01', $tahunSelesai.'-07-31'];
        }

        return [$tahunMulai.'-08-01', $tahunSelesai.'-01-31'];
    }

    protected function onlyExistingColumns(string $table, array $data): array
    {
        $columns = Schema::getColumnListing($table);

        return collect($data)->only($columns)->toArray();
    }
    // ==========================================
    // 6. PAKET MATA KULIAH CRUD
    // ==========================================
}
