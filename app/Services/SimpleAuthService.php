<?php

namespace App\Services;

use App\Support\PasswordVerifier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SimpleAuthService
{
    /**
     * Login pakai: NIM (mahasiswa), NIK (dosen), username, atau email.
     * Password = plaintext sama identifier (legacy) atau bcrypt.
     */
    public static function authenticate(string $identifier, string $password): ?array
    {
        $userQuery = DB::table('users')
            ->where(function ($q) use ($identifier) {
                $q->where('nim',      $identifier)
                  ->orWhere('nik',      $identifier)
                  ->orWhere('username', $identifier)
                  ->orWhere('email',    $identifier);
            });

        if (Schema::hasColumn('users', 'deleted_at')) {
            $userQuery->whereNull('deleted_at');
        }

        $user = $userQuery->first();

        if (!$user) return null;

        $valid = PasswordVerifier::check($password, $user->password);

        if (!$valid) return null;

        $role = $user->role;

        $sessionData = [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $role,
            'nim'   => $user->nim  ?? null,
            'nik'   => $user->nik  ?? null,
        ];

        // Mahasiswa
        if ($role === 'mahasiswa') {
            $mhsQuery = DB::table('mahasiswa')->where('user_id', $user->id);
            if (Schema::hasColumn('mahasiswa', 'deleted_at')) {
                $mhsQuery->whereNull('deleted_at');
            }

            $mhs = $mhsQuery->first();

            if (!$mhs && $user->nim) {
                $mhsQuery = DB::table('mahasiswa')->where('nim', $user->nim);
                if (Schema::hasColumn('mahasiswa', 'deleted_at')) {
                    $mhsQuery->whereNull('deleted_at');
                }

                $mhs = $mhsQuery->first();
            }
            if (!$mhs) {
                return null;
            }

            $sessionData['mahasiswa_id'] = $mhs->id;
            $sessionData['nim']          = $mhs->nim;
            $sessionData['kelas']        = $mhs->kelas;
            $sessionData['angkatan']     = $mhs->angkatan;
        }

        // ── Dosen: cek berdasarkan role ATAU jika ada data di tabel dosen ──
        // Fix: sebelumnya hanya cek jika role = dosen_wali/dosen_mk/dosen,
        // sehingga user dengan role kosong ('') tidak ter-handle.
        $isDosen = in_array($role, ['dosen_wali', 'dosen_mk', 'dosen'])
                || ($role !== 'mahasiswa' && $role !== 'admin' && $user->nik);

        if ($isDosen) {
            $dosenQuery = DB::table('dosen')->where('user_id', $user->id);
            if (Schema::hasColumn('dosen', 'deleted_at')) {
                $dosenQuery->whereNull('deleted_at');
            }

            $dosen = $dosenQuery->first();

            if (!$dosen && $user->nik) {
                $dosenQuery = DB::table('dosen')->where('nik', $user->nik);
                if (Schema::hasColumn('dosen', 'deleted_at')) {
                    $dosenQuery->whereNull('deleted_at');
                }

                $dosen = $dosenQuery->first();
            }
            if (!$dosen) {
                return null;
            }

            $sessionData['dosen_id']   = $dosen->id;
            $sessionData['nik']        = $dosen->nik;
            $sessionData['nip']        = $dosen->nip;
            $sessionData['name']       = $dosen->nama;
            $sessionData['tipe_dosen'] = $dosen->tipe_dosen;
            $sessionData['fakultas']   = $dosen->fakultas ?? null;

            // Map tipe_dosen value ke role
            $tipeDosen = strtolower($dosen->tipe_dosen ?? '');

            if (strpos($tipeDosen, 'wali') !== false && strpos($tipeDosen, 'matakuliah') !== false) {
                // "Dosen Wali & Matakuliah" atau "keduanya"
                $sessionData['role'] = 'dosen';
            } elseif (strpos($tipeDosen, 'wali') !== false) {
                // "Dosen Wali"
                $sessionData['role'] = 'dosen_wali';
            } else {
                // "Dosen Mata Kuliah" atau lainnya
                $sessionData['role'] = 'dosen_mk';
            }
        }

        return $sessionData;
    }
}
