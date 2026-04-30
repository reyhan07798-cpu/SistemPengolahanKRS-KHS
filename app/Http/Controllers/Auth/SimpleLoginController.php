<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SimpleLoginController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login dengan NIM (Mahasiswa) atau NIK (Dosen)
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        $identifier = trim($credentials['identifier']);
        $password = $credentials['password'];

        $dummyUsers = [
            // 🎓 ADMIN
            'ADMIN001' => [
                'type' => 'admin',
                'password' => '123456',
                'name' => 'Administrator',
                'role_display' => 'Admin Sistem',
            ],

            // 👨‍🎓 MAHASISWA (Login pakai NIM)
            '3312501022' => [
                'type' => 'mahasiswa',
                'password' => '123456',
                'name' => 'Reyhan',
                'nim' => '3312501022',
                'prodi' => 'Teknik Informatika',
                'semester' => 2,
                'role_display' => 'Mahasiswa',
            ],
            '3312501007' => [
                'type' => 'mahasiswa',
                'password' => '123456',
                'name' => 'Nabila Fatin',
                'nim' => '3312501007',
                'prodi' => 'Teknik Informatika',
                'semester' => 2,
                'role_display' => 'Mahasiswa',
            ],
            '3312501017' => [
                'type' => 'mahasiswa',
                'password' => '123456',
                'name' => 'Irenessa Rosidin',
                'nim' => '3312501017',
                'prodi' => 'Teknik Informatika',
                'semester' => 2,
                'role_display' => 'Mahasiswa',
            ],

            // Dosen dengan 2 peran: Wali + Matkul
            '124307' => [
                'type' => 'dosen',
                'password' => '123456',
                'name' => 'Dr. Cyntia Lasmi Andesti, S.Kom., M.Kom',
                'nik' => '124307',
                'nidn' => '124307',
                'prodi' => 'Teknik Informatika',
                'is_dosen_wali' => true,   
                'is_dosen_mk' => true,     
                'role_display' => 'Dosen (Wali + Matkul)',
            ],
            // Dosen hanya sebagai Wali
            '124308' => [
                'type' => 'dosen',
                'password' => '123456',
                'name' => 'Dr. Rusyda Nazhirah Yunus, M.Kom',
                'nik' => '124308',
                'nidn' => '124308',
                'prodi' => 'Teknik Informatika',
                'is_dosen_wali' => true,
                'is_dosen_mk' => false,
                'role_display' => 'Dosen Wali',
            ],
            // Dosen hanya sebagai Pengampu Matkul
            '124309' => [
                'type' => 'dosen',
                'password' => '123456',
                'name' => 'Dr. Budi Santoso, M.T',
                'nik' => '124309',
                'nidn' => '124309',
                'prodi' => 'Teknik Informatika',
                'is_dosen_wali' => false,
                'is_dosen_mk' => true,
                'role_display' => 'Dosen Mata Kuliah',
            ],
        ];

        // CEK CREDENTIALS
        if (isset($dummyUsers[$identifier]) && 
            $dummyUsers[$identifier]['password'] === $password) {
            
            $user = $dummyUsers[$identifier];
            
            // Simpan data user ke session
            session([
                'is_logged_in' => true,
                'user_id' => $identifier,
                'user_name' => $user['name'],
                'user_type' => $user['type'],
                'role_display' => $user['role_display'],
                
                // Data spesifik mahasiswa
                'nim' => $user['nim'] ?? null,
                'prodi' => $user['prodi'] ?? null,
                'semester_aktif' => $user['semester'] ?? null,
                
                // Data spesifik dosen
                'nik' => $user['nik'] ?? null,
                'nidn' => $user['nidn'] ?? null,
                'is_dosen_wali' => $user['is_dosen_wali'] ?? false,
                'is_dosen_mk' => $user['is_dosen_mk'] ?? false,
            ]);

            // Redirect berdasarkan tipe user
            switch ($user['type']) {
                case 'admin':
                    return redirect()->route('pages.admin.dashboard');
                
                case 'mahasiswa':
                    return redirect()->route('pages.mahasiswa.beranda');
                
                case 'dosen':
                    if ($user['is_dosen_wali']) {
                        return redirect()->route('dosen.wali.beranda');
                    } else {
                        return redirect()->route('dosen.mk.beranda');
                    }
                
                default:
                    return redirect('/login');
            }
        }

        // Jika login gagal
        return back()->withErrors([
            'identifier' => 'Identifier atau Password tidak valid!'
        ])->withInput();
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        // Hapus semua session key
        $request->session()->forget([
            'user_id',
            'user_name',
            'user_type',
            'role_display',
            'nim',
            'nik',
            'nidn',
            'prodi',
            'semester_aktif',
            'is_dosen_wali',
            'is_dosen_mk',
        ]);
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', '✅ Anda telah berhasil keluar.');
    }
}