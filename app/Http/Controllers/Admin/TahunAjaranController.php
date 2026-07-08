<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAdminData;
use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class TahunAjaranController extends Controller
{
    use HandlesAdminData;

    // Menampilkan daftar tahun ajaran dari database.
    public function indexTahunAjaran()
    {
        if (Schema::hasTable('tahun_ajarans')) {
            $tahunAjaran = TahunAjaran::orderBy('created_at', 'desc')
                ->get()
                ->map(function ($tahunAjaran) {
                    $tahunAjaran->status = $this->normalizeTahunAjaranStatus($tahunAjaran->status);

                    return $tahunAjaran;
                });
        } else {
            $tahunAjaran = collect([
                (object) ['id' => 1, 'semester' => 'Ganjil', 'tahun_ajaran' => '2024/2025', 'status' => 'Nonaktif'],
                (object) ['id' => 2, 'semester' => 'Genap', 'tahun_ajaran' => '2025/2026', 'status' => 'Aktif'],
                (object) ['id' => 3, 'semester' => 'Ganjil', 'tahun_ajaran' => '2026/2027', 'status' => 'Nonaktif'],
                (object) ['id' => 4, 'semester' => 'Genap', 'tahun_ajaran' => '2027/2028', 'status' => 'Nonaktif'],
            ]);
        }

        return view('pages.admin.data_tahunajaran', compact('tahunAjaran'));
    }

    // Membuka halaman/form tambah tahun ajaran.
    public function createTahunAjaran()
    {
        return view('pages.admin.tahunajaran_create');
    }

    // Menyimpan tahun ajaran baru dan mengatur status aktifnya.
    public function storeTahunAjaran(Request $request)
    {
        $validated = $request->validate([
            'semester' => 'required|string|in:Ganjil,Genap',
            'tahun_ajaran' => [
                'required',
                'string',
                'max:20',
                'regex:/^\d{4}\/\d{4}$/',
                Rule::unique('tahun_ajarans', 'tahun_ajaran')
                    ->where(fn ($query) => $query->where('semester', $request->semester)),
            ],
        ], [
            'semester.required' => 'Semester wajib dipilih.',
            'semester.in' => 'Semester harus Ganjil atau Genap.',
            'tahun_ajaran.required' => 'Tahun ajaran wajib dipilih.',
            'tahun_ajaran.regex' => 'Format tahun ajaran harus seperti 2025/2026.',
            'tahun_ajaran.unique' => 'Kombinasi semester dan tahun ajaran sudah ada.',
        ]);
        try {
            $status = $request->has('status') ? 'Aktif' : 'Nonaktif';
            if (Schema::hasTable('tahun_ajarans')) {
                // Jika tahun ajaran baru aktif, tahun ajaran aktif sebelumnya dibuat nonaktif.
                DB::transaction(function () use ($validated, $status) {
                    if ($status === 'Aktif') {
                        TahunAjaran::where('status', 'Aktif')
                            ->orWhere('status', 'aktif')
                            ->update(['status' => 'Nonaktif']);
                    }
                    $tahunAjaran = TahunAjaran::create([
                        'semester' => $validated['semester'],
                        'tahun_ajaran' => $validated['tahun_ajaran'],
                        'status' => $status,
                    ]);
                    $this->syncTahunAjaranToSemester($tahunAjaran);
                });
            }

            return redirect()->route('pages.admin.tahunajaran.index')->with('success', 'Data tahun ajaran berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: '.$e->getMessage())->withInput();
        }
    }

    // Mengambil tahun ajaran yang akan diedit.
    public function editTahunAjaran($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);
        $tahunAjaran->status = $this->normalizeTahunAjaranStatus($tahunAjaran->status);

        return view('pages.admin.tahunajaran_edit', compact('tahunAjaran'));
    }

    // Memperbarui tahun ajaran dan menyinkronkan status ke tabel semester.
    public function updateTahunAjaran(Request $request, $id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);
        $validated = $request->validate([
            'semester' => 'required|string|in:Ganjil,Genap',
            'tahun_ajaran' => [
                'required',
                'string',
                'max:20',
                'regex:/^\d{4}\/\d{4}$/',
                Rule::unique('tahun_ajarans', 'tahun_ajaran')
                    ->where(fn ($query) => $query->where('semester', $request->semester))
                    ->ignore($tahunAjaran->id),
            ],
        ], [
            'semester.required' => 'Semester wajib dipilih.',
            'semester.in' => 'Semester harus Ganjil atau Genap.',
            'tahun_ajaran.required' => 'Tahun ajaran wajib dipilih.',
            'tahun_ajaran.regex' => 'Format tahun ajaran harus seperti 2025/2026.',
            'tahun_ajaran.unique' => 'Kombinasi semester dan tahun ajaran sudah ada.',
        ]);
        try {
            $status = $request->has('status') ? 'Aktif' : 'Nonaktif';
            DB::transaction(function () use ($tahunAjaran, $validated, $status) {
                // Hanya satu periode yang boleh aktif agar proses KRS tidak bingung.
                if ($status === 'Aktif') {
                    TahunAjaran::where('id', '!=', $tahunAjaran->id)
                        ->where(function ($query) {
                            $query->where('status', 'Aktif')
                                ->orWhere('status', 'aktif');
                        })
                        ->update(['status' => 'Nonaktif']);
                }
                $tahunAjaran->update([
                    'semester' => $validated['semester'],
                    'tahun_ajaran' => $validated['tahun_ajaran'],
                    'status' => $status,
                ]);
                $this->syncTahunAjaranToSemester($tahunAjaran);
            });

            return redirect()->route('pages.admin.tahunajaran.index')->with('success', 'Data tahun ajaran berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: '.$e->getMessage())->withInput();
        }
    }

    // Menghapus tahun ajaran dan menonaktifkan pasangan semesternya.
    public function destroyTahunAjaran($id)
    {
        try {
            if (Schema::hasTable('tahun_ajarans')) {
                $tahunAjaran = TahunAjaran::findOrFail($id);
                $this->setSemesterActiveState($tahunAjaran->semester, $tahunAjaran->tahun_ajaran, false);
                $tahunAjaran->delete();
            }

            return redirect()->route('pages.admin.tahunajaran.index')->with('success', 'Data tahun ajaran berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }
}
