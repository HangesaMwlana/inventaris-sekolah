<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('welcome'); });

Route::middleware(['auth', 'verified'])->group(function () {
    // DASHBOARD: Semua Role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // BARANG: Index Semua Role, sisanya Petugas
    Route::get('/barangs', [BarangController::class, 'index'])->name('barangs.index');
    
    // PEMINJAMAN: Index & Store
    Route::get('/peminjamans', [PeminjamanController::class, 'index'])->name('peminjamans.index');
    Route::post('/peminjamans', [PeminjamanController::class, 'store'])->name('peminjamans.store');

    // KHUSUS PETUGAS
    Route::middleware('role:petugas')->group(function () {
        Route::post('/barangs', [BarangController::class, 'store'])->name('barangs.store');
        Route::put('/barangs/{barang}', [BarangController::class, 'update'])->name('barangs.update');
        Route::delete('/barangs/{barang}', [BarangController::class, 'destroy'])->name('barangs.destroy');
        Route::post('/peminjamans/{peminjaman}/approve', [PeminjamanController::class, 'approve'])->name('peminjamans.approve');
        Route::post('/peminjamans/{peminjaman}/return', [PeminjamanController::class, 'return'])->name('peminjamans.return');
    });

    // KHUSUS ADMIN
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';