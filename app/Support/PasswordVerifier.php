<?php

namespace App\Support;

use Illuminate\Support\Facades\Hash;
use RuntimeException;

class PasswordVerifier
{
    public static function check(string $plainPassword, ?string $storedPassword): bool
    {
        if ($storedPassword === null || $storedPassword === '') {
            return false;
        }

        if (hash_equals($storedPassword, $plainPassword)) {
            return true;
        }

        if ((Hash::info($storedPassword)['algoName'] ?? 'unknown') === 'unknown') {
            return false;
        }

        try {
            return Hash::check($plainPassword, $storedPassword);
        } catch (RuntimeException) {
            return false;
        }
    }
}
