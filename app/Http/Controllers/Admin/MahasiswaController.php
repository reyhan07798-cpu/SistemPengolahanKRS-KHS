<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAdminData;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\MahasiswaSemester;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class MahasiswaController extends Controller
{
    use HandlesAdminData;

    public function indexMahasiswa()
    {
        $mahasiswa = Schema::hasTable('mahasiswa')
        ? $this->mahasiswaListQuery()->get()
        : collect();
        $prodis = $this->getProdiOptions();
        $angkatans = $this->getAngkatanOptions();
        $dosens = $this->getDosenWaliOptions();
        $kelasOptions = $this->getKelasOptions();
        $kelasGroups = $this->getKelasGroupOptions();
        $sesiOptions = $this->getSesiKelasOptions();

        return view('pages.admin.data_mahasiswa', compact('mahasiswa', 'prodis', 'angkatans', 'dosens', 'kelasOptions', 'kelasGroups', 'sesiOptions'));
    }

    public function createMahasiswa()
    {
        $prodis = $this->getProdiOptions();
        $angkatans = $this->getAngkatanOptions();
        $dosens = $this->getDosenWaliOptions();
        $kelasOptions = $this->getKelasOptions();
        $kelasGroups = $this->getKelasGroupOptions();
        $sesiOptions = $this->getSesiKelasOptions();

        return view('pages.admin.mahasiswa_create', compact('prodis', 'angkatans', 'dosens', 'kelasOptions', 'kelasGroups', 'sesiOptions'));
    }

    public function storeMahasiswa(Request $request)
    {
        $validated = $request->validate([
            'nim' => [
                'required',
                'string',
                'max:20',
                Rule::unique('mahasiswa', 'nim'),
                Rule::unique('users', 'nim'),
            ],
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('mahasiswa', 'email'),
                Rule::unique('users', 'email'),
            ],
            'prodi' => 'required|string|max:100',
            'semester_ke_awal' => 'required|integer|min:1|max:14',
            'kelas_grup' => ['required', 'string', 'max:3'],
            'sesi_kelas' => ['required', 'string', 'max:20'],
            'angkatan' => 'required|integer|min:2000|max:2100',
            'kelas' => ['nullable', 'string', 'max:50'],
            'dosen_wali_id' => 'nullable|exists:dosen,id',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'password' => 'required|string|min:4',
        ]);
        $validated['kelas'] = $this->buildKelasName(
            $validated['prodi'],
            (int) $validated['semester_ke_awal'],
            $validated['kelas_grup'],
            $validated['sesi_kelas']
        );
        try {
            DB::transaction(function () use ($validated) {
                $user = User::create($this->mahasiswaUserData($validated));
                $mahasiswa = Mahasiswa::create([
                    'user_id' => $user->id,
                    'dosen_wali_id' => $validated['dosen_wali_id'] ?? null,
                    'prodi_id' => $this->resolveProdiId($validated['prodi']),
                    'nim' => $validated['nim'],
                    'nama' => $validated['nama'],
                    'email' => $validated['email'],
                    'no_hp' => $validated['no_hp'] ?? null,
                    'alamat' => $validated['alamat'] ?? null,
                    'angkatan' => $validated['angkatan'],
                    'kelas' => $validated['kelas'],
                ]);
                $activeSemester = DB::table('semesters')->where('is_active', true)->first();
                if ($activeSemester && Schema::hasTable('mahasiswa_semester')) {
                    MahasiswaSemester::create([
                        'mahasiswa_id' => $mahasiswa->id,
                        'semester_id' => $activeSemester->id,
                        'semester_ke' => (int) $validated['semester_ke_awal'],
                        'status' => 'aktif',
                    ]);
                }
            });

            return redirect()->route('pages.admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil ditambahkan! Akun login mahasiswa sudah dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan: '.$e->getMessage())->withInput();
        }
    }

    public function editMahasiswa($id)
    {
        $mahasiswa = Mahasiswa::with(['prodi', 'dosenWali'])->findOrFail($id);
        $prodis = $this->getProdiOptions();
        $angkatans = $this->getAngkatanOptions();
        $dosens = $this->getDosenWaliOptions();
        $kelasOptions = $this->getKelasOptions();
        $kelasGroups = $this->getKelasGroupOptions();
        $sesiOptions = $this->getSesiKelasOptions();

        return view('pages.admin.mahasiswa_edit', compact('mahasiswa', 'prodis', 'angkatans', 'dosens', 'kelasOptions', 'kelasGroups', 'sesiOptions'));
    }

    public function updateMahasiswa(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $validated = $request->validate([
            'nim' => [
                'required',
                'string',
                'max:20',
                Rule::unique('mahasiswa', 'nim')->ignore($mahasiswa->id),
                Rule::unique('users', 'nim')->ignore($mahasiswa->user_id),
            ],
            'nama' => 'required|string|max:255',
            'prodi' => 'required|string|max:100',
            'semester_ke_awal' => 'required|integer|min:1|max:14',
            'kelas_grup' => ['required', 'string', 'max:3'],
            'sesi_kelas' => ['required', 'string', 'max:20'],
            'kelas' => ['nullable', 'string', 'max:50'],
            'angkatan' => 'required|integer|min:2000|max:2100',
            'dosen_wali_id' => 'nullable|exists:dosen,id',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('mahasiswa', 'email')->ignore($mahasiswa->id),
                Rule::unique('users', 'email')->ignore($mahasiswa->user_id),
            ],
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'password' => 'nullable|string|min:4',
        ]);
        $validated['kelas'] = $this->buildKelasName(
            $validated['prodi'],
            (int) $validated['semester_ke_awal'],
            $validated['kelas_grup'],
            $validated['sesi_kelas']
        );
        try {
            DB::transaction(function () use ($mahasiswa, $validated, $request) {
                $mahasiswa->update([
                    'dosen_wali_id' => $validated['dosen_wali_id'] ?? null,
                    'prodi_id' => $this->resolveProdiId($validated['prodi']),
                    'nim' => $validated['nim'],
                    'nama' => $validated['nama'],
                    'email' => $validated['email'],
                    'no_hp' => $validated['no_hp'] ?? null,
                    'alamat' => $validated['alamat'] ?? null,
                    'angkatan' => $validated['angkatan'],
                    'kelas' => $validated['kelas'],
                ]);
                $user = $mahasiswa->user;
                if ($user) {
                    $userData = $this->mahasiswaUserData($validated, false);
                    if ($request->filled('password')) {
                        $userData['password'] = Hash::make($validated['password']);
                    }
                    $user->update($userData);
                }
            });

            return redirect()->route('pages.admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update: '.$e->getMessage())->withInput();
        }
    }

    public function destroyMahasiswa($id)
    {
        try {
            if (Schema::hasTable('mahasiswa')) {
                if (! Schema::hasColumn('mahasiswa', 'deleted_at')) {
                    return redirect()
                        ->route('pages.admin.mahasiswa.index')
                        ->with('error', 'Kolom soft delete belum tersedia. Jalankan migration terlebih dahulu.');
                }
                $mahasiswa = Mahasiswa::findOrFail($id);
                $user = $mahasiswa->user;
                DB::transaction(function () use ($mahasiswa, $user) {
                    $mahasiswa->delete();
                    if ($user && Schema::hasColumn('users', 'deleted_at')) {
                        $user->delete();
                    }
                });
            }

            return redirect()->route('pages.admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus dari tampilan admin!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus: '.$e->getMessage());
        }
    }
}
