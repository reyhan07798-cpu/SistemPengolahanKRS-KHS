<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class ProfilDosenWaliController extends Controller
{
    public function index()
    {
        // Data profil disesuaikan dengan siapa yang login
        $userType = session('user_type');
        $nik = session('nik', session('nidn', ''));

        if ($userType === 'dosen') {
            $dosen = [
                'nama'          => session('user_name', 'Dosen'),
                'nip'           => $nik,
                'nidn'          => $nik,
                'email'         => 'dosen@univ.ac.id',
                'no_hp'         => '08123456789',
                'alamat'        => 'Kota Batam',
                'program_studi' => session('prodi', 'Teknik Informatika'),
            ];
        } else {
            $dosen = [
                'nama'          => 'Dosen',
                'nip'           => '-',
                'nidn'          => '-',
                'email'         => '-',
                'no_hp'         => '-',
                'alamat'        => '-',
                'program_studi' => '-',
            ];
        }

        // Tentukan view mana yang dipakai berdasarkan role
        if (session('is_dosen_mk') && !session('is_dosen_wali')) {
            return view('pages.dosen_matkul.profil', compact('dosen'));
        }

        return view('pages.dosen_wali.profil', compact('dosen'));
    }

    // Alias untuk kompatibilitas route lama
    public function updateProfil(Request $request)
    {
        return $this->update($request);
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'email'  => 'required|email',
            'no_hp'  => 'required|string',
            'alamat' => 'required|string',
        ]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama'             => 'required',
            'password_baru'             => ['required', 'min:6', 'confirmed'],
        ]);

        return redirect()->back()->with('success', 'Password berhasil diubah.');
    }
}
