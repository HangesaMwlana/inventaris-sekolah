<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () { 
    return view('welcome'); 
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    // 1. DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 3. BARANG & PEMINJAMAN (Akses Umum: Admin, Petugas, Siswa)
    Route::get('/barangs', [BarangController::class, 'index'])->name('barangs.index');
    Route::get('/peminjamans', [PeminjamanController::class, 'index'])->name('peminjamans.index');
    
    // Create & Delete ditaruh di sini agar Siswa bisa membatalkan pengajuannya sendiri
    Route::post('/peminjamans', [PeminjamanController::class, 'store'])->name('peminjamans.store');
    Route::delete('/peminjamans/{peminjaman}', [PeminjamanController::class, 'destroy'])->name('peminjamans.destroy');


    // 4. AKSES KHUSUS PETUGAS & ADMIN
    Route::middleware('role:petugas,admin')->group(function () {
        
        // --- MANAJEMEN BARANG ---
        Route::get('/barangs/cetak', [BarangController::class, 'cetakLaporan'])->name('barangs.cetak');
        Route::post('/barangs', [BarangController::class, 'store'])->name('barangs.store');
        Route::put('/barangs/{barang}', [BarangController::class, 'update'])->name('barangs.update');
        Route::delete('/barangs/{barang}', [BarangController::class, 'destroy'])->name('barangs.destroy');

        // --- MANAJEMEN PEMINJAMAN ---
        Route::get('/peminjamans/cetak', [PeminjamanController::class, 'cetakLaporan'])->name('peminjamans.cetak');
        
        // Fitur Approval, Pengembalian, & Edit
        Route::post('/peminjamans/{peminjaman}/approve', [PeminjamanController::class, 'approve'])->name('peminjamans.approve');
        Route::post('/peminjamans/{peminjaman}/return', [PeminjamanController::class, 'return'])->name('peminjamans.return');
        Route::put('/peminjamans/{peminjaman}', [PeminjamanController::class, 'update'])->name('peminjamans.update');
    });


    // 5. KHUSUS ADMIN (Manajemen Pengguna)
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

});

require __DIR__.'/auth.php';