<?php
use App\Http\Controllers\Auth\LoginController;

// Landing Page (Homepage) - Public
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::view('/admin/dashboard', 'admin.dashboard');
Route::view('/mahasiswa/dashboard', 'mahasiswa.dashboard');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});