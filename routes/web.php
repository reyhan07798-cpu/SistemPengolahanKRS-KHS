<?php

use App\Http\Controllers\Auth\SimpleLoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KrsVerifikasiController;
use App\Http\Controllers\KhsMahasiswaController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\DosenMKController;

// Landing Page (Homepage) - Public
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Authentication Routes - Public
Route::get('/login', [SimpleLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [SimpleLoginController::class, 'login']);

// KRS Verifikasi (Dosen Wali)
Route::get('/dosen_wali/krs-verifikasi', [KrsVerifikasiController::class, 'index'])->name('krs.verifikasi');
Route::patch('/dosen_wali/krs/approve/{nim}', [KrsVerifikasiController::class, 'approve'])->name('krs.approve');
Route::delete('/dosen_wali/krs/reject/{nim}', [KrsVerifikasiController::class, 'reject'])->name('krs.reject');

// Protected Routes - Require Auth
Route::middleware('check.simple.auth')->group(function () {

    // Logout
    Route::post('/logout', [SimpleLoginController::class, 'logout'])->name('logout');

    // Admin Routes
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Mahasiswa Routes
    Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'index'])->name('mahasiswa.dashboard');

    // Dosen Wali Routes
    Route::get('/dosen_wali/dashboard', [DashboardController::class, 'index'])->name('dosen_wali.dashboard');
    Route::get('/dosen-wali/khs', [KhsMahasiswaController::class, 'index'])->name('khs.index');
    Route::get('/dosen-wali/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::put('/dosen-wali/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::put('/dosen-wali/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');

    // Dosen Mata Kuliah Routes
Route::middleware('check.simple.auth')->group(function () {
    Route::get('/dosen_matkul/beranda', [DosenMKController::class, 'index'])->name('dosen_matkul.beranda');
    Route::get('/dosen_matkul/input-nilai', [DosenMKController::class, 'inputNilai'])->name('dosen_matkul.input-nilai');
    Route::post('/dosen_matkul/simpan-nilai', [DosenMKController::class, 'simpanNilai'])->name('dosen_matkul.simpan-nilai');
    Route::get('/dosen_matkul/lihat-nilai', [DosenMKController::class, 'lihatNilai'])->name('dosen_matkul.lihat-nilai');
    Route::get('/dosen_matkul/profil', [DosenMKController::class, 'profil'])->name('dosen_matkul.profil');
    Route::put('/dosen_matkul/profil', [DosenMKController::class, 'update'])->name('dosen_matkul.profil.update');
    Route::put('/dosen_matkul/profil/password', [DosenMKController::class, 'updatePassword'])->name('dosen_matkul.profil.password');
});
});