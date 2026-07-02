<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DreamPlanningController;
use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);

Route::get('/HOME', [DashboardController::class, 'index'])->name('dashboard');


Route::get('/TRANSAKSI', [TransactionController::class, 'index'])->name('transaction');
Route::get('/TRANSAKSI/print', [TransactionController::class, 'printPdf'])->name('transaction.print');
Route::get('/TRANSAKSI/trash', [TransactionController::class, 'trash'])->name('transaction.trash');
Route::delete('/TRANSAKSI/trash/empty', [TransactionController::class, 'emptyTrash'])->name('transaction.empty_trash');
Route::post('/TRANSAKSI/{id}/restore', [TransactionController::class, 'restore'])->name('transaction.restore');
Route::delete('/TRANSAKSI/{id}/force', [TransactionController::class, 'forceDelete'])->name('transaction.force_delete');
Route::get('/TRANSAKSI/create', [TransactionController::class, 'create'])->name('transaction.create');
Route::post('/TRANSAKSI', [TransactionController::class, 'store'])->name('transaction.store');
Route::get('/TRANSAKSI/{id}/edit', [TransactionController::class, 'edit'])->name('transaction.edit');
Route::put('/TRANSAKSI/{id}', [TransactionController::class, 'update'])->name('transaction.update');
Route::delete('/TRANSAKSI/{id}', [TransactionController::class, 'destroy'])->name('transaction.destroy');
Route::get('/TRANSAKSI/receipt/{id}', [TransactionController::class, 'showReceipt'])->name('transaction.receipt');

Route::get('/KATEGORI', [CategoriesController::class, 'index'])->name('categories');
Route::get('/KATEGORI/create', [CategoriesController::class, 'create'])->name('categories.create');
Route::post('/KATEGORI', [CategoriesController::class, 'store'])->name('categories.store');
Route::get('/KATEGORI/{id}/edit', [CategoriesController::class, 'edit'])->name('categories.edit');
Route::put('/KATEGORI/{id}', [CategoriesController::class, 'update'])->name('categories.update');
Route::delete('/KATEGORI/{id}', [CategoriesController::class, 'destroy'])->name('categories.destroy');

Route::get('/DREAM', [DreamPlanningController::class, 'index'])->name('dream');
Route::get('/DREAM/create', [DreamPlanningController::class, 'create'])->name('dream.create');
Route::post('/DREAM', [DreamPlanningController::class, 'store'])->name('dream.store');
Route::get('/DREAM/{id}/edit', [DreamPlanningController::class, 'edit'])->name('dream.edit');
Route::put('/DREAM/{id}', [DreamPlanningController::class, 'update'])->name('dream.update');
Route::delete('/DREAM/{id}', [DreamPlanningController::class, 'destroy'])->name('dream.destroy');
    Route::put('/DREAM/{id}/add-funds', [DreamPlanningController::class, 'addFunds'])->name('dream.add_funds');
    Route::post('/DREAM/{id}/withdraw', [DreamPlanningController::class, 'withdrawFunds'])->name('dream.withdraw');
});
