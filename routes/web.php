<?php

use App\Http\Controllers\Auth\SimpleLoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KrsVerifikasiController;
use App\Http\Controllers\KhsMahasiswaController;
use App\Http\Controllers\ProfilController;
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
// PROTECTED ROUTES (Middleware Auth)
// ==========================================
Route::middleware('check.simple.auth')->group(function () {

    // Logout
    Route::post('/logout', [SimpleLoginController::class, 'logout'])->name('logout');

    // ==========================================
    // ADMIN ROUTES
    // ==========================================
    
    // 1. Dashboard Admin
    Route::get('/admin/dashboard', [AdminController::class, 'dashboardAdmin'])->name('admin.dashboard');

    // 2. Mahasiswa Routes (CRUD)
    Route::prefix('admin/mahasiswa')->name('admin.mahasiswa.')->group(function () {
        Route::get('/', [AdminController::class, 'indexMahasiswa'])->name('index');
        Route::get('/create', [AdminController::class, 'createMahasiswa'])->name('create');
        Route::post('/', [AdminController::class, 'storeMahasiswa'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editMahasiswa'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateMahasiswa'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyMahasiswa'])->name('destroy');
    });

    // 3. Dosen Routes (CRUD)
    Route::prefix('admin/dosen')->name('admin.dosen.')->group(function () {
        Route::get('/', [AdminController::class, 'indexDosen'])->name('index');
        Route::get('/create', [AdminController::class, 'createDosen'])->name('create');
        Route::post('/', [AdminController::class, 'storeDosen'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editDosen'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateDosen'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyDosen'])->name('destroy');
    });

    // 4. Mata Kuliah Routes (CRUD) - BARU DITAMBAHKAN
    Route::prefix('admin/matakuliah')->name('admin.matakuliah.')->group(function () {
        Route::get('/', [AdminController::class, 'indexMatakuliah'])->name('index');
        Route::get('/create', [AdminController::class, 'createMatakuliah'])->name('create');
        Route::post('/', [AdminController::class, 'storeMatakuliah'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editMatakuliah'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateMatakuliah'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyMatakuliah'])->name('destroy');
    });

    // ==========================================
    // MAHASISWA ROUTES
    // ==========================================
    Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'index'])->name('mahasiswa.dashboard');

    // ==========================================
    // DOSEN WALI ROUTES
    // ==========================================
    Route::get('/dosen_wali/dashboard', [DashboardController::class, 'index'])->name('dosen_wali.dashboard');
    
    // KRS Verifikasi
    Route::get('/dosen_wali/krs-verifikasi', [KrsVerifikasiController::class, 'index'])->name('krs.verifikasi');
    Route::patch('/dosen_wali/krs/approve/{nim}', [KrsVerifikasiController::class, 'approve'])->name('krs.approve');
    Route::delete('/dosen_wali/krs/reject/{nim}', [KrsVerifikasiController::class, 'reject'])->name('krs.reject');

    // KHS & Profil Dosen Wali
    Route::get('/dosen-wali/khs', [KhsMahasiswaController::class, 'index'])->name('khs.index');
    Route::get('/dosen-wali/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::put('/dosen-wali/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::put('/dosen-wali/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');

    // ==========================================
    // DOSEN MATA KULIAH ROUTES
    // ==========================================
    Route::get('/dosen_matkul/beranda', [DosenMKController::class, 'index'])->name('dosen_matkul.beranda');
    Route::get('/dosen_matkul/input-nilai', [DosenMKController::class, 'inputNilai'])->name('dosen_matkul.input-nilai');
    Route::post('/dosen_matkul/simpan-nilai', [DosenMKController::class, 'simpanNilai'])->name('dosen_matkul.simpan-nilai');
    Route::get('/dosen_matkul/lihat-nilai', [DosenMKController::class, 'lihatNilai'])->name('dosen_matkul.lihat-nilai');
    Route::get('/dosen_matkul/profil', [DosenMKController::class, 'profil'])->name('dosen_matkul.profil');
    Route::put('/dosen_matkul/profil', [DosenMKController::class, 'update'])->name('dosen_matkul.profil.update');
    Route::put('/dosen_matkul/profil/password', [DosenMKController::class, 'updatePassword'])->name('dosen_matkul.profil.password');


    // 5. Tahun Ajaran Routes (CRUD) - BARU DITAMBAHKAN
    Route::prefix('admin/tahun-ajaran')->name('admin.tahunajaran.')->group(function () {
        Route::get('/', [AdminController::class, 'indexTahunAjaran'])->name('index');
        Route::get('/create', [AdminController::class, 'createTahunAjaran'])->name('create');
        Route::post('/', [AdminController::class, 'storeTahunAjaran'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editTahunAjaran'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateTahunAjaran'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyTahunAjaran'])->name('destroy');
    });


    // 6. Paket Mata Kuliah Routes (CRUD)
    Route::prefix('admin/paket-mk')->name('admin.paketmk.')->group(function () {
        Route::get('/', [AdminController::class, 'indexPaketMK'])->name('index');
        Route::get('/create', [AdminController::class, 'createPaketMK'])->name('create');
        Route::post('/', [AdminController::class, 'storePaketMK'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editPaketMK'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updatePaketMK'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyPaketMK'])->name('destroy');
    });

});