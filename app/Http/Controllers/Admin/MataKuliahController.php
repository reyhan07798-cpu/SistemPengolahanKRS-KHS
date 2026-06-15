<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAdminData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MataKuliahController extends Controller
{
    use HandlesAdminData;

    public function indexMatakuliah()
    {
        $dosens = Schema::hasTable('dosen')
        ? DB::table('dosen')
            ->when(
                Schema::hasColumn('dosen', 'deleted_at'),
                fn ($query) => $query->whereNull('deleted_at')
            )
            ->orderBy('nama', 'asc')
            ->get()
        : collect();
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
                ->orderBy('mata_kuliah.created_at', 'desc')
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

    public function createMatakuliah()
    {
        $dosens = Schema::hasTable('dosen')
        ? DB::table('dosen')
            ->when(
                Schema::hasColumn('dosen', 'deleted_at'),
                fn ($query) => $query->whereNull('deleted_at')
            )
            ->orderBy('nama', 'asc')
            ->get()
        : collect();
        $semesters = collect(['1', '2', '3', '4', '5', '6', '7', '8']);
        $prodis = $this->getProdiOptions();

        return view('pages.admin.matakuliah_create', compact(
            'dosens',
            'semesters',
            'prodis'
        ));
    }

    public function storeMatakuliah(Request $request)
    {
        $validated = $request->validate([
            'kode_mk' => 'required|string|max:30|unique:mata_kuliah,kode_mk',
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
        try {
            $data = [
                'dosen_id' => $validated['dosen_id'] ?? null,
                'kode_mk' => $validated['kode_mk'],
                'nama' => $validated['nama'],
                'sks' => $validated['sks'],
                'semester_ke' => $validated['semester_ke'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
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
        $dosens = Schema::hasTable('dosen')
        ? DB::table('dosen')->orderBy('nama', 'asc')->get()
        : collect();
        $semesters = collect(['1', '2', '3', '4', '5', '6', '7', '8']);
        $prodis = $this->getProdiOptions();

        return view('pages.admin.matakuliah_edit', compact(
            'matakuliah',
            'dosens',
            'semesters',
            'prodis'
        ));
    }

    public function updateMatakuliah(Request $request, $id)
    {
        $validated = $request->validate([
            'kode_mk' => 'required|string|max:30|unique:mata_kuliah,kode_mk,'.$id,
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
        try {
            $data = [
                'dosen_id' => $validated['dosen_id'] ?? null,
                'kode_mk' => $validated['kode_mk'],
                'nama' => $validated['nama'],
                'sks' => $validated['sks'],
                'semester_ke' => $validated['semester_ke'],
                'updated_at' => now(),
            ];
            if (Schema::hasColumn('mata_kuliah', 'prodi_id')) {
                $data['prodi_id'] = $this->resolveProdiId($validated['prodi']);
            }
            DB::table('mata_kuliah')
                ->where('id', $id)
                ->update($data);

            return redirect()
                ->route('pages.admin.matakuliah.index')
                ->with('success', 'Data mata kuliah berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal memperbarui mata kuliah: '.$e->getMessage())
                ->withInput();
        }
    }

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
    // ==========================================
    // 5. TAHUN AJARAN CRUD
    // ==========================================
}
