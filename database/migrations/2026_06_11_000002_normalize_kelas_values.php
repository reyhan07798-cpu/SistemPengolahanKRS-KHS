<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach ([
            'mahasiswa' => 'kelas',
            'mata_kuliah' => 'kelas',
            'krs_mahasiswa' => 'kelas',
            'nilai' => 'kelas',
            'kelas' => 'nama_kelas',
        ] as $table => $column) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) {
                continue;
            }

            DB::table($table)
                ->whereNotNull($column)
                ->orderBy('id')
                ->chunkById(100, function ($rows) use ($table, $column) {
                    foreach ($rows as $row) {
                        $normalized = $this->normalizeKelas($row->{$column});

                        if ($normalized !== (string) $row->{$column}) {
                            DB::table($table)
                                ->where('id', $row->id)
                                ->update([$column => $normalized]);
                        }
                    }
                });
        }
    }

    public function down(): void
    {
        // Normalisasi kelas tidak dibalik agar data tetap konsisten.
    }

    private function normalizeKelas(?string $kelas): string
    {
        $kelas = strtoupper(trim((string) $kelas));
        $kelas = preg_replace('/\s+/', '-', $kelas);
        $kelas = preg_replace('/-+/', '-', $kelas);

        return trim($kelas, '-');
    }
};
