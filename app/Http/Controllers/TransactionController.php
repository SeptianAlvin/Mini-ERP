<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    private function getCurrentBalance()
    {
        $pemasukan = Transaction::whereHas('category', function($q) {
            $q->where('type', 'income');
        })->sum('amount');
        
        $pengeluaran = Transaction::whereHas('category', function($q) {
            $q->where('type', 'expense');
        })->sum('amount');
        
        return $pemasukan - $pengeluaran;
    }

    public function index(Request $request)
    {
        $query = Transaction::with('category');

        if ($request->filled('search')) {
            $query->where('desc', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('month')) {
            $query->whereMonth('trans_date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('trans_date', $request->year);
        }

        if ($request->filled('type')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        $transactions = $query->orderBy('trans_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $allDates = Transaction::pluck('trans_date');
        $availableYears = $allDates->map(function($date) {
            return date('Y', strtotime($date));
        })->unique()->sortDesc()->values()->toArray();

        if (empty($availableYears)) {
            $availableYears = [date('Y')];
        }

        return view('transaction', compact('transactions', 'availableYears'));
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
            'amount' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'receipt_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $category = \App\Models\Category::find($request->category_id);
        
        if ($category->type === 'expense') {
            $balance = $this->getCurrentBalance();
            if ($request->amount > $balance) {
                return redirect()->back()->withInput()->withErrors(['amount' => 'Saldo tidak mencukupi untuk pengeluaran ini. Saldo saat ini: Rp ' . number_format($balance, 0, ',', '.')]);
            }
        }

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

        if ($transaction->dream_planning_id) {
            return redirect()->route('transaction')->with('error', 'Transaksi otomatis dari Dream Planner tidak dapat diedit secara manual. Harap gunakan menu Dream Planner atau Tong Sampah jika ada kesalahan.');
        }

        $categories = \App\Models\Category::all();
        return view('transaction_form', compact('transaction', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'trans_date' => 'required|date',
            'desc' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'receipt_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $transaction = Transaction::findOrFail($id);

        $category = \App\Models\Category::find($request->category_id);
        $old_category = $transaction->category;

        $balance = $this->getCurrentBalance();
        
        // Kembalikan efek transaksi lama ke saldo simulasi
        if ($old_category->type === 'income') {
            $simulated_balance = $balance - $transaction->amount;
        } else {
            $simulated_balance = $balance + $transaction->amount;
        }

        // Terapkan efek transaksi baru
        if ($category->type === 'expense') {
            $simulated_balance -= $request->amount;
        } else {
            $simulated_balance += $request->amount;
        }

        if ($simulated_balance < 0) {
            return redirect()->back()->withInput()->withErrors(['amount' => 'Perubahan ini akan menyebabkan Saldo Utama menjadi minus (tidak cukup). Saldo simulasi: Rp ' . number_format($simulated_balance, 0, ',', '.')]);
        }
        
        // Cek jika ini adalah transaksi top up dream planner
        if ($transaction->dream_planning_id) {
            $dream = \App\Models\DreamPlanning::find($transaction->dream_planning_id);
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

        if ($transaction->category && $transaction->category->type === 'income') {
            $balance = $this->getCurrentBalance();
            if ($balance - $transaction->amount < 0) {
                return redirect()->back()->withErrors(['error' => 'Pemasukan ini tidak bisa dihapus karena akan menyebabkan Saldo Utama menjadi minus (tidak cukup). Hapus pengeluaran terkait terlebih dahulu.']);
            }
        }

        // Cek jika ini adalah transaksi top up dream planner
        if ($transaction->dream_planning_id) {
            $dream = \App\Models\DreamPlanning::find($transaction->dream_planning_id);
            if ($dream) {
                if ($transaction->category && $transaction->category->type === 'expense') {
                    // Transaksi Top-Up
                    if ($dream->terkumpul < $transaction->amount) {
                        return redirect()->back()->withErrors(['error' => 'Transaksi Top-Up ini tidak bisa dihapus karena dana di tabungan sudah dicairkan. Hapus transaksi pencairan terlebih dahulu!']);
                    }
                    $dream->terkumpul -= $transaction->amount;
                } else if ($transaction->category && $transaction->category->type === 'income') {
                    // Transaksi Pencairan
                    $dream->terkumpul += $transaction->amount;
                    $dream->status = 'active';
                }
                
                if ($dream->terkumpul < 0) $dream->terkumpul = 0;
                $dream->save();
            }
        }
        
        $transaction->delete();

        return redirect()->route('transaction')->with('success', 'Transaksi dipindahkan ke Tong Sampah.');
    }

    public function trash()
    {
        $transactions = Transaction::onlyTrashed()->with('category')->orderBy('deleted_at', 'desc')->get();
        return view('transaction_trash', compact('transactions'));
    }

    public function restore($id)
    {
        $transaction = Transaction::onlyTrashed()->findOrFail($id);

        if ($transaction->category && $transaction->category->type === 'expense') {
            $balance = $this->getCurrentBalance();
            if ($balance - $transaction->amount < 0) {
                return redirect()->back()->withErrors(['error' => 'Pengeluaran ini tidak bisa dipulihkan karena Saldo Utama Anda saat ini tidak mencukupi (akan menjadi minus).']);
            }
        }
        
        if ($transaction->dream_planning_id) {
            $dream = \App\Models\DreamPlanning::find($transaction->dream_planning_id);
            if ($dream) {
                if ($transaction->category && $transaction->category->type === 'expense') {
                    // Transaksi Top-Up dipulihkan
                    $dream->terkumpul += $transaction->amount;
                    $dream->status = 'active';
                } else if ($transaction->category && $transaction->category->type === 'income') {
                    // Transaksi Pencairan dipulihkan
                    $dream->terkumpul -= $transaction->amount;
                    if ($dream->terkumpul <= 0) {
                        $dream->terkumpul = 0;
                        $dream->status = 'completed';
                    }
                }
                $dream->save();
            }
        }
        
        $transaction->restore();

        return redirect()->route('transaction.trash')->with('success', 'Transaksi berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        $transaction = Transaction::onlyTrashed()->findOrFail($id);
        
        if ($transaction->receipt_path && Storage::exists($transaction->receipt_path)) {
            Storage::delete($transaction->receipt_path);
        }
        
        $transaction->forceDelete();

        return redirect()->route('transaction.trash')->with('success', 'Transaksi dihapus secara permanen.');
    }

    public function showReceipt($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        if ($transaction->receipt_path && Storage::exists($transaction->receipt_path)) {
            return Storage::response($transaction->receipt_path);
        }

        abort(404, 'Bukti transaksi tidak ditemukan.');
    }

    public function printPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $start = $request->start_date;
        $end = $request->end_date;

        $transactions = Transaction::with('category')
            ->whereBetween('trans_date', [$start, $end])
            ->orderBy('trans_date', 'asc')
            ->get();

        $pdf = Pdf::loadView('transaction_pdf', compact('transactions', 'start', 'end'));
        
        return $pdf->download('laporan_transaksi_' . $start . '_sampai_' . $end . '.pdf');
    }

    public function emptyTrash()
    {
        $transactions = Transaction::onlyTrashed()->get();
        
        foreach ($transactions as $transaction) {
            if ($transaction->receipt_path && Storage::exists($transaction->receipt_path)) {
                \Illuminate\Support\Facades\Storage::delete($transaction->receipt_path);
            }
            $transaction->forceDelete();
        }

        return redirect()->route('transaction.trash')->with('success', 'Seluruh isi Tong Sampah berhasil dikosongkan secara permanen.');
    }
}
