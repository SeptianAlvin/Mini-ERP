@extends('layouts.app')
@section('title', 'Categories - Money Track')
@section('page-title', 'Daftar Kategori')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <h2 class="text-xl font-semibold">Semua Kategori</h2>
    <a href="{{ route('categories.create') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white py-2 px-4 rounded-lg shadow font-semibold">
        + Tambah Kategori
    </a>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
    {{ session('error') }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @foreach ($categories as $category)
        <div class="bg-white p-4 rounded-lg shadow border-l-4 {{ $category->type === 'income' ? 'border-green-500' : 'border-red-500' }} flex justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold mb-1">{{ $category->cat_name }}</h2>
                <p class="text-gray-600 text-sm">Tipe: <span class="capitalize {{ $category->type === 'income' ? 'text-green-500' : 'text-red-500' }} font-semibold">{{ $category->type }}</span></p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('categories.edit', $category->id) }}" class="text-indigo-500 hover:text-indigo-700 text-sm font-bold">Edit</a>
                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-bold">Hapus</button>
                </form>
            </div>
        </div>
    @endforeach 
</div>
@endsection
