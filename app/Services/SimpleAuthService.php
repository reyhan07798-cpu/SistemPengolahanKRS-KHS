<?php

namespace App\Services;

class SimpleAuthService
{
    // Hardcoded users dengan role
    private static $users = [
        // Mahasiswa
        'mahasiswa1@student.com' => [
            'password' => 'password123',
            'name' => 'Mahasiswa Satu',
            'role' => 'mahasiswa'
        ],
        // Admin
        'admin@poltek.com' => [
            'password' => 'password123',
            'name' => 'Admin Pengelola',
            'role' => 'admin'
        ],
        // Dosen Wali
        'dosen.wali@poltek.com' => [
            'password' => 'password123',
            'name' => 'Dosen Wali',
            'role' => 'dosen_wali'
        ],
        // Dosen Matkul
        'dosen.matkul@poltek.com' => [
            'password' => 'password123',
            'name' => 'Dosen Matkul',
            'role' => 'dosen_matkul'
        ],
    ];

    public static function authenticate($email, $password)
    {
        if (!isset(self::$users[$email])) {
            return null;
        }

        $user = self::$users[$email];

        if ($user['password'] !== $password) {
            return null;
        }

        return [
            'email' => $email,
            'name' => $user['name'],
            'role' => $user['role'],
        ];
    }

    public static function getUsers()
    {
        return self::$users;
    }
}
