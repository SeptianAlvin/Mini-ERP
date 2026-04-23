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
}
