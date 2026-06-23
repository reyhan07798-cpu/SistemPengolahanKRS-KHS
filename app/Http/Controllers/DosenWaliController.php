<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DosenWaliController extends Controller
{
    // ── Helper: ambil dosen_id dari session ──────────────────────────
    private function dosenId(): int
    {
        return (int)(session('user.dosen_id') ?? 0);
    }

    private function dosenNik(): string
    {
        return (string)(session('user.nik') ?? '');
    }

    // ── Helper: ambil semester aktif ─────────────────────────────────
    private function semesterAktif()
    {
        return DB::table('semesters')->where('is_active', 1)->first();
    }

    // ── Helper: semua semester (untuk filter) ────────────────────────
    private function allSemesters()
    {
        return DB::table('semesters')->orderByDesc('id')->get();
    }

    // ── Helper: kelas unik mahasiswa bimbingan ───────────────────────
    private function kelasBimbingan(): array
    {
        $dosenId = $this->dosenId();
        if (!$dosenId) return [];
        return DB::table('mahasiswa')
            ->where('dosen_wali_id', $dosenId)
            ->whereNotNull('kelas')
            ->distinct()->pluck('kelas')->sort()->values()->toArray();
    }

    // ═════════════════════════════════════════════════════════════════
    //  BERANDA
    // ═════════════════════════════════════════════════════════════════
    public function index()
    {
        $dosenId = $this->dosenId();
        $semAktif = $this->semesterAktif();

        // Mahasiswa bimbingan
        $mahasiswaBimbingan = DB::table('mahasiswa')
            ->where('dosen_wali_id', $dosenId)
            ->get();

        $totalBimbingan = $mahasiswaBimbingan->count();
        $mhsIds = $mahasiswaBimbingan->pluck('id')->toArray();

        // KRS stats (semester aktif)
        $krsQuery = DB::table('krs_mahasiswa')
            ->whereIn('mahasiswa_id', $mhsIds);
        if ($semAktif) $krsQuery->where('semester_id', $semAktif->id);

        $krsAll      = $krsQuery->get();
        $krsMenunggu  = $krsAll->where('status', 'menunggu')->count();
        $krsDisetujui = $krsAll->where('status', 'disetujui')->count();
        $krsDitolak   = $krsAll->where('status', 'ditolak')->count();

        $stats = [
            'mahasiswa_bimbingan' => $totalBimbingan,
            'krs_menunggu'        => $krsMenunggu,
            'krs_disetujui'       => $krsDisetujui,
            'krs_ditolak'         => $krsDitolak,
        ];

        // Tabel mahasiswa bimbingan dengan IPK
        $mahasiswaList = $mahasiswaBimbingan->map(function ($m) use ($semAktif, $krsAll) {
            // Hitung IPK (semua semester)
            $nilaiMhs = DB::table('nilai')->where('mahasiswa_id', $m->id)->get();
            $totalMutuSks = $nilaiMhs->sum(fn($n) => (float)$n->bobot * (int)$n->sks);
            $totalSks     = $nilaiMhs->sum('sks');
            $ipk = $totalSks > 0 ? round($totalMutuSks / $totalSks, 2) : 0.00;

            // Status KRS semester aktif
            $krs = $krsAll->firstWhere('mahasiswa_id', $m->id);
            $statusKrs = $krs ? ucfirst($krs->status) : 'Belum Mengajukan';

            return [
                'nama'       => $m->nama,
                'nim'        => $m->nim,
                'kelas'      => $m->kelas ?? '-',
                'ipk'        => $ipk,
                'status_krs' => $statusKrs,
            ];
        })->toArray();

        return view('pages.dosen_wali.beranda', compact('stats', 'mahasiswaList'));
    }

    // ═════════════════════════════════════════════════════════════════
    //  KHS MAHASISWA BIMBINGAN
    // ═════════════════════════════════════════════════════════════════
    public function khs(Request $request)
    {
        $dosenId  = $this->dosenId();
        $allSem   = $this->allSemesters();
        $semAktif = $this->semesterAktif();
        $kelasList= $this->kelasBimbingan();

        // Default filter ke semester aktif
        $filterSemesterId  = $request->input('semester_id', $semAktif->id ?? '');
        $filterKelas       = $request->input('kelas', '');

        // Cari semester yang dipilih
        $semTerpilih = $allSem->firstWhere('id', $filterSemesterId);
        $isReadOnly  = $semTerpilih ? !$semTerpilih->is_active : true;

        // Mahasiswa bimbingan
        $query = DB::table('mahasiswa')
            ->where('mahasiswa.dosen_wali_id', $dosenId);

        if ($filterKelas) $query->where('mahasiswa.kelas', $filterKelas);

        $mahasiswaBimbingan = $query->get();
        $mhsIds = $mahasiswaBimbingan->pluck('id')->toArray();

        $mahasiswaList = $mahasiswaBimbingan->map(function ($m) use ($filterSemesterId, $semTerpilih) {
            // Nilai di semester terpilih
            $nilaiSem = DB::table('nilai')
                ->join('mata_kuliah','nilai.mata_kuliah_id','=','mata_kuliah.id')
                ->where('nilai.mahasiswa_id', $m->id)
                ->when($filterSemesterId, fn($q) => $q->where('nilai.semester_id', $filterSemesterId))
                ->select('nilai.*','mata_kuliah.nama as nama_mk','mata_kuliah.kode_mk')
                ->get();

            $totalMutuSks = $nilaiSem->sum(fn($n) => (float)$n->bobot * (int)$n->sks);
            $totalSks     = $nilaiSem->sum('sks');
            $ip           = $totalSks > 0 ? round($totalMutuSks / $totalSks, 2) : 0.00;

            // IPK kumulatif
            $allNilai = DB::table('nilai')->where('mahasiswa_id', $m->id)->get();
            $allMutuSks = $allNilai->sum(fn($n) => (float)$n->bobot * (int)$n->sks);
            $allSks     = $allNilai->sum('sks');
            $ipk        = $allSks > 0 ? round($allMutuSks / $allSks, 2) : 0.00;

            $mkLulus = $nilaiSem->filter(fn($n) => !in_array($n->nilai, ['D','E']))->count();

            return [
                'id'       => $m->id,
                'nim'      => $m->nim,
                'nama'     => $m->nama,
                'kelas'    => $m->kelas ?? '-',
                'mk_lulus' => $mkLulus,
                'ip'       => $ip,
                'ipk'      => $ipk,
            ];
        })->sortByDesc('ipk')->values()->map(function ($m, $i) {
            $m['ranking'] = $i + 1;
            return $m;
        })->toArray();

        $totalMahasiswa = count($mahasiswaList);
        $ipkTinggi = collect($mahasiswaList)->filter(fn($m) => $m['ipk'] >= 3.5)->count();

        return view('pages.dosen_wali.khs', compact(
            'mahasiswaList','allSem','filterSemesterId','filterKelas',
            'kelasList','totalMahasiswa','ipkTinggi','isReadOnly',
            'semAktif'
        ));
    }
}
