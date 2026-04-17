<?php
use App\Http\Controllers\Auth\SimpleLoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KrsVerifikasiController;

// Landing Page (Homepage) - Public
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Authentication Routes - Public
Route::get('/login', [SimpleLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [SimpleLoginController::class, 'login']);

// KRS Verifikasi
Route::get('/dosen_wali/krs-verifikasi', [KrsVerifikasiController::class, 'index'])->name('krs.verifikasi');
Route::patch('/dosen_wali/krs/approve/{nim}', [KrsVerifikasiController::class, 'approve'])->name('krs.approve');
Route::delete('/dosen_wali/krs/reject/{nim}', [KrsVerifikasiController::class, 'reject'])->name('krs.reject');

// Protected Routes - Require Auth
Route::middleware('check.simple.auth')->group(function () {
    Route::post('/logout', [SimpleLoginController::class, 'logout'])->name('logout');
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');    
    Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'index'])->name('mahasiswa.dashboard');    
    Route::get('/dosen_wali/dashboard', [DashboardController::class, 'index'])->name('dosen_wali.dashboard');    
    Route::view('/dashboard/dosen-matkul', 'dosen_matkul.dashboard');
});