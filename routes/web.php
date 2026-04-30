<?php

use App\Http\Controllers\Auth\SimpleLoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenWaliController;
use App\Http\Controllers\KrsVerifikasiController;
use App\Http\Controllers\KhsMahasiswaController;
use App\Http\Controllers\ProfilDosenWaliController;
use App\Http\Controllers\DosenMKController;
use Illuminate\Support\Facades\Route;

// PUBLIC ROUTES
Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/login', [SimpleLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [SimpleLoginController::class, 'login']);

// PROTECTED ROUTES
Route::middleware('check.simple.auth')->group(function () {

    Route::post('/logout', [SimpleLoginController::class, 'logout'])->name('logout');

    // ADMIN ROUTES
    Route::get('/admin/dashboard', [AdminController::class, 'dashboardAdmin'])->name('pages.admin.dashboard');

    Route::prefix('admin/mahasiswa')->name('pages.admin.mahasiswa.')->group(function () {
        Route::get('/', [AdminController::class, 'indexMahasiswa'])->name('index');
        Route::get('/create', [AdminController::class, 'createMahasiswa'])->name('create');
        Route::post('/', [AdminController::class, 'storeMahasiswa'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editMahasiswa'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateMahasiswa'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyMahasiswa'])->name('destroy');
    });

    Route::prefix('admin/dosen')->name('pages.admin.dosen.')->group(function () {
        Route::get('/', [AdminController::class, 'indexDosen'])->name('index');
        Route::get('/create', [AdminController::class, 'createDosen'])->name('create');
        Route::post('/', [AdminController::class, 'storeDosen'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editDosen'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateDosen'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyDosen'])->name('destroy');
    });

    Route::prefix('admin/matakuliah')->name('pages.admin.matakuliah.')->group(function () {
        Route::get('/', [AdminController::class, 'indexMatakuliah'])->name('index');
        Route::get('/create', [AdminController::class, 'createMatakuliah'])->name('create');
        Route::post('/', [AdminController::class, 'storeMatakuliah'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editMatakuliah'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateMatakuliah'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyMatakuliah'])->name('destroy');
    });

    Route::prefix('admin/tahun-ajaran')->name('pages.admin.tahunajaran.')->group(function () {
        Route::get('/', [AdminController::class, 'indexTahunAjaran'])->name('index');
        Route::get('/create', [AdminController::class, 'createTahunAjaran'])->name('create');
        Route::post('/', [AdminController::class, 'storeTahunAjaran'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editTahunAjaran'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateTahunAjaran'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyTahunAjaran'])->name('destroy');
    });

    Route::prefix('admin/paket-mk')->name('pages.admin.paketmk.')->group(function () {
        Route::get('/', [AdminController::class, 'indexPaketMK'])->name('index');
        Route::get('/create', [AdminController::class, 'createPaketMK'])->name('create');
        Route::post('/', [AdminController::class, 'storePaketMK'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editPaketMK'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updatePaketMK'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyPaketMK'])->name('destroy');
    });

    // MAHASISWA ROUTES
    Route::prefix('mahasiswa')->name('pages.mahasiswa.')->group(function () {
        Route::get('/beranda', [MahasiswaController::class, 'index'])->name('beranda');
        Route::get('/ambil-krs', [MahasiswaController::class, 'ambilKrs'])->name('ambil-krs');
        Route::post('/ambil-krs', [MahasiswaController::class, 'storeKrs'])->name('store-krs');
        Route::get('/lihat-khs', [KhsMahasiswaController::class, 'index'])->name('lihat-khs');
        Route::get('/profil', [MahasiswaController::class, 'profil'])->name('profil');
        Route::put('/profil', [MahasiswaController::class, 'updateProfil'])->name('profil.update');
        Route::get('/api/paket-semester', [MahasiswaController::class, 'getPaketSemester'])->name('api.paket-semester');
    });

    // DOSEN ROUTES
    Route::prefix('dosen')->name('dosen.')->group(function () {

        // Beranda alias (dipakai di empty-access redirect)
        Route::get('/beranda', function () {
            return redirect()->route('dosen.wali.beranda');
        })->name('dashboard');

        // --- Dosen Wali ---
        Route::prefix('wali')->name('wali.')->group(function () {
            Route::get('/beranda', [DosenWaliController::class, 'index'])->name('beranda');
            Route::get('/krs-verifikasi', [DosenWaliController::class, 'verifikasiKrs'])->name('krs-verifikasi');
            Route::patch('/krs/{nim}/approve', [DosenWaliController::class, 'approveKrs'])->name('krs.approve');
            Route::delete('/krs/{nim}/reject', [DosenWaliController::class, 'rejectKrs'])->name('krs.reject');
            Route::get('/khs', [DosenWaliController::class, 'khs'])->name('khs');
        });

        // --- Dosen Mata Kuliah ---
        Route::prefix('mk')->name('mk.')->group(function () {
            Route::get('/beranda', [DosenMKController::class, 'index'])->name('beranda');
            Route::get('/input-nilai', [DosenMKController::class, 'inputNilai'])->name('input-nilai');
            Route::post('/simpan-nilai', [DosenMKController::class, 'simpanNilai'])->name('simpan-nilai');
            Route::get('/lihat-nilai', [DosenMKController::class, 'lihatNilai'])->name('lihat-nilai');
        });

        // --- Profil Dosen (Shared) ---
        Route::get('/profil', [ProfilDosenWaliController::class, 'index'])->name('profil');
        Route::put('/profil', [ProfilDosenWaliController::class, 'update'])->name('profil.update');
        Route::put('/profil/password', [ProfilDosenWaliController::class, 'updatePassword'])->name('profil.password');

        // Testing Role
        Route::get('/set-role/{role}', function ($role) {
            if ($role === 'wali') {
                session(['is_dosen_wali' => true, 'is_dosen_mk' => false, 'user_name' => 'Dosen Wali Test', 'user_email' => 'wali@test.com']);
            } elseif ($role === 'mk') {
                session(['is_dosen_wali' => false, 'is_dosen_mk' => true, 'user_name' => 'Dosen MK Test', 'user_email' => 'mk@test.com']);
            } elseif ($role === 'both') {
                session(['is_dosen_wali' => true, 'is_dosen_mk' => true, 'user_name' => 'Dosen Lengkap', 'user_email' => 'lengkap@test.com']);
            }
            return redirect()->route('dosen.wali.beranda')->with('success', "✅ Role diubah ke: $role");
        })->name('set.role');
    });

    // =========================================================
    // ALIAS ROUTES — menyamakan nama yang dipanggil di views
    // =========================================================

    // Dosen Wali aliases
    Route::get('/dosen/wali/krs-verifikasi-alias', [DosenWaliController::class, 'verifikasiKrs'])->name('pages.dosen_wali.krs.verifikasi');
    Route::patch('/dosen/wali/krs/{nim}/approve', [DosenWaliController::class, 'approveKrs'])->name('pages.dosen_wali.krs.approve');
    Route::delete('/dosen/wali/krs/{nim}/reject', [DosenWaliController::class, 'rejectKrs'])->name('pages.dosen_wali.krs.reject');
    Route::get('/dosen/wali/khs-alias', [DosenWaliController::class, 'khs'])->name('pages.dosen_wali.khs');
    Route::put('/dosen/wali/profil', [ProfilDosenWaliController::class, 'update'])->name('pages.dosen_wali.profil.update');

    // Dosen MK aliases
    Route::get('/dosen/mk/lihat-nilai-alias', [DosenMKController::class, 'lihatNilai'])->name('pages.dosen_matkul.lihat-nilai');
    Route::get('/dosen/mk/lihat-nilai-alias2', [DosenMKController::class, 'lihatNilai'])->name('dosen_matkul.lihat-nilai');
    Route::put('/dosen/mk/profil', [ProfilDosenWaliController::class, 'update'])->name('pages.dosen_matkul.profil.update');
    Route::put('/dosen/mk/profil/password', [ProfilDosenWaliController::class, 'updatePassword'])->name('pages.dosen_matkul.profil.password');

});
