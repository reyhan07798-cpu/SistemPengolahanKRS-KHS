<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAdminData;
use App\Http\Controllers\Controller;
use App\Models\PaketMataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PaketMataKuliahController extends Controller
{
    use HandlesAdminData;

    // Menampilkan daftar paket mata kuliah untuk setiap prodi dan semester.
    public function indexPaketMK()
    {
        $allMataKuliah = $this->getMataKuliahOptions();
        $prodis = $this->getProdiOptions();
        $semesters = $this->getPaketSemesterOptions();
        $paketMK = Schema::hasTable('paket_mata_kuliahs')
        ? $this->paketMataKuliahListQuery()->get()
        : collect();
        if ($paketMK->isNotEmpty() && Schema::hasTable('paket_mata_kuliah_details')) {
            // Detail mata kuliah digabung agar view tahu isi setiap paket.
            $detailIds = DB::table('paket_mata_kuliah_details')
                ->whereIn('paket_mata_kuliah_id', $paketMK->pluck('id'))
                ->get()
                ->groupBy('paket_mata_kuliah_id')
                ->map(fn ($rows) => $this->canonicalMataKuliahIds($rows->pluck('mata_kuliah_id')->toArray()));
            $paketMK = $paketMK->map(function ($paket) use ($detailIds) {
                $paket->mata_kuliah_ids = array_values($detailIds->get($paket->id, []));

                return $paket;
            });
        }

        return view('pages.admin.data_paketmk', compact('paketMK', 'allMataKuliah', 'prodis', 'semesters'));
    }

    // Membuka halaman/form tambah paket mata kuliah.
    public function createPaketMK()
    {
        $allMataKuliah = $this->getMataKuliahOptions();
        $prodis = $this->getProdiOptions();
        $semesters = $this->getPaketSemesterOptions();

        return view('pages.admin.paketmk_create', compact('allMataKuliah', 'prodis', 'semesters'));
    }

    // Menyimpan paket MK baru beserta daftar mata kuliah di dalamnya.
    public function storePaketMK(Request $request)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'semester' => 'required|integer|min:1|max:8',
            'prodi' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'mata_kuliah' => 'required|array|min:1',
            'mata_kuliah.*' => 'integer|exists:mata_kuliah,id',
        ]);
        $mataKuliahIds = $this->canonicalMataKuliahIds($validated['mata_kuliah']);
        $prodiId = $this->resolveProdiId($validated['prodi']);
        $semesterId = $this->resolveSemesterId((int) $validated['semester']);
        $this->validatePaketMataKuliahScope($mataKuliahIds, (int) $validated['semester'], $prodiId);
        $this->validatePaketMataKuliahSks($mataKuliahIds);
        try {
            // Transaction dipakai karena paket utama dan detail mata kuliah harus tersimpan bersama.
            DB::transaction(function () use ($validated, $mataKuliahIds, $prodiId, $semesterId) {
                $this->archiveExistingPaketMataKuliah($prodiId, $semesterId);
                $paketId = DB::table('paket_mata_kuliahs')->insertGetId([
                    'nama_paket' => $validated['nama_paket'],
                    'semester_id' => $semesterId,
                    'prodi_id' => $prodiId,
                    'deskripsi' => $validated['deskripsi'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->syncPaketMataKuliahDetails($paketId, $mataKuliahIds);
            });

            return redirect()->route('pages.admin.paketmk.index')->with('success', 'Data paket berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: '.$e->getMessage())->withInput();
        }
    }

    // Mengambil paket yang dipilih beserta mata kuliah yang sudah tercentang.
    public function editPaketMK($id)
    {
        $paketMK = $this->paketMataKuliahListQuery()
            ->where('paket_mata_kuliahs.id', $id)
            ->first();
        if (! $paketMK) {
            abort(404, 'Data paket mata kuliah tidak ditemukan.');
        }
        $allMataKuliah = $this->getMataKuliahOptions();
        $prodis = $this->getProdiOptions();
        $semesters = $this->getPaketSemesterOptions();
        $selectedMataKuliahIds = DB::table('paket_mata_kuliah_details')
            ->where('paket_mata_kuliah_id', $id)
            ->pluck('mata_kuliah_id')
            ->toArray();
        $selectedMataKuliahIds = $this->canonicalMataKuliahIds($selectedMataKuliahIds);

        return view('pages.admin.paketmk_edit', compact(
            'paketMK',
            'allMataKuliah',
            'prodis',
            'semesters',
            'selectedMataKuliahIds'
        ));
    }

    // Memperbarui paket MK dan menyinkronkan ulang detail mata kuliahnya.
    public function updatePaketMK(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'semester' => 'required|integer|min:1|max:8',
            'prodi' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'mata_kuliah' => 'required|array|min:1',
            'mata_kuliah.*' => 'integer|exists:mata_kuliah,id',
        ]);
        $mataKuliahIds = $this->canonicalMataKuliahIds($validated['mata_kuliah']);
        $prodiId = $this->resolveProdiId($validated['prodi']);
        $semesterId = $this->resolveSemesterId((int) $validated['semester']);
        $this->validatePaketMataKuliahScope($mataKuliahIds, (int) $validated['semester'], $prodiId);
        $this->validatePaketMataKuliahSks($mataKuliahIds);
        try {
            // Isi detail paket bisa berubah, jadi proses update dibuat satu transaction.
            DB::transaction(function () use ($validated, $id, $mataKuliahIds, $prodiId, $semesterId) {
                $this->archiveExistingPaketMataKuliah($prodiId, $semesterId, (int) $id);
                DB::table('paket_mata_kuliahs')
                    ->where('id', $id)
                    ->update([
                        'nama_paket' => $validated['nama_paket'],
                        'semester_id' => $semesterId,
                        'prodi_id' => $prodiId,
                        'deskripsi' => $validated['deskripsi'] ?? null,
                        'updated_at' => now(),
                    ]);
                $this->syncPaketMataKuliahDetails($id, $mataKuliahIds);
            });

            return redirect()->route('pages.admin.paketmk.index')->with('success', 'Data paket berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: '.$e->getMessage())->withInput();
        }
    }

    // Menghapus paket mata kuliah dari daftar admin.
    public function destroyPaketMK($id)
    {
        try {
            if (Schema::hasTable('paket_mata_kuliahs')) {
                $paket = PaketMataKuliah::findOrFail($id);
                $paket->delete();
            }

            return redirect()->route('pages.admin.paketmk.index')->with('success', 'Data paket berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }
    }
}
