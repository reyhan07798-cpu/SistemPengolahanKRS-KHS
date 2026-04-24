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

// ==========================================
// PUBLIC ROUTES
// ==========================================
Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/login', [SimpleLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [SimpleLoginController::class, 'login']);

// ==========================================
// PROTECTED ROUTES
// ==========================================
Route::middleware('check.simple.auth')->group(function () {

    // Logout
    Route::post('/logout', [SimpleLoginController::class, 'logout'])->name('logout');

    // ==========================================
    // ADMIN
    // ==========================================
    Route::get('/admin/dashboard', [AdminController::class, 'dashboardAdmin'])->name('admin.dashboard');

    Route::prefix('admin/mahasiswa')->name('admin.mahasiswa.')->group(function () {
        Route::get('/', [AdminController::class, 'indexMahasiswa'])->name('index');
        Route::get('/create', [AdminController::class, 'createMahasiswa'])->name('create');
        Route::post('/', [AdminController::class, 'storeMahasiswa'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editMahasiswa'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateMahasiswa'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyMahasiswa'])->name('destroy');
    });

    Route::prefix('admin/dosen')->name('admin.dosen.')->group(function () {
        Route::get('/', [AdminController::class, 'indexDosen'])->name('index');
        Route::get('/create', [AdminController::class, 'createDosen'])->name('create');
        Route::post('/', [AdminController::class, 'storeDosen'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editDosen'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateDosen'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyDosen'])->name('destroy');
    });

    Route::prefix('admin/matakuliah')->name('admin.matakuliah.')->group(function () {
        Route::get('/', [AdminController::class, 'indexMatakuliah'])->name('index');
        Route::get('/create', [AdminController::class, 'createMatakuliah'])->name('create');
        Route::post('/', [AdminController::class, 'storeMatakuliah'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editMatakuliah'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateMatakuliah'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyMatakuliah'])->name('destroy');
    });

    // ==========================================
    // MAHASISWA
    // ==========================================
    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/beranda', [MahasiswaController::class, 'index'])->name('beranda');

        Route::get('/ambil-krs', [MahasiswaController::class, 'ambilKrs'])->name('ambil-krs');
        Route::post('/ambil-krs', [MahasiswaController::class, 'storeKrs'])->name('store-krs');

        Route::get('/lihat-khs', [KhsMahasiswaController::class, 'index'])->name('lihat-khs');

        // ✅ RUTE PROFIL MAHASISWA (SHOW + UPDATE)
        Route::get('/profil', [MahasiswaController::class, 'profil'])->name('profil');
        Route::put('/profil', [MahasiswaController::class, 'updateProfil'])->name('profil.update');        // ↑ Route ini yang sebelumnya missing, sekarang sudah ditambahkan

    });

    // ==========================================
    // DOSEN WALI
    // ==========================================
    Route::prefix('dosen-wali')->name('dosen_wali.')->group(function () {
        Route::get('/beranda', [DosenWaliController::class, 'index'])->name('beranda');

        // KRS Verifikasi
        Route::get('/krs-verifikasi', [KrsVerifikasiController::class, 'index'])->name('krs.verifikasi');
        Route::patch('/krs/approve/{nim}', [KrsVerifikasiController::class, 'approve'])->name('krs.approve');
        Route::delete('/krs/reject/{nim}', [KrsVerifikasiController::class, 'reject'])->name('krs.reject');

        // ✅ KHS DOSEN (controller sama, beda URL → auto beda logic)
        Route::get('/khs', [KhsMahasiswaController::class, 'index'])->name('khs');

        // Profil
        Route::get('/profil', [ProfilDosenWaliController::class, 'index'])->name('profil');
        Route::post('/dosen-wali/profil/update', [DosenWaliController::class, 'update'])->name('profil.update');        
        Route::put('/profil/password', [ProfilDosenWaliController::class, 'updatePassword'])->name('profil.password');
        Route::post('/dosen-wali/profil/update', [DosenWaliController::class, 'updateProfil'])->name('profil.update');
    });

    // ==========================================
    // DOSEN MATA KULIAH
    // ==========================================
    Route::prefix('dosen_matkul')->name('dosen_matkul.')->group(function () {

        Route::get('/beranda', [DosenMKController::class, 'index'])->name('beranda');
        Route::get('/input-nilai', [DosenMKController::class, 'inputNilai'])->name('input-nilai');
        Route::post('/simpan-nilai', [DosenMKController::class, 'simpanNilai'])->name('simpan-nilai');
        Route::get('/lihat-nilai', [DosenMKController::class, 'lihatNilai'])->name('lihat-nilai');

        Route::get('/profil', [DosenMKController::class, 'profil'])->name('profil');
        Route::put('/profil', [DosenMKController::class, 'update'])->name('profil.update');
        Route::put('/profil/password', [DosenMKController::class, 'updatePassword'])->name('profil.password');
    });

    // ==========================================
    // ADMIN TAMBAHAN
    // ==========================================
    Route::prefix('admin/tahun-ajaran')->name('admin.tahunajaran.')->group(function () {
        Route::get('/', [AdminController::class, 'indexTahunAjaran'])->name('index');
        Route::get('/create', [AdminController::class, 'createTahunAjaran'])->name('create');
        Route::post('/', [AdminController::class, 'storeTahunAjaran'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editTahunAjaran'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateTahunAjaran'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyTahunAjaran'])->name('destroy');
    });

    Route::prefix('admin/paket-mk')->name('admin.paketmk.')->group(function () {
        Route::get('/', [AdminController::class, 'indexPaketMK'])->name('index');
        Route::get('/create', [AdminController::class, 'createPaketMK'])->name('create');
        Route::post('/', [AdminController::class, 'storePaketMK'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editPaketMK'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updatePaketMK'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyPaketMK'])->name('destroy');
    });

});