<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $pemasukan = Transaction::whereHas('category', function($q) {
            $q->where('type', 'income');
        })->sum('amount');
        
        $pengeluaran = Transaction::whereHas('category', function($q) {
            $q->where('type', 'expense');
        })->sum('amount');
        
        $total_saldo = $pemasukan - $pengeluaran;
        
        $recent_transactions = Transaction::with('category')->latest()->take(5)->get();

        $Data = [
            'total_saldo' => $total_saldo,
            'Pemasukan' => $pemasukan,
            'Pengeluaran' => $pengeluaran,
            'recent_transactions' => $recent_transactions,
        ];
        return view('dashboard', $Data);
    }
}
