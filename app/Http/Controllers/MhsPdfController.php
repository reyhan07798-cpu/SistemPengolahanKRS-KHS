<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Khs;
use Illuminate\Support\Facades\Auth;

class MhsPdfController extends Controller
{
    public function exportKhsPdf()
    {
        // Ambil data mahasiswa yang login
        $mahasiswa = Auth::user();

        // Ambil data KHS berdasarkan mahasiswa
        $khs = Khs::where('mahasiswa_id', $mahasiswa->id)->get();

        // Data tambahan (bisa kamu ambil dari DB kalau ada tabelnya)
        $data = [
            'mahasiswa'   => $mahasiswa,
            'khs'         => $khs,
            'tahun'       => '2025/2026',
            'semester'    => 'GANJIL',
            'semester_ke' => 1,
            'kelas'       => 'SA1',
            'prodi'       => 'D3-Teknik Informatika',
            'pa'          => 'Rusyada Nazhirah Yunus, S.S., M.Si.',
            'kaprodi'     => 'YENI ROKHAYATI, S.Si., M.Sc',
            'nip'         => '198602192014042001'
        ];

        // Load view ke PDF
        $pdf = Pdf::loadView('mahasiswa.khs_pdf', $data);

        // Download PDF
        return $pdf->download('KHS_' . $mahasiswa->nama . '.pdf');

        // Alternatif:
        // return $pdf->stream('khs.pdf'); // tampil di browser
    }
}