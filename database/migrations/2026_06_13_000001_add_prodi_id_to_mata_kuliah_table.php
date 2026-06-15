<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('prodi')) {
            $informatika = DB::table('prodi')->where('kode_prodi', 'IF')->first()
                ?? DB::table('prodi')->where('nama_prodi', 'Teknik Informatika')->first();

            if ($informatika) {
                DB::table('prodi')->where('id', $informatika->id)->update([
                    'kode_prodi' => 'IF',
                    'nama_prodi' => 'Informatika',
                    'updated_at' => now(),
                ]);
            }

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
        }

        if (!Schema::hasTable('mata_kuliah')) {
            return;
        }

        if (!Schema::hasColumn('mata_kuliah', 'prodi_id')) {
            Schema::table('mata_kuliah', function (Blueprint $table) {
                $table->foreignId('prodi_id')
                    ->nullable()
                    ->after('semester_id')
                    ->constrained('prodi')
                    ->onDelete('set null');
            });
        }

        if (Schema::hasTable('prodi')) {
            $informatikaId = DB::table('prodi')->where('kode_prodi', 'IF')->value('id');

            if ($informatikaId) {
                DB::table('mata_kuliah')
                    ->whereNull('prodi_id')
                    ->update([
                        'prodi_id' => $informatikaId,
                        'updated_at' => now(),
                    ]);
            }
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('mata_kuliah') || !Schema::hasColumn('mata_kuliah', 'prodi_id')) {
            return;
        }

        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->dropConstrainedForeignId('prodi_id');
        });
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
