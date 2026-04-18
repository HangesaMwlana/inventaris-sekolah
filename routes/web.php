<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| HALAMAN PUBLIK
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome')->name('home');
Route::view('/landing', 'landing')->name('landing');
Route::view('/fitur', 'fitur')->name('fitur');


/*
|--------------------------------------------------------------------------
| SETELAH LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    // BARANG
    Route::get('/barangs', [BarangController::class, 'index'])
        ->name('barangs.index');


    /*
    |--------------------------------------------------------------------------
    | PETUGAS + ADMIN
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:petugas,admin')->group(function () {

        // ROUTE CETAK HARUS DI ATAS RESOURCE AGAR TIDAK BENTROK
        Route::get('/barangs/cetak', [BarangController::class, 'cetakLaporan'])
            ->name('barangs.cetak');

        Route::get('/peminjamans/cetak', [PeminjamanController::class, 'cetakLaporan'])
            ->name('peminjamans.cetak');

        Route::post('/barangs', [BarangController::class, 'store'])
            ->name('barangs.store');

        Route::put('/barangs/{barang}', [BarangController::class, 'update'])
            ->name('barangs.update');

        Route::delete('/barangs/{barang}', [BarangController::class, 'destroy'])
            ->name('barangs.destroy');

        Route::post('/peminjamans/{peminjaman}/approve', [PeminjamanController::class, 'approve'])
            ->name('peminjamans.approve');

        Route::post('/peminjamans/{peminjaman}/return', [PeminjamanController::class, 'return'])
            ->name('peminjamans.return');
    });


    // 🔥 PEMINJAMAN RESOURCE (Diletakkan di bawah route cetak)
    Route::resource('peminjamans', PeminjamanController::class);


    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {

        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');

        Route::put('/users/{user}', [UserController::class, 'update'])
            ->name('users.update');

        Route::delete('/users/{user}', [UserController::class, 'destroy'])
            ->name('users.destroy');
    });

});

require __DIR__.'/auth.php';