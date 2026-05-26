<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Cek role dari session.
     * Usage di routes: middleware('check.role:dosen_wali')
     * atau multi-role: middleware('check.role:dosen_wali,dosen')
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!$request->session()->has('user')) {
            return redirect('/login');
        }

        $userRole  = session('user.role', '');
        $tipeDosen = session('user.tipe_dosen', '');

        // Cek apakah user punya salah satu role yang diizinkan
        foreach ($roles as $role) {
            if ($role === 'dosen_wali') {
                // Izinkan: role dosen_wali, atau dosen dengan tipe keduanya/dosen_wali
                if (
                    $userRole === 'dosen_wali' ||
                    $userRole === 'dosen' ||
                    in_array($tipeDosen, ['dosen_wali', 'keduanya'])
                ) {
                    return $next($request);
                }
            } elseif ($role === 'dosen_mk') {
                // Izinkan: role dosen_mk, atau dosen dengan tipe keduanya/dosen_mk
                if (
                    $userRole === 'dosen_mk' ||
                    $userRole === 'dosen_matkul' ||
                    $userRole === 'dosen' ||
                    in_array($tipeDosen, ['dosen_mk', 'keduanya'])
                ) {
                    return $next($request);
                }
            } elseif ($role === 'admin') {
                if ($userRole === 'admin') {
                    return $next($request);
                }
            } elseif ($role === 'mahasiswa') {
                if ($userRole === 'mahasiswa') {
                    return $next($request);
                }
            } else {
                if ($userRole === $role) {
                    return $next($request);
                }
            }
        }

        // Tidak punya akses → redirect ke beranda sesuai role
        return $this->redirectByRole($userRole, $tipeDosen);
    }

    private function redirectByRole(string $role, ?string $tipeDosen = null)
    {
        if ($role === 'dosen' || $tipeDosen === 'keduanya') {
            return redirect()->route('dosen.wali.beranda')->with('error', 'Akses ditolak: Anda tidak memiliki hak untuk halaman tersebut.');
        }
        return match($role) {
            'mahasiswa'              => redirect()->route('pages.mahasiswa.beranda')->with('error', 'Akses ditolak.'),
            'admin'                  => redirect()->route('pages.admin.dashboard')->with('error', 'Akses ditolak.'),
            'dosen_wali'             => redirect()->route('dosen.wali.beranda')->with('error', 'Akses ditolak: halaman ini hanya untuk Dosen Mata Kuliah.'),
            'dosen_mk','dosen_matkul'=> redirect()->route('dosen.mk.beranda')->with('error', 'Akses ditolak: halaman ini hanya untuk Dosen Wali.'),
            default                  => redirect('/login'),
        };
    }
}
