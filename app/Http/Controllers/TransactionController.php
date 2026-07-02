<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'receipt_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $data = $request->all();

        if ($request->hasFile('receipt_path')) {
            $file = $request->file('receipt_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('private/receipts', $filename);
            $data['receipt_path'] = $path;
        }

        Transaction::create($data);

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
            'receipt_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $transaction = Transaction::findOrFail($id);
        
        // Cek jika ini adalah transaksi top up dream planner
        if (str_starts_with($transaction->desc, 'Alokasi Tabungan: ')) {
            $tujuan_tabungan = str_replace('Alokasi Tabungan: ', '', $transaction->desc);
            $dream = \App\Models\DreamPlanning::where('tujuan_tabungan', $tujuan_tabungan)->first();
            
            if ($dream) {
                $selisih = $request->amount - $transaction->amount;
                $dream->terkumpul += $selisih;
                if ($dream->terkumpul < 0) $dream->terkumpul = 0;
                $dream->save();
            }
        }
        
        $data = $request->all();

        if ($request->hasFile('receipt_path')) {
            if ($transaction->receipt_path && Storage::exists($transaction->receipt_path)) {
                Storage::delete($transaction->receipt_path);
            }

            $file = $request->file('receipt_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('private/receipts', $filename);
            $data['receipt_path'] = $path;
        }

        $transaction->update($data);

        return redirect()->route('transaction')->with('success', 'Transaksi berhasil diubah.');
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        if ($transaction->receipt_path && Storage::exists($transaction->receipt_path)) {
            Storage::delete($transaction->receipt_path);
        }

        // Cek jika ini adalah transaksi top up dream planner
        if (str_starts_with($transaction->desc, 'Alokasi Tabungan: ')) {
            $tujuan_tabungan = str_replace('Alokasi Tabungan: ', '', $transaction->desc);
            $dream = \App\Models\DreamPlanning::where('tujuan_tabungan', $tujuan_tabungan)->first();
            
            if ($dream) {
                $dream->terkumpul -= $transaction->amount;
                if ($dream->terkumpul < 0) $dream->terkumpul = 0;
                $dream->save();
            }
        }
        
        $transaction->delete();

        return redirect()->route('transaction')->with('success', 'Transaksi berhasil dihapus.');
    }

    public function showReceipt($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        if ($transaction->receipt_path && Storage::exists($transaction->receipt_path)) {
            return Storage::response($transaction->receipt_path);
        }

        abort(404, 'Bukti transaksi tidak ditemukan.');
    }
}
