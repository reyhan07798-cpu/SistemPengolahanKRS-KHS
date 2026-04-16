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
        $user = self::$users[$identifier] ?? null;

        if (!$user) {
            return null;
        }

        if ($user['password'] !== $password) {
            return null;
        }

        if ($user['type'] === 'nim') {
            return [
                'nim' => $identifier,
                'nik' => null,
                'email' => $user['email'],
                'name' => $user['name'],
                'role' => $user['role'],
            ];
        } elseif ($user['type'] === 'nik') {
            return [
                'nim' => null,
                'nik' => $identifier,
                'email' => $user['email'],
                'name' => $user['name'],
                'role' => $user['role'],
            ];
        } else {
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
