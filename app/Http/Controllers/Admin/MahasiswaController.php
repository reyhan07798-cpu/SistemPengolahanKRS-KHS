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

    // Menampilkan daftar mahasiswa beserta data pilihan untuk filter dan form.
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

    // Membuka halaman/form tambah mahasiswa.
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

    // Menyimpan mahasiswa baru sekaligus membuat akun login mahasiswa.
    public function storeMahasiswa(Request $request)
    {
        // Validasi memastikan data wajib terisi dan NIM/email tidak kembar.
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
            'password' => 'nullable|string|min:4',
        ]);

        if (empty($validated['password'])) {
            $validated['password'] = $validated['nim'];
        }

        // Nama kelas dibentuk otomatis dari prodi, semester awal, grup, dan sesi.
        $validated['kelas'] = $this->buildKelasName(
            $validated['prodi'],
            (int) $validated['semester_ke_awal'],
            $validated['kelas_grup'],
            $validated['sesi_kelas']
        );
        try {
            // Transaction menjaga akun user, data mahasiswa, dan semester awal tersimpan bersama.
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

    // Mengambil satu data mahasiswa untuk ditampilkan di form edit.
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

    // Memperbarui data mahasiswa dan akun login yang terhubung.
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
            // Data mahasiswa dan user login diperbarui dalam satu proses.
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

    // Menghapus mahasiswa dari tampilan admin memakai soft delete.
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

                // User login ikut disembunyikan agar akun mahasiswa tidak aktif lagi.
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
