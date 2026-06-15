<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAdminData;
use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class DosenController extends Controller
{
    use HandlesAdminData;

    public function indexDosen()
    {
        $prodis = $this->getProdiOptions();
        $dosen = Schema::hasTable('dosen')
        ? Dosen::orderBy('created_at', 'desc')->get()
        : collect();

        return view('pages.admin.data_dosen', compact('dosen', 'prodis'));
    }

    public function createDosen()
    {
        $prodis = $this->getProdiOptions();

        return view('pages.admin.dosen_create', compact('prodis'));
    }

    public function storeDosen(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|max:30|unique:dosen,nik',
            'nip' => 'nullable|string|max:30|unique:dosen,nip',
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:dosen,email|unique:users,email',
            'tipe_dosen' => 'required|string',
            'fakultas' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'password' => 'required|string|min:4',
        ]);
        try {
            $user = User::create([
                'name' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            if (Schema::hasColumn('users', 'nik')) {
                $user->update(['nik' => $validated['nik']]);
            }
            if (Schema::hasColumn('users', 'role')) {
                $role = 'dosen_mk';
                $tipe = strtolower($validated['tipe_dosen']);
                if (
                    $tipe === 'keduanya' ||
                    str_contains($tipe, 'wali') &&
                    (str_contains($tipe, 'mata kuliah') || str_contains($tipe, 'mk') || str_contains($tipe, 'keduanya'))
                ) {
                    $role = 'dosen';
                } elseif (str_contains($tipe, 'wali')) {
                    $role = 'dosen_wali';
                }
                $user->update(['role' => $role]);
            }
            Dosen::create([
                'user_id' => $user->id,
                'nik' => $validated['nik'],
                'nip' => $validated['nip'] ?? null,
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'no_hp' => $validated['no_hp'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'tipe_dosen' => $validated['tipe_dosen'],
                'fakultas' => $validated['fakultas'],
            ]);

            return redirect()
                ->route('pages.admin.dosen.index')
                ->with('success', 'Data dosen berhasil ditambahkan! Akun login sudah siap digunakan.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menyimpan data: '.$e->getMessage())
                ->withInput();
        }
    }

    public function editDosen($id)
    {
        $dosen = Dosen::findOrFail($id);
        $prodis = $this->getProdiOptions();

        return view('pages.admin.dosen_edit', compact('dosen', 'prodis'));
    }

    public function updateDosen(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);
        $validated = $request->validate([
            'nik' => 'required|string|max:30|unique:dosen,nik,'.$id,
            'nip' => 'nullable|string|max:30|unique:dosen,nip,'.$id,
            'nama' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('dosen', 'email')->ignore($id),
                Rule::unique('users', 'email')->ignore($dosen->user_id),
            ],
            'tipe_dosen' => 'required|string',
            'fakultas' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:4',
        ]);
        try {
            if ($dosen->user_id) {
                $user = User::find($dosen->user_id);
                if ($user) {
                    $updateUser = [
                        'name' => $validated['nama'],
                        'email' => $validated['email'],
                    ];
                    if ($request->filled('password')) {
                        $updateUser['password'] = Hash::make($validated['password']);
                    }
                    if (Schema::hasColumn('users', 'nik')) {
                        $updateUser['nik'] = $validated['nik'];
                    }
                    if (Schema::hasColumn('users', 'role')) {
                        $role = 'dosen_mk';
                        $tipe = strtolower($validated['tipe_dosen']);
                        if (
                            $tipe === 'keduanya' ||
                            str_contains($tipe, 'wali') &&
                            (str_contains($tipe, 'mata kuliah') || str_contains($tipe, 'mk') || str_contains($tipe, 'keduanya'))
                        ) {
                            $role = 'dosen';
                        } elseif (str_contains($tipe, 'wali')) {
                            $role = 'dosen_wali';
                        }
                        $updateUser['role'] = $role;
                    }
                    $user->update($updateUser);
                }
            }
            $dosen->update([
                'nik' => $validated['nik'],
                'nip' => $validated['nip'] ?? null,
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'no_hp' => $validated['no_hp'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'tipe_dosen' => $validated['tipe_dosen'],
                'fakultas' => $validated['fakultas'],
            ]);

            return redirect()
                ->route('pages.admin.dosen.index')
                ->with('success', 'Data dosen berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal update data: '.$e->getMessage())
                ->withInput();
        }
    }

    public function destroyDosen($id)
    {
        $successMessage = 'Data dosen berhasil dihapus dari tampilan admin.';
        try {
            if (! Schema::hasTable('dosen')) {
                if (request()->expectsJson()) {
                    return response()->json(['message' => 'Tabel dosen tidak ditemukan.'], 404);
                }

                return redirect()
                    ->route('pages.admin.dosen.index')
                    ->with('error', 'Tabel dosen tidak ditemukan.');
            }
            if (! Schema::hasColumn('dosen', 'deleted_at')) {
                if (request()->expectsJson()) {
                    return response()->json(['message' => 'Kolom soft delete belum tersedia. Jalankan migration terlebih dahulu.'], 500);
                }

                return redirect()
                    ->route('pages.admin.dosen.index')
                    ->with('error', 'Kolom soft delete belum tersedia. Jalankan migration terlebih dahulu.');
            }
            $dosen = Dosen::findOrFail($id);
            DB::transaction(function () use ($dosen) {
                $user = $dosen->user_id ? User::find($dosen->user_id) : null;
                $dosen->delete();
                if ($user && Schema::hasColumn('users', 'deleted_at')) {
                    $user->delete();
                }
            });
            if (request()->expectsJson()) {
                return response()->json(['message' => $successMessage]);
            }

            return redirect()
                ->route('pages.admin.dosen.index')
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Gagal menghapus data: '.$e->getMessage()], 500);
            }

            return redirect()
                ->route('pages.admin.dosen.index')
                ->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }
    }
}
