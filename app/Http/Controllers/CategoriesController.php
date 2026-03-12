<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {
        $Cat =
        [
            'categories'=> [
                ['Kategori' => 'Gaji', 'Tipe' => 'income'],
                ['Kategori' => 'Sumbangan', 'Tipe' => 'Expense'],
                ['Kategori' => 'Streaming', 'Tipe' => 'income']    
            ]
        ];
        return view('categories', $Cat);
    }
}
