<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('category')->get();
        return view('transaction', compact('transactions'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('transaction_form', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'trans_date' => 'required|date',
            'desc' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        Transaction::create($request->all());

        return redirect()->route('transaction')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        $categories = \App\Models\Category::all();
        return view('transaction_form', compact('transaction', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'trans_date' => 'required|date',
            'desc' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->update($request->all());

        return redirect()->route('transaction')->with('success', 'Transaksi berhasil diubah.');
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return redirect()->route('transaction')->with('success', 'Transaksi berhasil dihapus.');
    }
}
