<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ReportController;

// kalau buka root langsung lempar ke form login aja
Route::get('/', function () {
    return redirect()->route('login');
});

// semua route operasional dikumpulin di bawah prefix /admin
Route::prefix('admin')->group(function () {
    // jalur login & logout umum
    Route::get('/', [LoginController::class, 'loginView'])->name('loginView');
    Route::get('/login', [LoginController::class, 'loginView'])->name('login');
    Route::post('/action-login', [LoginController::class, 'action_login'])->name('action-login');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout.get'); // buat jaga-jaga kalau user logout lewat link biasa
    
    // halaman yang wajib login dulu
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // cek stok: disesuaikan dengan permission 'stock'
        Route::middleware('permission:stock')->group(function () {
            Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
        });

        // transaksi POS kasir: disesuaikan dengan permission 'pos'
        Route::middleware('permission:pos')->group(function () {
            Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
            Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
            Route::get('/transactions/print/{id}', [TransactionController::class, 'printReceipt'])->name('transactions.print');
        });

        // laporan omzet & penjualan: disesuaikan dengan permission 'reports'
        Route::middleware('permission:reports')->group(function () {
            Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        });

        // kelola data master: masing-masing dilindungi permission spesifik
        Route::middleware('permission:roles')->group(function () {
            Route::resource('roles', RoleController::class);
        });
        Route::middleware('permission:users')->group(function () {
            Route::resource('users', UserController::class);
        });
        Route::middleware('permission:categories')->group(function () {
            Route::resource('categories', CategoryController::class);
        });
        Route::middleware('permission:products')->group(function () {
            Route::resource('products', ProductController::class);
        });
    });
});


