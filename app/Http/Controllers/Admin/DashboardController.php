<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAdminData;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    use HandlesAdminData;

    public function dashboardAdmin()
    {
        $totalMahasiswa = Schema::hasTable('mahasiswa')
        ? DB::table('mahasiswa')
            ->when(
                Schema::hasColumn('mahasiswa', 'deleted_at'),
                fn ($query) => $query->whereNull('deleted_at')
            )
            ->count()
        : 0;
        $totalDosen = Schema::hasTable('dosen')
        ? DB::table('dosen')
            ->when(
                Schema::hasColumn('dosen', 'deleted_at'),
                fn ($query) => $query->whereNull('deleted_at')
            )
            ->count()
        : 0;
        $totalMataKuliah = Schema::hasTable('mata_kuliah')
        ? DB::table('mata_kuliah')
            ->when(
                Schema::hasColumn('mata_kuliah', 'deleted_at'),
                fn ($query) => $query->whereNull('deleted_at')
            )
            ->count()
        : 0;
        $prodis = $this->getProdiOptions();
        $mahasiswa = Schema::hasTable('mahasiswa')
        ? $this->dashboardMahasiswaRankingQuery()
            ->get()
            ->map(function ($mahasiswa) {
                $mahasiswa->ipk = (float) $mahasiswa->ipk;

                return $mahasiswa;
            })
        : collect();
        $mahasiswaDenganNilai = $mahasiswa->where('ipk', '>', 0);
        $avgIpk = $mahasiswaDenganNilai->count() > 0
        ? $mahasiswaDenganNilai->avg('ipk')
        : 0;
        $angkatans = $mahasiswa->pluck('angkatan')->unique()->sortDesc()->values();

        return view('pages.admin.dashboard_admin', compact(
            'mahasiswa', 'totalMahasiswa', 'totalDosen', 'totalMataKuliah', 'avgIpk', 'prodis', 'angkatans'
        ));
    }
    // ==========================================
    // 2. MAHASISWA CRUD
    // ==========================================
}
