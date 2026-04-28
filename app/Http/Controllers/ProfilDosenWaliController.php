<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfilDosenWaliController extends Controller
{
    public function index()
    {
        $dosen = [
            'nama' => 'Rusyda Nazhirah Yunus, S.S., M.Si',
            'nip' => '198501012010012001',
            'email' => 'wali@univ.ac.id',
            'no_hp' => '08123456789',
            'alamat' => 'Kota Batam',
            'program_studi' => 'Teknik Informatika'
        ];

        return view('pages.dosen_wali.profil', compact('dosen'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'no_hp' => 'required|string',
            'alamat' => 'required|string',
        ]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'password_lama' => 'required',
            'password_baru' => ['required', Password::defaults(), 'confirmed'],
        ]);

        return redirect()->back()->with('success', 'Password berhasil diubah');
    }
}