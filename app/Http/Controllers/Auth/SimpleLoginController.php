<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SimpleAuthService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SimpleLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'identifier' => ['required', 'string'],
            'password'   => ['required'],
        ]);

        $user = SimpleAuthService::authenticate($credentials['identifier'], $credentials['password']);

        if (!$user) {
            throw ValidationException::withMessages([
                'identifier' => 'NIM, NIK, atau password salah.',
            ]);
        }

        // Simpan data user ke session
        $request->session()->put('user', $user);

        // Set session keys yang dipakai layout dosen.blade.php
        $role = $user['role'];

        $request->session()->put('user_name',    $user['name']);
        $request->session()->put('user_type',    $role);
        $request->session()->put('role_display', $this->getRoleDisplay($role));

        $request->session()->put('is_dosen_wali', in_array($role, ['dosen_wali', 'dosen']));
        $request->session()->put('is_dosen_mk',   in_array($role, ['dosen_matkul', 'dosen']));

        $request->session()->regenerate();

        return $this->redirectByRole($role);
    }

    private function getRoleDisplay($role): string
    {
        return match($role) {
            'mahasiswa'    => 'Mahasiswa',
            'admin'        => 'Administrator',
            'dosen_wali'   => 'Dosen Wali',
            'dosen_matkul' => 'Dosen Mata Kuliah',
            'dosen'        => 'Dosen (Wali & Mata Kuliah)',
            default        => 'Pengguna',
        };
    }

    private function redirectByRole($role)
    {
        return match($role) {
            'mahasiswa'    => redirect()->route('pages.mahasiswa.beranda'),
            'admin'        => redirect()->route('pages.admin.dashboard'),
            'dosen_wali'   => redirect()->route('dosen.wali.beranda'),
            'dosen_matkul' => redirect()->route('dosen.mk.beranda'),
            'dosen'        => redirect()->route('dosen.wali.beranda'),
            default        => redirect('/login'),
        };
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}