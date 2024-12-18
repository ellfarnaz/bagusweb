<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Profile
    Route::post('/profile/update', [ProfileController::class, 'update']);
    
    // Menu
    Route::get('/makanan', [MenuController::class, 'getMakanan']);
    Route::get('/minuman', [MenuController::class, 'getMinuman']);
    
    // Transaksi
    Route::post('/transaksi/create', [TransaksiController::class, 'store']);
    Route::get('/transaksi', [TransaksiController::class, 'index']);
    Route::get('/transaksi/{id}', [TransaksiController::class, 'show']);
    Route::post('/transaksi/{id}/accept', [TransaksiController::class, 'accept']);
    Route::post('/transaksi/{id}/reject', [TransaksiController::class, 'reject']);
});

// Routes khusus admin
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/tambah-makanan', [MenuController::class, 'store']);
    Route::put('/update-makanan/{id}', [MenuController::class, 'update']);
    Route::delete('/hapus-makanan/{id}', [MenuController::class, 'destroy']);
    
    Route::post('/tambah-minuman', [MenuController::class, 'store']);
    Route::put('/update-minuman/{id}', [MenuController::class, 'update']);
    Route::delete('/hapus-minuman/{id}', [MenuController::class, 'destroy']);
});
