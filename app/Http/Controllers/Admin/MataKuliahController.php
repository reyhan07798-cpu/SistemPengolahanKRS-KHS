<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAdminData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class MataKuliahController extends Controller
{
    use HandlesAdminData;

    // Menampilkan daftar mata kuliah beserta dosen pengampu, semester, dan prodi.
    public function indexMatakuliah()
    {
        $dosens = $this->getDosenPengampuOptions();

        $semesters = collect(['1', '2', '3', '4', '5', '6', '7', '8']);
        $matakuliah = collect();

        if (Schema::hasTable('mata_kuliah')) {
            $query = DB::table('mata_kuliah')
                ->leftJoin('dosen', 'mata_kuliah.dosen_id', '=', 'dosen.id');

            if (Schema::hasTable('prodi') && Schema::hasColumn('mata_kuliah', 'prodi_id')) {
                $query->leftJoin('prodi', 'mata_kuliah.prodi_id', '=', 'prodi.id');
            }

            $query->select(
                'mata_kuliah.id',
                'mata_kuliah.kode_mk',
                'mata_kuliah.nama',
                'mata_kuliah.sks',
                'mata_kuliah.semester_ke',
                'mata_kuliah.dosen_id',
                'mata_kuliah.created_at',
                'mata_kuliah.updated_at',
                'dosen.nama as dosen_pengampu'
            );

            if (Schema::hasColumn('mata_kuliah', 'dosen_nik')) {
                $query->addSelect('mata_kuliah.dosen_nik');
            }

            if (Schema::hasColumn('mata_kuliah', 'prodi_id')) {
                $query->addSelect('mata_kuliah.prodi_id');
            } else {
                $query->addSelect(DB::raw('NULL as prodi_id'));
            }

            if (Schema::hasTable('prodi') && Schema::hasColumn('mata_kuliah', 'prodi_id')) {
                $query->addSelect(DB::raw("COALESCE(prodi.nama_prodi, '-') as prodi"));
            } else {
                $query->addSelect(DB::raw("'-' as prodi"));
            }

            $matakuliah = $query
                ->when(
                    Schema::hasColumn('mata_kuliah', 'deleted_at'),
                    fn ($query) => $query->whereNull('mata_kuliah.deleted_at')
                )
                ->orderBy('mata_kuliah.semester_ke', 'asc')
                ->orderBy('mata_kuliah.kode_mk', 'asc')
                ->get();
        }

        $prodis = $this->getProdiOptions();

        return view('pages.admin.data_matakuliah', compact(
            'matakuliah',
            'dosens',
            'semesters',
            'prodis'
        ));
    }

    // Membuka halaman/form tambah mata kuliah.
    public function createMatakuliah()
    {
        $dosens = $this->getDosenPengampuOptions();

        $semesters = collect(['1', '2', '3', '4', '5', '6', '7', '8']);
        $prodis = $this->getProdiOptions();

        return view('pages.admin.matakuliah_create', compact(
            'dosens',
            'semesters',
            'prodis'
        ));
    }

    // Menyimpan mata kuliah baru.
    public function storeMatakuliah(Request $request)
    {
        $kodeRule = Rule::unique('mata_kuliah', 'kode_mk');

        if (Schema::hasColumn('mata_kuliah', 'deleted_at')) {
            $kodeRule->whereNull('deleted_at');
        }

        $validated = $request->validate([
            'kode_mk' => ['required', 'string', 'max:30', $kodeRule],
            'nama' => 'required|string|max:100',
            'sks' => 'required|integer|min:1|max:6',
            'semester_ke' => 'required|integer|min:1|max:8',
            'prodi' => 'required|string|max:100',
            'dosen_id' => 'nullable|exists:dosen,id',
        ], [
            'kode_mk.required' => 'Kode mata kuliah wajib diisi.',
            'kode_mk.unique' => 'Kode mata kuliah sudah digunakan.',
            'nama.required' => 'Nama mata kuliah wajib diisi.',
            'sks.required' => 'SKS wajib diisi.',
            'semester_ke.required' => 'Semester wajib dipilih.',
            'prodi.required' => 'Program studi wajib dipilih.',
        ]);

        // Dosen pengampu harus sesuai prodi dan memang bertipe dosen mata kuliah.
        if (! $this->isValidDosenPengampuForProdi($validated['dosen_id'] ?? null, $validated['prodi'])) {
            return back()
                ->withErrors(['dosen_id' => 'Dosen pengampu harus dosen mata kuliah dari program studi yang dipilih.'])
                ->withInput();
        }

        try {
            $dosen = !empty($validated['dosen_id'])
                ? DB::table('dosen')->where('id', $validated['dosen_id'])->first()
                : null;

            $data = [
                'dosen_id' => $validated['dosen_id'] ?? null,
                'kode_mk' => $validated['kode_mk'],
                'nama' => $validated['nama'],
                'sks' => $validated['sks'],
                'semester_ke' => $validated['semester_ke'],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (Schema::hasColumn('mata_kuliah', 'dosen_nik')) {
                $data['dosen_nik'] = $dosen?->nik;
            }

            if (Schema::hasColumn('mata_kuliah', 'prodi_id')) {
                $data['prodi_id'] = $this->resolveProdiId($validated['prodi']);
            }

            DB::table('mata_kuliah')->insert($data);

            return redirect()
                ->route('pages.admin.matakuliah.index')
                ->with('success', 'Data mata kuliah berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menyimpan mata kuliah: '.$e->getMessage())
                ->withInput();
        }
    }

    // Mengambil satu mata kuliah untuk ditampilkan di form edit.
    public function editMatakuliah($id)
    {
        $query = DB::table('mata_kuliah')->where('mata_kuliah.id', $id);

        if (Schema::hasTable('prodi') && Schema::hasColumn('mata_kuliah', 'prodi_id')) {
            $query->leftJoin('prodi', 'mata_kuliah.prodi_id', '=', 'prodi.id')
                ->select('mata_kuliah.*', 'prodi.nama_prodi as prodi');
        } else {
            $query->select('mata_kuliah.*', DB::raw("'' as prodi"));
        }

        $matakuliah = $query->first();

        if (! $matakuliah) {
            abort(404, 'Data mata kuliah tidak ditemukan.');
        }

        $dosens = $this->getDosenPengampuOptions();

        $semesters = collect(['1', '2', '3', '4', '5', '6', '7', '8']);
        $prodis = $this->getProdiOptions();

        return view('pages.admin.matakuliah_edit', compact(
            'matakuliah',
            'dosens',
            'semesters',
            'prodis'
        ));
    }

    // Memperbarui data mata kuliah.
    public function updateMatakuliah(Request $request, $id)
    {
        $kodeRule = Rule::unique('mata_kuliah', 'kode_mk')->ignore($id);

        if (Schema::hasColumn('mata_kuliah', 'deleted_at')) {
            $kodeRule->whereNull('deleted_at');
        }

        $validated = $request->validate([
            'kode_mk' => ['required', 'string', 'max:30', $kodeRule],
            'nama' => 'required|string|max:100',
            'sks' => 'required|integer|min:1|max:6',
            'semester_ke' => 'required|integer|min:1|max:8',
            'prodi' => 'required|string|max:100',
            'dosen_id' => 'nullable|exists:dosen,id',
        ], [
            'kode_mk.required' => 'Kode mata kuliah wajib diisi.',
            'kode_mk.unique' => 'Kode mata kuliah sudah digunakan.',
            'nama.required' => 'Nama mata kuliah wajib diisi.',
            'sks.required' => 'SKS wajib diisi.',
            'semester_ke.required' => 'Semester wajib dipilih.',
            'prodi.required' => 'Program studi wajib dipilih.',
        ]);

        // Validasi ini mencegah dosen dari prodi lain menjadi pengampu mata kuliah ini.
        if (! $this->isValidDosenPengampuForProdi($validated['dosen_id'] ?? null, $validated['prodi'])) {
            return back()
                ->withErrors(['dosen_id' => 'Dosen pengampu harus dosen mata kuliah dari program studi yang dipilih.'])
                ->withInput();
        }

        try {
            $dosen = !empty($validated['dosen_id'])
                ? DB::table('dosen')->where('id', $validated['dosen_id'])->first()
                : null;

            $data = [
                'dosen_id' => $validated['dosen_id'] ?? null,
                'kode_mk' => $validated['kode_mk'],
                'nama' => $validated['nama'],
                'sks' => $validated['sks'],
                'semester_ke' => $validated['semester_ke'],
                'updated_at' => now(),
            ];

            if (Schema::hasColumn('mata_kuliah', 'dosen_nik')) {
                $data['dosen_nik'] = $dosen?->nik;
            }

            if (Schema::hasColumn('mata_kuliah', 'prodi_id')) {
                $data['prodi_id'] = $this->resolveProdiId($validated['prodi']);
            }

            DB::table('mata_kuliah')
                ->where('id', $id)
                ->update($data);

            if (
                Schema::hasTable('nilai') &&
                Schema::hasColumn('nilai', 'dosen_nik') &&
                Schema::hasColumn('nilai', 'mata_kuliah_id')
            ) {
                // Jika pengampu berubah, data nilai lama ikut disesuaikan dengan NIK dosen baru.
                DB::table('nilai')
                    ->where('mata_kuliah_id', $id)
                    ->update([
                        'dosen_nik' => $dosen?->nik,
                        'updated_at' => now(),
                    ]);
            }

            return redirect()
                ->route('pages.admin.matakuliah.index')
                ->with('success', 'Data mata kuliah berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal memperbarui mata kuliah: '.$e->getMessage())
                ->withInput();
        }
    }

    // Menghapus mata kuliah dari tampilan admin memakai kolom deleted_at.
    public function destroyMatakuliah(Request $request, $id)
    {
        try {
            if (! Schema::hasTable('mata_kuliah')) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Tabel mata kuliah tidak ditemukan.'], 404);
                }

                return back()->with('error', 'Tabel mata kuliah tidak ditemukan.');
            }

            $matakuliah = DB::table('mata_kuliah')->where('id', $id)->first();

            if (! $matakuliah) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Data mata kuliah tidak ditemukan.'], 404);
                }

                return back()->with('error', 'Data mata kuliah tidak ditemukan.');
            }

            if (! Schema::hasColumn('mata_kuliah', 'deleted_at')) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Kolom soft delete belum tersedia. Jalankan migration terlebih dahulu.'], 500);
                }

                return back()->with('error', 'Kolom soft delete belum tersedia. Jalankan migration terlebih dahulu.');
            }

            $updateData = [
                'deleted_at' => now(),
            ];

            if (Schema::hasColumn('mata_kuliah', 'updated_at')) {
                $updateData['updated_at'] = now();
            }

            DB::table('mata_kuliah')->where('id', $id)->update($updateData);

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Data mata kuliah berhasil dihapus dari tampilan admin!']);
            }

            return redirect()
                ->route('pages.admin.matakuliah.index')
                ->with('success', 'Data mata kuliah berhasil dihapus dari tampilan admin!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Gagal menghapus mata kuliah: '.$e->getMessage()], 500);
            }

            return back()
                ->with('error', 'Gagal menghapus mata kuliah: '.$e->getMessage());
        }
    }

    private function getDosenPengampuOptions()
    {
        if (! Schema::hasTable('dosen')) {
            return collect();
        }

        return DB::table('dosen')
            ->select(
                'id',
                'nama',
                'nik',
                DB::raw(Schema::hasColumn('dosen', 'tipe_dosen') ? 'tipe_dosen' : 'NULL as tipe_dosen'),
                DB::raw(Schema::hasColumn('dosen', 'fakultas') ? 'fakultas' : 'NULL as fakultas')
            )
            ->when(
                Schema::hasColumn('dosen', 'deleted_at'),
                fn ($query) => $query->whereNull('deleted_at')
            )
            ->when(Schema::hasColumn('dosen', 'tipe_dosen'), function ($query) {
                $query->where(function ($query) {
                    $query->where('tipe_dosen', 'keduanya')
                        ->orWhere('tipe_dosen', 'like', '%Mata Kuliah%')
                        ->orWhere('tipe_dosen', 'like', '%mk%');
                });
            })
            ->orderBy('nama', 'asc')
            ->get();
    }

    private function isValidDosenPengampuForProdi(?string $dosenId, string $prodi): bool
    {
        if (empty($dosenId)) {
            return true;
        }

        if (! Schema::hasTable('dosen')) {
            return false;
        }

        $dosen = DB::table('dosen')
            ->where('id', $dosenId)
            ->when(
                Schema::hasColumn('dosen', 'deleted_at'),
                fn ($query) => $query->whereNull('deleted_at')
            )
            ->first();

        if (! $dosen) {
            return false;
        }

        if (
            Schema::hasColumn('dosen', 'tipe_dosen') &&
            ! $this->isDosenMataKuliahRole($dosen->tipe_dosen ?? null)
        ) {
            return false;
        }

        if (! Schema::hasColumn('dosen', 'fakultas')) {
            return true;
        }

        return strcasecmp(trim((string) ($dosen->fakultas ?? '')), trim($prodi)) === 0;
    }

    private function isDosenMataKuliahRole(?string $tipeDosen): bool
    {
        $tipeDosen = strtolower(trim((string) $tipeDosen));

        return $tipeDosen === 'keduanya' ||
            str_contains($tipeDosen, 'mata kuliah') ||
            str_contains($tipeDosen, 'mk');
    }
}
