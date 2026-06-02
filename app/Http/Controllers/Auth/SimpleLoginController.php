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

        $user = SimpleAuthService::authenticate(
            $credentials['identifier'],
            $credentials['password']
        );

        if (!$user) {
            throw ValidationException::withMessages([
                'identifier' => 'NIM, NIK, username, atau password salah.',
            ]);
        }

        $role      = $user['role']       ?? 'mahasiswa';
        $tipeDosen = $user['tipe_dosen'] ?? null;

        $request->session()->put('user',         $user);
        $request->session()->put('user_name',    $user['name'] ?? '-');
        $request->session()->put('user_type',    $role);
        $request->session()->put('role_display', $this->getRoleDisplay($role, $tipeDosen));

        // ── Flag portal yang bisa diakses (untuk sidebar) ─────────────────
        // Dosen wali murni atau merangkap → is_dosen_wali = true
        $request->session()->put('is_dosen_wali',
            in_array($role, ['dosen_wali', 'dosen']) ||
            in_array($tipeDosen, ['dosen_wali', 'keduanya'])
        );
        // Dosen MK murni atau merangkap → is_dosen_mk = true
        $request->session()->put('is_dosen_mk',
            in_array($role, ['dosen_mk', 'dosen_matkul', 'dosen']) ||
            in_array($tipeDosen, ['dosen_mk', 'keduanya'])
        );

        $request->session()->regenerate();

        return $this->redirectByRole($role, $tipeDosen);
    }

    private function getRoleDisplay(string $role, ?string $tipeDosen = null): string
    {
        // Merangkap keduanya
        if ($role === 'dosen' || $tipeDosen === 'keduanya') {
            return 'Dosen Wali & Mata Kuliah';
        }
        return match($role) {
            'mahasiswa'              => 'Mahasiswa',
            'admin'                  => 'Administrator',
            'dosen_wali'             => 'Dosen Wali',
            'dosen_mk','dosen_matkul'=> 'Dosen Mata Kuliah',
            default                  => 'Pengguna',
        };
    }

    private function redirectByRole(string $role, ?string $tipeDosen = null)
    {
        // Merangkap wali + MK → ke beranda wali (bisa switch ke MK dari sidebar)
        if ($role === 'dosen') {
            return redirect()->route('dosen.wali.beranda');
        }

        return match($role) {
            'mahasiswa'              => redirect()->route('pages.mahasiswa.beranda'),
            'admin'                  => redirect()->route('pages.admin.dashboard'),
            'dosen_wali'             => redirect()->route('dosen.wali.beranda'),
            'dosen_mk','dosen_matkul'=> redirect()->route('dosen.mk.beranda'),
            default                  => redirect('/login'),
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
