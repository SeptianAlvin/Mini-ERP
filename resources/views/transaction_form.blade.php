@extends('layouts.app')
@section('title', isset($transaction) ? 'Edit Transaksi - Money Track' : 'Tambah Transaksi - Money Track')
@section('page-title', isset($transaction) ? 'Edit Transaksi' : 'Tambah Transaksi')

@section('content')
<div class="bg-white p-6 rounded-lg shadow max-w-lg">
    <form action="{{ isset($transaction) ? route('transaction.update', $transaction->id) : route('transaction.store') }}" method="POST">
        @csrf
        @if(isset($transaction))
            @method('PUT')
        @endif

        <div class="mb-4">
            <label for="trans_date" class="block text-gray-700 font-bold mb-2">Tanggal Transaksi</label>
            <input type="date" name="trans_date" id="trans_date" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('trans_date', isset($transaction) ? \Carbon\Carbon::parse($transaction->trans_date)->format('Y-m-d') : '') }}" required>
            @error('trans_date')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="category_id" class="block text-gray-700 font-bold mb-2">Kategori</label>
            <select name="category_id" id="category_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $transaction->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->cat_name }} ({{ $category->type }})
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="desc" class="block text-gray-700 font-bold mb-2">Deskripsi</label>
            <input type="text" name="desc" id="desc" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('desc', $transaction->desc ?? '') }}" required>
            @error('desc')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="amount" class="block text-gray-700 font-bold mb-2">Nominal (Rp)</label>
            <input type="number" name="amount" id="amount" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('amount', $transaction->amount ?? '') }}" required min="0">
            @error('amount')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('transaction') }}" class="text-gray-500 hover:text-gray-700">Batal</a>
            <button type="submit" class="bg-indigo-500 text-white font-bold py-2 px-4 rounded hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                {{ isset($transaction) ? 'Simpan Perubahan' : 'Tambah Transaksi' }}
            </button>
        </div>
    </form>
</div>
@endsection
