<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class MhsPdfController extends Controller
{
    private function currentMahasiswa()
    {
        $userSession = session('user');

        if (!$userSession) {
            return null;
        }

        $userId = $userSession['id'] ?? null;
        $nim = $userSession['nim'] ?? null;
        $email = $userSession['email'] ?? null;

        return DB::table('mahasiswa')
            ->leftJoin('users', 'mahasiswa.user_id', '=', 'users.id')
            ->select(
                'mahasiswa.id as mahasiswa_id',
                'mahasiswa.user_id',
                'mahasiswa.nim',
                'mahasiswa.nama',
                'mahasiswa.email',
                'mahasiswa.no_hp',
                'mahasiswa.alamat',
                'mahasiswa.angkatan',
                'mahasiswa.kelas',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->when($userId, function ($query) use ($userId) {
                $query->where('mahasiswa.user_id', $userId);
            })
            ->when(!$userId && $nim, function ($query) use ($nim) {
                $query->where('mahasiswa.nim', $nim);
            })
            ->when(!$userId && !$nim && $email, function ($query) use ($email) {
                $query->where('mahasiswa.email', $email);
            })
            ->first();
    }

    public function exportKhsPdf()
    {
        $mahasiswa = $this->currentMahasiswa();

        if (!$mahasiswa) {
            return redirect()->route('login')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $semesterAktif = DB::table('semesters')
            ->where('is_active', 1)
            ->first();

        if (!$semesterAktif) {
            return back()->with('error', 'Belum ada semester aktif.');
        }

        /*
        |--------------------------------------------------------------------------
        | Nilai yang dicetak: hanya semester aktif
        |--------------------------------------------------------------------------
        */
        $khs = DB::table('nilai')
            ->join('mata_kuliah', 'nilai.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->where('nilai.mahasiswa_id', $mahasiswa->mahasiswa_id)
            ->where('nilai.tahun_ajaran', $semesterAktif->tahun_ajaran)
            ->where('nilai.semester', $semesterAktif->semester_ke)
            ->where('nilai.status', 'final')
            ->select(
                'mata_kuliah.kode_mk as kode',
                'mata_kuliah.nama as mata_kuliah',
                'nilai.sks',
                'nilai.nilai',
                'nilai.bobot as angka',
                'nilai.tahun_ajaran',
                'nilai.semester'
            )
            ->orderBy('mata_kuliah.kode_mk', 'asc')
            ->get();

        $totalSks = $khs->sum('sks');

        $totalKn = $khs->sum(function ($item) {
            return ((int) $item->sks) * ((float) $item->angka);
        });

        // IPS semester aktif
        $ips = $totalSks > 0 ? round($totalKn / $totalSks, 2) : 0;

        /*
        |--------------------------------------------------------------------------
        | IPK Kumulatif sampai semester aktif
        |--------------------------------------------------------------------------
        */
        $nilaiKumulatif = DB::table('nilai')
            ->where('mahasiswa_id', $mahasiswa->mahasiswa_id)
            ->where('status', 'final')
            ->where(function ($query) use ($semesterAktif) {
                $query->where('tahun_ajaran', '<', $semesterAktif->tahun_ajaran)
                    ->orWhere(function ($subQuery) use ($semesterAktif) {
                        $subQuery->where('tahun_ajaran', $semesterAktif->tahun_ajaran)
                            ->where('semester', '<=', $semesterAktif->semester_ke);
                    });
            })
            ->get();

        $totalSksKumulatif = $nilaiKumulatif->sum('sks');

        $totalKnKumulatif = $nilaiKumulatif->sum(function ($item) {
            return ((int) $item->sks) * ((float) $item->bobot);
        });

        $ipkKumulatif = $totalSksKumulatif > 0
            ? round($totalKnKumulatif / $totalSksKumulatif, 2)
            : 0;

        $data = [
            'mahasiswa' => $mahasiswa,
            'khs' => $khs,

            'tahun' => $semesterAktif->tahun_ajaran,
            'semester' => $semesterAktif->semester,
            'semester_ke' => $semesterAktif->semester_ke,

            'kelas' => $mahasiswa->kelas ?? '-',
            'prodi' => 'Teknik Informatika',
            'pa' => 'Dosen Wali',
            'kaprodi' => 'Kepala Program Studi',
            'nip' => '-',

            'total_sks' => $totalSks,
            'total_kn' => $totalKn,
            'ips' => $ips,
            'ipk' => $ipkKumulatif,
        ];

        $pdf = Pdf::loadView('pages.mahasiswa.khs_pdf', $data)
            ->setPaper('A4', 'portrait');

        $namaFile = 'KHS_'
            . $mahasiswa->nim . '_'
            . str_replace('/', '-', $semesterAktif->tahun_ajaran)
            . '_Semester_'
            . $semesterAktif->semester
            . '.pdf';

        return $pdf->stream($namaFile);
    }
}