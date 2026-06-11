<?php

namespace App\Http\Controllers;

use App\Support\PasswordVerifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfilDosenWaliController extends Controller
{
    // ── Helper: ambil data dosen dari session + tabel dosen ──────────
    private function getDosenFromSession()
    {
        $sess = session('user', []);
        $dosenId = $sess['dosen_id'] ?? null;

        if ($dosenId) {
            $dosen = DB::table('dosen')
                ->leftJoin('users', 'dosen.user_id', '=', 'users.id')
                ->where('dosen.id', $dosenId)
                ->select('dosen.*', 'users.id as user_id', 'users.role')
                ->first();
            if ($dosen) return $dosen;
        }

        // fallback by nik
        $nik = $sess['nik'] ?? null;
        if ($nik) {
            $dosen = DB::table('dosen')
                ->leftJoin('users', 'dosen.user_id', '=', 'users.id')
                ->where('dosen.nik', $nik)
                ->select('dosen.*', 'users.id as user_id', 'users.role')
                ->first();
            if ($dosen) return $dosen;
        }

        return null;
    }

    // ─────────────────────────────────────────────────────────────────
    //  SHOW PROFIL
    // ─────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $dbDosen = $this->getDosenFromSession();
        $sess    = session('user', []);

        $dosen = [
            'nama'          => $dbDosen->nama   ?? $sess['name']  ?? 'Dosen',
            'nip'           => $dbDosen->nip    ?? '-',
            'nidn'          => $dbDosen->nik    ?? $sess['nik']   ?? '-',
            'email'         => $dbDosen->email  ?? $sess['email'] ?? '-',
            'no_hp'         => $dbDosen->no_hp  ?? '-',
            'alamat'        => $dbDosen->alamat ?? '-',
            'program_studi' => 'Teknik Informatika',
            'tipe_dosen'    => $dbDosen->tipe_dosen ?? 'dosen_mk',
        ];

        // Tentukan view berdasarkan route
        if ($request->routeIs('pages.dosen_matkul.*') || ($dbDosen && $dbDosen->tipe_dosen === 'dosen_mk')) {
            return view('pages.dosen_matkul.profil', compact('dosen'));
        }
        return view('pages.dosen_wali.profil', compact('dosen'));
    }

    // ─────────────────────────────────────────────────────────────────
    //  UPDATE PROFIL
    // ─────────────────────────────────────────────────────────────────
    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:100',
            'email'  => 'required|email|max:100',
            'no_hp'  => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);

        $dbDosen = $this->getDosenFromSession();
        if (!$dbDosen) return redirect()->back()->with('error', 'Data dosen tidak ditemukan.');

        // Update tabel dosen
        DB::table('dosen')->where('id', $dbDosen->id)->update([
            'nama'       => $validated['nama'],
            'email'      => $validated['email'],
            'no_hp'      => $validated['no_hp'] ?? $dbDosen->no_hp,
            'alamat'     => $validated['alamat'] ?? $dbDosen->alamat,
            'updated_at' => now(),
        ]);

        // Update tabel users juga (email & name)
        if ($dbDosen->user_id) {
            DB::table('users')->where('id', $dbDosen->user_id)->update([
                'name'       => $validated['nama'],
                'email'      => $validated['email'],
                'updated_at' => now(),
            ]);
        }

        // Update session agar nama tampil langsung
        $user = session('user', []);
        $user['name']  = $validated['nama'];
        $user['email'] = $validated['email'];
        session(['user' => $user, 'user_name' => $validated['nama']]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

    // ─────────────────────────────────────────────────────────────────
    //  UPDATE PASSWORD
    // ─────────────────────────────────────────────────────────────────
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama'              => 'required|string',
            'password_baru'              => 'required|string|min:6',
            'password_baru_confirmation' => 'required|same:password_baru',
        ], [
            'password_baru.min'               => 'Password baru minimal 6 karakter.',
            'password_baru_confirmation.same' => 'Konfirmasi password tidak cocok.',
        ]);

        $dbDosen = $this->getDosenFromSession();
        if (!$dbDosen || !$dbDosen->user_id) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan.');
        }

        $userDb = DB::table('users')->where('id', $dbDosen->user_id)->first();
        if (!$userDb) return redirect()->back()->with('error', 'Akun tidak ditemukan.');

        $valid = PasswordVerifier::check($request->password_lama, $userDb->password);

        if (!$valid) {
            return redirect()->back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
        }

        DB::table('users')->where('id', $dbDosen->user_id)->update([
            'password'   => Hash::make($request->password_baru),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Password berhasil diubah.');
    }
}
