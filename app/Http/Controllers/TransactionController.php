<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index ()
    {
        $trans =[
            'transactions'=> 
            [
                    ['Tanggal' => '01 Mar 2026', 'deskripsi' => 'Gaji Bulanan', 'nominal' => 500000, 'Kategori' => 'Gaji', 'Tipe' => 'income'],
                    ['Tanggal' => '02 Mar 2026', 'deskripsi' => 'Donasi', 'nominal' => 11000, 'Kategori' => 'Sumbangan', 'Tipe' => 'Expense'],
                    ['Tanggal' => '03 Mar 2026', 'deskripsi' => 'Streaming', 'nominal' => 550000, 'Kategori' => 'Streaming', 'Tipe' => 'income']
        
            ]
            ];
        return view('transaction', $trans);
    }
}
