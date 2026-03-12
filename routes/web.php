<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoriesController;

Route::get('/', [DashboardController::class, 'index']);

Route::get('/HOME', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/TRANSAKSI', [TransactionController::class, 'index'])->name('transaction');

Route::get('/KATEGORI', [CategoriesController::class, 'index'])->name('categories');
