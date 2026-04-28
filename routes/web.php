<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\GeofencingController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LogWaController;
use App\Http\Controllers\GuruDashboardController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->role === 'guru') {
            return redirect()->route('guru.dashboard');
        } else {
            return redirect()->route('presensi.index');
        }
    }
    return redirect()->route('login');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('siswa', SiswaController::class);
    Route::resource('guru', GuruController::class);
    Route::post('geofencing/toggle', [GeofencingController::class, 'toggle'])->name('geofencing.toggle');
    Route::get('geofencing', [GeofencingController::class, 'index'])->name('geofencing.index');
    Route::put('geofencing', [GeofencingController::class, 'update'])->name('geofencing.update');
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('log-wa', [LogWaController::class, 'index'])->name('log-wa.index');
});

Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/guru', [GuruDashboardController::class, 'index'])->name('guru.dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');
    Route::get('/presensi/riwayat', [PresensiController::class, 'riwayat'])->name('presensi.riwayat');
});