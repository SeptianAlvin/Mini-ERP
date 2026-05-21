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
        $dream->delete();

        return redirect()->route('dream')->with('success', 'Dream Planning berhasil dihapus.');
    }

    public function addFunds(Request $request, $id)
    {
        $request->validate([
            'tambahan_dana' => 'required|numeric|min:1',
        ]);

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
            'category_id' => $category->id
        ]);

        return redirect()->route('dream')->with('success', 'Dana sejumlah Rp ' . number_format($request->tambahan_dana, 0, ',', '.') . ' berhasil dialokasikan ke ' . $dream->tujuan_tabungan . '.');
    }
}
