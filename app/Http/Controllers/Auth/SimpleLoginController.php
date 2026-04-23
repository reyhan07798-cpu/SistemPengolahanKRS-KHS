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
            'password' => ['required'],
        ]);

        $user = SimpleAuthService::authenticate($credentials['identifier'], $credentials['password']);

        if (!$user) {
            throw ValidationException::withMessages([
                'identifier' => 'NIM, NIK, atau password salah.',
            ]);
        }

        $request->session()->put('user', $user);
        $request->session()->regenerate();

        return $this->redirectByRole($user['role']);
    }

    private function redirectByRole($role)
    {
        $redirects = [
            'mahasiswa' => '/mahasiswa/beranda',
            'admin' => '/admin/dashboard',
            'dosen_wali' => '/dosen_wali/beranda',
            'dosen_matkul' => '/dosen_matkul/beranda',
        ];

        $url = $redirects[$role] ?? '/dashboard';
        return redirect($url);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
