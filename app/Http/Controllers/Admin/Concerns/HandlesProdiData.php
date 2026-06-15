<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\Prodi;
use Illuminate\Support\Facades\Schema;

trait HandlesProdiData
{
    protected function getProdiOptions()
    {
        $fallback = collect([
            'Informatika',
            'Teknologi Rekayasa Perangkat Lunak',
            'Teknologi Geomatika',
            'Rekayasa Keamanan Siber',
            'Teknologi Rekayasa Multimedia Animasi',
            'Animasi',
            'Teknologi Permainan',
        ]);
        if (! Schema::hasTable('prodi')) {
            return $fallback;
        }
        $prodis = Prodi::whereIn('nama_prodi', $fallback->all())
            ->orderBy('nama_prodi')
            ->pluck('nama_prodi')
            ->filter()
            ->values();

        return $fallback
            ->merge($prodis)
            ->unique()
            ->sort()
            ->values();
    }

    protected function resolveProdiId(string $namaProdi): ?int
    {
        if (! Schema::hasTable('prodi')) {
            return null;
        }
        $prodi = Prodi::where('nama_prodi', $namaProdi)->first();
        if ($prodi) {
            return $prodi->id;
        }

        return Prodi::create([
            'kode_prodi' => $this->makeUniqueProdiCode($namaProdi),
            'nama_prodi' => $namaProdi,
        ])->id;
    }

    protected function makeUniqueProdiCode(string $namaProdi): string
    {
        $base = substr($this->prodiPrefix($namaProdi), 0, 10);
        $kode = $base;
        $counter = 1;
        while (Prodi::where('kode_prodi', $kode)->exists()) {
            $kode = substr($base, 0, 7).$counter;
            $counter++;
        }

        return substr($kode, 0, 10);
    }

    protected function prodiPrefix(string $namaProdi): string
    {
        $normalized = strtolower(trim($namaProdi));
        $map = [
            'informatika' => 'IF',
            'teknologi rekayasa perangkat lunak' => 'TRL',
            'teknologi geomatika' => 'TG',
            'rekayasa keamanan siber' => 'RKS',
            'teknologi rekayasa multimedia animasi' => 'TRMA',
            'teknologi permainan' => 'TP',
            'animasi' => 'ANIMASI',
        ];
        if (isset($map[$normalized])) {
            return $map[$normalized];
        }

        return strtoupper(collect(preg_split('/\s+/', $namaProdi))
            ->filter()
            ->map(fn ($word) => substr($word, 0, 1))
            ->implode('')) ?: 'PRD';
    }
}
