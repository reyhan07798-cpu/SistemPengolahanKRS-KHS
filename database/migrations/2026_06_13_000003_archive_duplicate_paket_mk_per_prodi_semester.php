<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('paket_mata_kuliahs')) {
            return;
        }

        $groups = DB::table('paket_mata_kuliahs')
            ->when(
                Schema::hasColumn('paket_mata_kuliahs', 'deleted_at'),
                fn($query) => $query->whereNull('deleted_at')
            )
            ->select('prodi_id', 'semester_id', DB::raw('COUNT(*) as total'))
            ->groupBy('prodi_id', 'semester_id')
            ->having('total', '>', 1)
            ->get();

        foreach ($groups as $group) {
            $ids = DB::table('paket_mata_kuliahs')
                ->where('prodi_id', $group->prodi_id)
                ->where('semester_id', $group->semester_id)
                ->when(
                    Schema::hasColumn('paket_mata_kuliahs', 'deleted_at'),
                    fn($query) => $query->whereNull('deleted_at')
                )
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->pluck('id');

            $keepId = $ids->first();
            $archiveIds = $ids->filter(fn($id) => (int) $id !== (int) $keepId)->values();

            if ($archiveIds->isEmpty()) {
                continue;
            }

            if (Schema::hasColumn('paket_mata_kuliahs', 'deleted_at')) {
                DB::table('paket_mata_kuliahs')
                    ->whereIn('id', $archiveIds)
                    ->update([
                        'deleted_at' => now(),
                        'updated_at' => now(),
                    ]);

                continue;
            }

            DB::table('paket_mata_kuliahs')->whereIn('id', $archiveIds)->delete();
        }
    }

    public function down(): void
    {
        // Paket lama yang sudah diarsipkan tidak dipulihkan otomatis agar tidak dobel lagi.
    }
};
