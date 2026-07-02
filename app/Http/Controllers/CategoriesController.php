<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories', compact('categories'));
    }

    public function create()
    {
        return view('categories_form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cat_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where(fn ($query) => $query->where('user_id', auth()->id()))
            ],
            'type' => 'required|in:income,expense',
        ]);

        Category::create($request->all());

        return redirect()->route('categories')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $hasTransactions = \App\Models\Transaction::where('category_id', $id)->exists();
        return view('categories_form', compact('category', 'hasTransactions'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $hasTransactions = \App\Models\Transaction::where('category_id', $id)->exists();

        if ($hasTransactions) {
            $request->validate([
                'cat_name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('categories')->where(fn ($query) => $query->where('user_id', auth()->id()))->ignore($id)
                ],
            ]);
            $category->update(['cat_name' => $request->cat_name]);
        } else {
            $request->validate([
                'cat_name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('categories')->where(fn ($query) => $query->where('user_id', auth()->id()))->ignore($id)
                ],
                'type' => 'required|in:income,expense',
            ]);
            $category->update($request->all());
        }

        return redirect()->route('categories')->with('success', 'Kategori berhasil diubah.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        if (\App\Models\Transaction::where('category_id', $id)->exists()) {
            return redirect()->route('categories')->with('error', 'Kategori tidak bisa dihapus karena sedang digunakan dalam transaksi.');
        }

        $category->delete();

        return redirect()->route('categories')->with('success', 'Kategori berhasil dihapus.');
    }
}

