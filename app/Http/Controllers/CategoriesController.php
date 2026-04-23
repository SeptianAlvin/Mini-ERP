<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

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
            'cat_name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
        ]);

        Category::create($request->all());

        return redirect()->route('categories')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories_form', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cat_name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());

        return redirect()->route('categories')->with('success', 'Kategori berhasil diubah.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories')->with('success', 'Kategori berhasil dihapus.');
    }
}
