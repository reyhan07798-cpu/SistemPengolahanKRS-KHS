<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
class DosenMKSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(MahasiswaDosenMataKuliahSeeder::class);
    }
}
