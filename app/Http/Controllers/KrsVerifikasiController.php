<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KrsVerifikasiController extends Controller
{
    private function dosenId(): int
    {
        return (int)(session('user.dosen_id') ?? 0);
    }

    private function semesterAktif()
    {
        return DB::table('semesters')->where('is_active', 1)->first();
    }

    private function normalizeKelas(?string $kelas): string
    {
        $kelas = strtoupper(trim((string) $kelas));
        $kelas = preg_replace('/\s+/', '-', $kelas);
        $kelas = preg_replace('/-+/', '-', $kelas);

        return trim($kelas, '-');
    }

    private function isDetailMkValidForKrs($krs, $mk): bool
    {
        // Valid if semester_ke matches. Kelas tidak lagi menjadi syarat
        // karena paket MK dikelompokkan berdasarkan semester, bukan kelas.
        return (int) $mk->semester_ke === (int) $krs->semester_ke;
    }

    // ═══════════════════════════════════════════════════════
    //  INDEX — daftar KRS mahasiswa bimbingan
    // ═══════════════════════════════════════════════════════
    public function index(Request $request)
    {
        $dosenId  = $this->dosenId();
        $semAktif = $this->semesterAktif();
        $allSem   = DB::table('semesters')->orderByDesc('id')->get();

        // Default ke semester aktif
        $filterSemesterId = $request->input('semester_id', $semAktif->id ?? '');
        $filterStatus     = $request->input('status', 'semua');
        $filterKelas      = $request->input('kelas', 'semua');

        // Cek apakah semester terpilih aktif
        $semTerpilih = $allSem->firstWhere('id', $filterSemesterId);
        $isReadOnly  = $semTerpilih ? !$semTerpilih->is_active : false;

        // Kelas unik dari mahasiswa bimbingan
        $kelasList = DB::table('mahasiswa')
            ->where('dosen_wali_id', $dosenId)
            ->whereNotNull('kelas')->distinct()->pluck('kelas')
            ->sort()->values()->toArray();

        // Query KRS mahasiswa bimbingan
        $query = DB::table('krs_mahasiswa')
            ->join('mahasiswa', 'krs_mahasiswa.mahasiswa_id', '=', 'mahasiswa.id')
            ->join('semesters', 'krs_mahasiswa.semester_id', '=', 'semesters.id')
            ->where('mahasiswa.dosen_wali_id', $dosenId)
            ->select(
                'krs_mahasiswa.id as krs_id',
                'krs_mahasiswa.mahasiswa_id',
                'krs_mahasiswa.status',
                'krs_mahasiswa.total_sks',
                'krs_mahasiswa.catatan',
                'krs_mahasiswa.updated_at as tanggal',
                'mahasiswa.nim',
                'mahasiswa.nama',
                'mahasiswa.kelas',
                'semesters.tahun_ajaran',
                'semesters.semester',
                'semesters.id as semester_id',
                'semesters.is_active'
            );

        if ($filterSemesterId) $query->where('krs_mahasiswa.semester_id', $filterSemesterId);
        if ($filterStatus !== 'semua') $query->where('krs_mahasiswa.status', $filterStatus);
        if ($filterKelas  !== 'semua') $query->where('mahasiswa.kelas', $filterKelas);

        $daftarKrs = $query->orderBy('mahasiswa.nama')->get()->map(function ($krs) {
            // Hitung jumlah MK
            $mkCount = DB::table('krs_detail')
                ->where('krs_mahasiswa_id', $krs->krs_id)->count();

            return [
                'krs_id'       => $krs->krs_id,
                'mahasiswa_id' => $krs->mahasiswa_id,
                'nim'          => $krs->nim,
                'nama'         => $krs->nama,
                'kelas'        => $krs->kelas ?? '-',
                'mk_count'     => $mkCount,
                'total_sks'    => $krs->total_sks,
                'status'       => ucfirst($krs->status),
                'catatan'      => $krs->catatan,
                'tanggal'      => $krs->tanggal
                    ? \Carbon\Carbon::parse($krs->tanggal)->format('d/m/Y')
                    : '-',
                'tahun_ajaran' => $krs->tahun_ajaran,
                'semester'     => $krs->semester,
                'is_active'    => (bool)$krs->is_active,
            ];
        })->toArray();

        // Stats
        $allKrsDosenSem = DB::table('krs_mahasiswa')
            ->join('mahasiswa','krs_mahasiswa.mahasiswa_id','=','mahasiswa.id')
            ->where('mahasiswa.dosen_wali_id', $dosenId)
            ->when($filterSemesterId, fn($q) => $q->where('krs_mahasiswa.semester_id', $filterSemesterId))
            ->get();

        $stats = [
            'menunggu'  => $allKrsDosenSem->where('status','menunggu')->count(),
            'disetujui' => $allKrsDosenSem->where('status','disetujui')->count(),
            'ditolak'   => $allKrsDosenSem->where('status','ditolak')->count(),
        ];

        return view('pages.dosen_wali.krs-verifikasi', compact(
            'stats','daftarKrs','allSem',
            'filterSemesterId','filterStatus','filterKelas',
            'kelasList','isReadOnly','semAktif'
        ));
    }

    // ═══════════════════════════════════════════════════════
    //  DETAIL KRS — daftar MK yang diambil mahasiswa
    // ═══════════════════════════════════════════════════════
    public function detail($krsId)
    {
        $krs = DB::table('krs_mahasiswa')
            ->join('mahasiswa','krs_mahasiswa.mahasiswa_id','=','mahasiswa.id')
            ->join('semesters','krs_mahasiswa.semester_id','=','semesters.id')
            ->where('krs_mahasiswa.id', $krsId)
            ->select('krs_mahasiswa.*','mahasiswa.nim','mahasiswa.nama','mahasiswa.kelas',
                     'semesters.tahun_ajaran','semesters.semester','semesters.is_active')
            ->first();

        if (!$krs) abort(404);

        $detailMK = DB::table('krs_detail')
            ->join('mata_kuliah','krs_detail.mata_kuliah_id','=','mata_kuliah.id')
            ->leftJoin('dosen','mata_kuliah.dosen_id','=','dosen.id')
            ->where('krs_detail.krs_mahasiswa_id', $krsId)
            ->select('mata_kuliah.id','mata_kuliah.kode_mk','mata_kuliah.nama','mata_kuliah.sks',
                     'mata_kuliah.semester_ke',
                     'mata_kuliah.kelas as kelas_mk','dosen.nama as nama_dosen')
            ->get()
            ->map(function ($mk) use ($krs) {
                $mk->is_valid_for_krs = $this->isDetailMkValidForKrs($krs, $mk);

                return $mk;
            });

        $isReadOnly = !(bool)$krs->is_active;
        $hasInvalidDetail = $detailMK->contains(fn ($mk) => ! $mk->is_valid_for_krs);

        return view('pages.dosen_wali.krs-detail', compact('krs','detailMK','isReadOnly','hasInvalidDetail'));
    }

    // ═══════════════════════════════════════════════════════
    //  APPROVE KRS
    // ═══════════════════════════════════════════════════════
    public function approve($krsId)
    {
        $krs = DB::table('krs_mahasiswa')
            ->join('semesters','krs_mahasiswa.semester_id','=','semesters.id')
            ->where('krs_mahasiswa.id', $krsId)
            ->select('krs_mahasiswa.*','semesters.is_active')
            ->first();

        if (!$krs) return redirect()->back()->with('error', 'KRS tidak ditemukan.');
        if (!$krs->is_active) return redirect()->back()->with('error', 'Tidak bisa memverifikasi KRS di semester yang tidak aktif.');

        $invalidDetailExists = DB::table('krs_detail')
            ->join('mata_kuliah','krs_detail.mata_kuliah_id','=','mata_kuliah.id')
            ->where('krs_detail.krs_mahasiswa_id', $krsId)
            ->get(['mata_kuliah.semester_ke','mata_kuliah.kelas as kelas_mk'])
            ->contains(fn ($mk) => ! $this->isDetailMkValidForKrs($krs, $mk));

        if ($invalidDetailExists) {
            return redirect()->back()->with('error', 'KRS tidak bisa disetujui karena ada mata kuliah yang tidak sesuai semester atau kelas mahasiswa.');
        }

        DB::table('krs_mahasiswa')->where('id', $krsId)->update([
            'status'     => 'disetujui',
            'catatan'    => null,
            'updated_at' => now(),
        ]);

        $nama = DB::table('mahasiswa')->where('id', $krs->mahasiswa_id)->value('nama') ?? '';
        return redirect()->back()->with('success', "KRS $nama berhasil disetujui.");
    }

    // ═══════════════════════════════════════════════════════
    //  REJECT KRS
    // ═══════════════════════════════════════════════════════
    public function reject(Request $request, $krsId)
    {
        $request->validate(['catatan' => 'required|string|max:500']);

        $krs = DB::table('krs_mahasiswa')
            ->join('semesters','krs_mahasiswa.semester_id','=','semesters.id')
            ->where('krs_mahasiswa.id', $krsId)
            ->select('krs_mahasiswa.*','semesters.is_active')
            ->first();

        if (!$krs) return redirect()->back()->with('error', 'KRS tidak ditemukan.');
        if (!$krs->is_active) return redirect()->back()->with('error', 'Tidak bisa menolak KRS di semester yang tidak aktif.');

        DB::table('krs_mahasiswa')->where('id', $krsId)->update([
            'status'     => 'ditolak',
            'catatan'    => $request->catatan,
            'updated_at' => now(),
        ]);

        $nama = DB::table('mahasiswa')->where('id', $krs->mahasiswa_id)->value('nama') ?? '';
        return redirect()->back()->with('error', "KRS $nama ditolak.");
    }
}
