<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;

// Komponen Livewire untuk login
use App\Livewire\Auth\Login;
// Breeze/Volt verify controller (kalau pakai)
use App\Http\Controllers\Auth\VerifyEmailController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

// Tamu (belum login)
Route::middleware('guest')->group(function () {
    // LOGIN via Livewire
    Route::get('/login', Login::class)->name('login');

    // REGISTER & PASSWORD (opsional pakai Volt/Breeze)
    Volt::route('register', 'pages.auth.register')->name('register');
    Volt::route('forgot-password', 'pages.auth.forgot-password')->name('password.request');
    Volt::route('reset-password/{token}', 'pages.auth.reset-password')->name('password.reset');
});

// Sudah login
Route::middleware('auth')->group(function () {
    // Verifikasi email (opsional)
    Volt::route('verify-email', 'pages.auth.verify-email')->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // Konfirmasi password (opsional)
    Volt::route('confirm-password', 'pages.auth.confirm-password')->name('password.confirm');

    // LOGOUT — HARUS POST
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('home'); // "/" kamu
    })->name('logout');
});
