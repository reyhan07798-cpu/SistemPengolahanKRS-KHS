<?php

namespace App\Services;

class SimpleAuthService
{
    private static $users = [
        // Mahasiswa
        '3312501022' => [
            'type' => 'nim',
            'password' => '3312501022',
            'name' => 'Mahasiswa Satu',
            'role' => 'mahasiswa',
            'email' => 'mahasiswa1@student.com'
        ],
        '3312501017' => [
            'type' => 'nim',
            'password' => '3312501017',
            'name' => 'Mahasiswa Dua',
            'role' => 'mahasiswa',
            'email' => 'mahasiswa2@student.com'
        ],
        '3312501007' => [
            'type' => 'nim',
            'password' => '3312501007',
            'name' => 'Mahasiswa Tiga',
            'role' => 'mahasiswa',
            'email' => 'mahasiswa3@student.com'
        ],
        // Admin 
        'admin123' => [
            'type' => 'admin',
            'password' => 'admin123',
            'name' => 'Admin Pengelola',
            'role' => 'admin',
            'email' => 'admin@poltek.com'
        ],
        // Dosen Wali
        '12345678' => [
            'type' => 'nik',
            'password' => '12345678',
            'name' => 'Dosen Wali',
            'role' => 'dosen_wali',
            'email' => 'dosen.wali@poltek.com'
        ],
        // Dosen Matkul 
        '87654321' => [
            'type' => 'nik',
            'password' => '87654321',
            'name' => 'Dosen Matkul',
            'role' => 'dosen_matkul',
            'email' => 'dosen.matkul@poltek.com'
        ],
    ];

    public static function authenticate($identifier, $password)
    {
        // Cari user berdasarkan identifier
        $user = self::$users[$identifier] ?? null;

        if (!$user) {
            return null;
        }

        // Check password
        if ($user['password'] !== $password) {
            return null;
        }

        // Return user data berdasarkan tipe
        if ($user['type'] === 'nim') {
            // Mahasiswa - hanya NIM
            return [
                'nim' => $identifier,
                'nik' => null, // Mahasiswa tidak punya NIK
                'email' => $user['email'],
                'name' => $user['name'],
                'role' => $user['role'],
            ];
        } elseif ($user['type'] === 'nik') {
            // Dosen - hanya NIK
            return [
                'nim' => null, // Dosen tidak punya NIM
                'nik' => $identifier,
                'email' => $user['email'],
                'name' => $user['name'],
                'role' => $user['role'],
            ];
        } else {
            // Admin - identifier khusus
            return [
                'nim' => null,
                'nik' => null,
                'email' => $user['email'],
                'name' => $user['name'],
                'role' => $user['role'],
            ];
        }
    }

    public static function getUsers()
    {
        return self::$users;
    }
}
