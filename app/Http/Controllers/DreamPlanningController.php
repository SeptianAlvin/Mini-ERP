<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DreamPlanningController extends Controller
{
    public function index()
    {
        $dreams = \App\Models\DreamPlanning::all();
        return view('dream', compact('dreams'));
    }

    public function create()
    {
        return view('dream_form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tujuan_tabungan' => 'required|string|max:255',
            'total_tabungan' => 'required|numeric|min:0',
        ]);

        \App\Models\DreamPlanning::create($request->all());

        return redirect()->route('dream')->with('success', 'Dream Planning berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $dream = \App\Models\DreamPlanning::findOrFail($id);
        return view('dream_form', compact('dream'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tujuan_tabungan' => 'required|string|max:255',
            'total_tabungan' => 'required|numeric|min:0',
        ]);

        $dream = \App\Models\DreamPlanning::findOrFail($id);
        $dream->update($request->all());

        return redirect()->route('dream')->with('success', 'Dream Planning berhasil diubah.');
    }

    public function destroy($id)
    {
        $dream = \App\Models\DreamPlanning::findOrFail($id);

        if ($dream->terkumpul > 0) {
            return redirect()->route('dream')->withErrors(['error' => 'Tabungan tidak bisa dihapus karena masih ada dana sebesar Rp ' . number_format($dream->terkumpul, 0, ',', '.') . ' di dalamnya. Harap cairkan terlebih dahulu!']);
        }

        $dream->delete();

        return redirect()->route('dream')->with('success', 'Dream Planning berhasil dihapus.');
    }

    public function addFunds(Request $request, $id)
    {
        $request->validate([
            'tambahan_dana' => 'required|numeric|min:1',
        ]);

        $pemasukan = \App\Models\Transaction::whereHas('category', function($q) {
            $q->where('type', 'income');
        })->sum('amount');
        
        $pengeluaran = \App\Models\Transaction::whereHas('category', function($q) {
            $q->where('type', 'expense');
        })->sum('amount');
        
        $total_saldo = $pemasukan - $pengeluaran;

        if ($request->tambahan_dana > $total_saldo) {
            return redirect()->back()->withErrors(['tambahan_dana' => 'Saldo Anda tidak mencukupi. Saldo saat ini: Rp ' . number_format($total_saldo, 0, ',', '.')]);
        }

        $dream = \App\Models\DreamPlanning::findOrFail($id);
        
        // 1. Update dana terkumpul
        $dream->terkumpul += $request->tambahan_dana;
        $dream->save();

        // 2. Cari atau buat kategori Tabungan Impian
        $category = \App\Models\Category::firstOrCreate(
            ['cat_name' => 'Tabungan Impian'],
            ['type' => 'expense'] // Dianggap sebagai pengeluaran dari saldo utama
        );

        // 3. Buat transaksi baru
        \App\Models\Transaction::create([
            'trans_date' => now()->toDateString(),
            'desc' => 'Alokasi Tabungan: ' . $dream->tujuan_tabungan,
            'amount' => $request->tambahan_dana,
            'category_id' => $category->id,
            'dream_planning_id' => $dream->id
        ]);

        return redirect()->route('dream')->with('success', 'Dana sejumlah Rp ' . number_format($request->tambahan_dana, 0, ',', '.') . ' berhasil dialokasikan ke ' . $dream->tujuan_tabungan . '.');
    }

    public function withdrawFunds(Request $request, $id)
    {
        $dream = \App\Models\DreamPlanning::findOrFail($id);

        if ($dream->terkumpul <= 0) {
            return redirect()->back()->withErrors(['error' => 'Tidak ada dana yang bisa dicairkan.']);
        }

        // 1. Cari atau buat kategori "Pencairan Tabungan Impian" (Pemasukan)
        $category = \App\Models\Category::firstOrCreate(
            ['cat_name' => 'Pencairan Tabungan Impian'],
            ['type' => 'income']
        );

        // 2. Buat transaksi pemasukan untuk mengembalikan dana ke saldo utama
        \App\Models\Transaction::create([
            'trans_date' => now()->toDateString(),
            'desc' => 'Pencairan Tabungan: ' . $dream->tujuan_tabungan,
            'amount' => $dream->terkumpul,
            'category_id' => $category->id,
            'dream_planning_id' => $dream->id
        ]);

        // 3. Update status menjadi completed
        $dream->status = 'completed';
        $dream->save();

        return redirect()->route('dream')->with('success', 'Tabungan ' . $dream->tujuan_tabungan . ' berhasil dicairkan sebesar Rp ' . number_format($dream->terkumpul, 0, ',', '.') . ' ke Saldo Utama.');
    }
}
