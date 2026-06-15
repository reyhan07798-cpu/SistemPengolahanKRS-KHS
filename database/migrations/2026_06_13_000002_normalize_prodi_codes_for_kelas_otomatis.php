<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('prodi')) {
            return;
        }

        $this->renameCode('TRPL', 'TRL', 'Teknologi Rekayasa Perangkat Lunak');
        $this->renameCode('A', 'ANIMASI', 'Animasi');

        foreach ($this->prodis() as $kode => $nama) {
            DB::table('prodi')->updateOrInsert(
                ['kode_prodi' => $kode],
                [
                    'nama_prodi' => $nama,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        $this->deleteIfUnused('SI');
    }

    public function down(): void
    {
        // Normalisasi kode prodi tidak dibalik agar relasi data tetap stabil.
    }

    private function renameCode(string $from, string $to, string $name): void
    {
        $old = DB::table('prodi')->where('kode_prodi', $from)->first();
        if (!$old) {
            return;
        }

        $target = DB::table('prodi')->where('kode_prodi', $to)->first();
        if (!$target) {
            DB::table('prodi')->where('id', $old->id)->update([
                'kode_prodi' => $to,
                'nama_prodi' => $name,
                'updated_at' => now(),
            ]);

            return;
        }

        $this->moveReferences($old->id, $target->id);
        DB::table('prodi')->where('id', $old->id)->delete();
    }

    private function deleteIfUnused(string $kode): void
    {
        $prodi = DB::table('prodi')->where('kode_prodi', $kode)->first();
        if (!$prodi || $this->isUsed((int) $prodi->id)) {
            return;
        }

        DB::table('prodi')->where('id', $prodi->id)->delete();
    }

    private function moveReferences(int $fromId, int $toId): void
    {
        foreach (['mahasiswa', 'mata_kuliah', 'paket_mata_kuliahs'] as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'prodi_id')) {
                DB::table($table)->where('prodi_id', $fromId)->update(['prodi_id' => $toId]);
            }
        }
    }

    private function isUsed(int $prodiId): bool
    {
        foreach (['mahasiswa', 'mata_kuliah', 'paket_mata_kuliahs'] as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'prodi_id')) {
                if (DB::table($table)->where('prodi_id', $prodiId)->exists()) {
                    return true;
                }
            }
        }

        return false;
    }

    private function prodis(): array
    {
        return [
            'IF' => 'Informatika',
            'TRL' => 'Teknologi Rekayasa Perangkat Lunak',
            'TG' => 'Teknologi Geomatika',
            'RKS' => 'Rekayasa Keamanan Siber',
            'TRMA' => 'Teknologi Rekayasa Multimedia Animasi',
            'ANIMASI' => 'Animasi',
            'TP' => 'Teknologi Permainan',
        ];
    }
};
