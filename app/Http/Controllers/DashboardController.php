<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ambil daftar tahun yang ada dari data transaksi
        $allDates = Transaction::pluck('trans_date');
        $availableYears = $allDates->map(function($date) {
            return date('Y', strtotime($date));
        })->unique()->sortDesc()->values()->toArray();

        if (empty($availableYears)) {
            $availableYears = [date('Y')];
        }
        if (!in_array(date('Y'), $availableYears)) {
            array_unshift($availableYears, date('Y'));
            rsort($availableYears);
        }

        $selectedYear = $request->get('year', date('Y'));

        $pemasukan = Transaction::whereYear('trans_date', $selectedYear)->whereHas('category', function($q) {
            $q->where('type', 'income');
        })->sum('amount');
        
        $pengeluaran = Transaction::whereYear('trans_date', $selectedYear)->whereHas('category', function($q) {
            $q->where('type', 'expense');
        })->sum('amount');
        
        $total_saldo = $pemasukan - $pengeluaran;
        
        $recent_transactions = Transaction::with('category')->latest()->take(5)->get();

        // Chart Data: Monthly trend for the selected year
        $transactionsThisYear = Transaction::with('category')
            ->whereYear('trans_date', $selectedYear)
            ->get();
            
        $monthlyIncome = array_fill(0, 12, 0);
        $monthlyExpense = array_fill(0, 12, 0);
        
        $categoryExpenses = [];
        foreach ($transactionsThisYear as $trx) {
            $monthIndex = (int)date('m', strtotime($trx->trans_date)) - 1;
            if ($trx->category && $trx->category->type === 'income') {
                $monthlyIncome[$monthIndex] += $trx->amount;
            } elseif ($trx->category && $trx->category->type === 'expense') {
                $monthlyExpense[$monthIndex] += $trx->amount;
                
                // Group expenses by category
                $catName = $trx->category->cat_name;
                if (!isset($categoryExpenses[$catName])) {
                    $categoryExpenses[$catName] = 0;
                }
                $categoryExpenses[$catName] += $trx->amount;
            }
        }

        $expenseLabels = array_keys($categoryExpenses);
        $expenseData = array_values($categoryExpenses);

        $Data = [
            'total_saldo' => $total_saldo,
            'Pemasukan' => $pemasukan,
            'Pengeluaran' => $pengeluaran,
            'recent_transactions' => $recent_transactions,
            'monthlyIncome' => json_encode($monthlyIncome),
            'monthlyExpense' => json_encode($monthlyExpense),
            'expenseLabels' => json_encode($expenseLabels),
            'expenseData' => json_encode($expenseData),
            'availableYears' => $availableYears,
            'selectedYear' => $selectedYear,
        ];
        return view('dashboard', $Data);
    }
}
