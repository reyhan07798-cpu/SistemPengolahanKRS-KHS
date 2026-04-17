<?php
use App\Http\Controllers\Auth\SimpleLoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MahasiswaController;

// Landing Page (Homepage) - Public
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Authentication Routes - Public
Route::get('/login', [SimpleLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [SimpleLoginController::class, 'login']);

// Protected Routes - Require Auth
Route::middleware('check.simple.auth')->group(function () {
    Route::post('/logout', [SimpleLoginController::class, 'logout'])->name('logout');
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');    
    Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'index'])->name('mahasiswa.dashboard');    
    Route::view('/dashboard/dosen-wali', 'dosen_wali.dashboard');
    Route::view('/dashboard/dosen-matkul', 'dosen_matkul.dashboard');
    Route::get('/dashboard', function () {
        return view('dosen_wali.dashboard');
    })->name('dashboard');
});