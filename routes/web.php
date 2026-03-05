<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/HOME', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/TRANSAKSI', function () {
    return view('transaction');
})->name('transaction');

Route::get('/KATEGORI', function () {
    return view('categories');
})->name('categories');
