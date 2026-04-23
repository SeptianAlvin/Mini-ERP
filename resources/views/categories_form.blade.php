@extends('layouts.app')
@section('title', isset($category) ? 'Edit Kategori - Money Track' : 'Tambah Kategori - Money Track')
@section('page-title', isset($category) ? 'Edit Kategori' : 'Tambah Kategori')

@section('content')
<div class="bg-white p-6 rounded-lg shadow max-w-lg">
    <form action="{{ isset($category) ? route('categories.update', $category->id) : route('categories.store') }}" method="POST">
        @csrf
        @if(isset($category))
            @method('PUT')
        @endif

        <div class="mb-4">
            <label for="cat_name" class="block text-gray-700 font-bold mb-2">Nama Kategori</label>
            <input type="text" name="cat_name" id="cat_name" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('cat_name', $category->cat_name ?? '') }}" required>
            @error('cat_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="type" class="block text-gray-700 font-bold mb-2">Tipe</label>
            <select name="type" id="type" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                <option value="income" {{ old('type', $category->type ?? '') == 'income' ? 'selected' : '' }}>Pemasukan (Income)</option>
                <option value="expense" {{ old('type', $category->type ?? '') == 'expense' ? 'selected' : '' }}>Pengeluaran (Expense)</option>
            </select>
            @error('type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('categories') }}" class="text-gray-500 hover:text-gray-700">Batal</a>
            <button type="submit" class="bg-indigo-500 text-white font-bold py-2 px-4 rounded hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                {{ isset($category) ? 'Simpan Perubahan' : 'Tambah Kategori' }}
            </button>
        </div>
    </form>
</div>
@endsection
