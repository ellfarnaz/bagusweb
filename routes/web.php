<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\MakananController;
use App\Http\Controllers\MinumanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
})->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.post');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

// Grup route yang memerlukan authentication
Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('makanan',[MakananController::class, 'index'])->name('makanan.index');
    Route::get('tambah-makanan',[MakananController::class, 'tambah']);
    Route::post('simpan-makanan',[MakananController::class, 'simpan']);
    Route::get('edit-makanan/{id}',[MakananController::class, 'edit']);
    Route::post('update-makanan/{id}',[MakananController::class, 'update']);
    Route::get('hapus-makanan/{id}',[MakananController::class, 'hapus']);

    Route::get('minuman',[MinumanController::class, 'index'])->name('minuman.index');
    Route::get('tambah-minuman',[MinumanController::class, 'tambah']);
    Route::post('simpan-minuman',[MinumanController::class, 'simpan']);
    Route::get('edit-minuman/{id}',[MinumanController::class, 'edit']);
    Route::post('update-minuman/{id}',[MinumanController::class, 'update']);
    Route::get('hapus-minuman/{id}',[MinumanController::class, 'hapus']);

    Route::resource('transaksi', TransaksiController::class)->only(['index', 'create', 'store']);
    Route::post('transaksi/{transaksi}/accept', [TransaksiController::class, 'accept'])->name('transaksi.accept');
    Route::post('transaksi/{transaksi}/reject', [TransaksiController::class, 'reject'])->name('transaksi.reject');
    Route::post('transaksi/{transaksi}/cetak', [TransaksiController::class, 'nota'])->name('transaksi.cetak.nota');
    Route::post('cetak/{id}', [TransaksiController::class, 'cetakNota'])->name('transaksi.cetak');

    // Route Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
});