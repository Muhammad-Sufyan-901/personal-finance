<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;

// Jika user belum login (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Jika user sudah login (Auth)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Rute sementara untuk dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute CRUD Transaksi
    Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
    Route::post('/transactions/import', [TransactionController::class, 'import'])->name('transactions.import');
    Route::get('/transactions/{id}/proofs/download-all', [TransactionController::class, 'downloadAllProofs'])->name('transactions.proofs.download');
    Route::get('/transactions/{id}/proof/{index}', [TransactionController::class, 'showProofImage'])->name('transactions.proof.show');
    Route::post('/transactions/bulk-delete', [TransactionController::class, 'bulkDelete'])->name('transactions.bulkDelete');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{id}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
});

// Redirect root ke login
Route::get('/', function () {
    return redirect('/login');
});