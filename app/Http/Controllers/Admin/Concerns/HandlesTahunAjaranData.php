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

        $targetSemesterId = null;
        if ($existing) {
            unset($semesterData['created_at']);
            DB::table('semesters')->where('id', $existing->id)->update($semesterData);
            $targetSemesterId = $existing->id;
        } else {
            $targetSemesterId = DB::table('semesters')->insertGetId($semesterData);
        }

        // Jika semester ini aktif, lakukan propagasi status mahasiswa secara otomatis
        if ($isActive && $targetSemesterId && Schema::hasTable('mahasiswa') && Schema::hasTable('mahasiswa_semester')) {
            $this->autoPropagateStudentStatuses($targetSemesterId);
        }
    }

    protected function autoPropagateStudentStatuses(int $toSemesterId): void
    {
        // Dapatkan ID mahasiswa yang sudah memiliki status di semester baru ini
        $hasRecordIds = DB::table('mahasiswa_semester')
            ->where('semester_id', $toSemesterId)
            ->pluck('mahasiswa_id')
            ->toArray();

        // Cari mahasiswa yang belum memiliki status di semester baru ini
        $mahasiswaList = DB::table('mahasiswa')
            ->when(
                Schema::hasColumn('mahasiswa', 'deleted_at'),
                fn ($query) => $query->whereNull('mahasiswa.deleted_at')
            )
            ->whereNotIn('id', $hasRecordIds)
            ->get();

        foreach ($mahasiswaList as $mhs) {
            // Temukan record status semester terakhir dari mahasiswa ini
            $latestRecord = DB::table('mahasiswa_semester')
                ->where('mahasiswa_id', $mhs->id)
                ->orderByDesc('id')
                ->first();

            if ($latestRecord) {
                $status = $latestRecord->status;
                $newSemesterKe = (int) $latestRecord->semester_ke;

                // Naikkan semester jika sebelumnya aktif atau mengulang
                if (in_array($status, ['aktif', 'mengulang'])) {
                    $newSemesterKe = min(14, $newSemesterKe + 1);
                }
            } else {
                // Default untuk mahasiswa baru yang tidak punya riwayat semester sama sekali
                $status = 'aktif';
                $newSemesterKe = 1;
            }

            DB::table('mahasiswa_semester')->insert([
                'mahasiswa_id' => $mhs->id,
                'semester_id' => $toSemesterId,
                'semester_ke' => $newSemesterKe,
                'status' => $status,
                'catatan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Sinkronisasi kelas berdasarkan semester_ke yang baru
            $mahasiswaModel = \App\Models\Mahasiswa::with('prodi')->find($mhs->id);
            if ($mahasiswaModel && method_exists($this, 'syncMahasiswaKelasForSemester')) {
                $this->syncMahasiswaKelasForSemester($mahasiswaModel, $newSemesterKe);
            }
        }
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
