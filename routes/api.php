<?php

namespace App\Http\controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\DashboardController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Autentikasi
Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);

// Buku
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/category/{category}', [BookController::class, 'getBooksByCategory']);
Route::put('/books/{id}', [BookController::class, 'update']);
Route::post('/books', [BookController::class, 'store']);



// Pinjaman (tidak butuh login)
Route::get('/loans/borrowed', [LoanController::class, 'borrowed']); // GET data peminjaman yang belum kembali
Route::get('/loans/returned', [LoanController::class, 'returned']); 
Route::get('/loans', [LoanController::class, 'index']);
Route::get('/loans/history-all', [LoanController::class, 'historyAll']);
// GET data yang sudah dikembalikan

// Endpoint admin/staff untuk melihat semua pinjaman (jika diperlukan tanpa login)
Route::post('/loans', [LoanController::class, 'index']); // POST: melihat daftar pinjaman (umum)
Route::get('/dashboard', [DashboardController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Protected Routes - Butuh Autentikasi
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Profil pengguna
    Route::get('/profile', function (Request $request) {
        return $request->user();
    });
    Route::put('/profile/update', [UserController::class, 'updateProfile']);

    // Peminjaman & Riwayat Pribadi
    Route::post('/loans/borrow', [LoanController::class, 'borrow']); // POST pinjam buku
    Route::post('/loans/return/{loanId}', [LoanController::class, 'returnBook']); // POST pengembalian buku
    Route::get('/history', [LoanController::class, 'history']); // GET riwayat pribadi peminjaman
    Route::delete('/books/{id}', [BookController::class, 'destroy']);

    Route::delete('/books/{id}', [BookController::class, 'destroy']);

});
