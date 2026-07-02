@extends('layouts.app')
@section('title', isset($transaction) ? 'Edit Transaksi - Money Track' : 'Tambah Transaksi - Money Track')
@section('page-title', isset($transaction) ? 'Edit Transaksi' : 'Tambah Transaksi')

@section('content')
<div class="bg-white p-6 rounded-lg shadow max-w-lg">
    <form action="{{ isset($transaction) ? route('transaction.update', $transaction->id) : route('transaction.store') }}" method="POST" enctype="multipart/form-data">
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
                <optgroup label="Pengeluaran">
                    @foreach($categories->where('type', 'expense') as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $transaction->category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->cat_name }}
                        </option>
                    @endforeach
                </optgroup>
                <optgroup label="Pemasukan">
                    @foreach($categories->where('type', 'income') as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $transaction->category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->cat_name }}
                        </option>
                    @endforeach
                </optgroup>
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

        <div class="mb-4">
            <label for="visible_amount" class="block text-gray-700 font-bold mb-2">Nominal</label>
            <div class="relative">
                <span class="absolute left-3 top-2.5 text-gray-500 font-semibold">Rp</span>
                <input type="text" id="visible_amount" class="w-full pl-10 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('amount', $transaction->amount ?? '') ? number_format(old('amount', $transaction->amount ?? ''), 0, ',', '.') : '' }}" required oninput="formatRupiah(this, 'amount')" placeholder="Contoh: 1.500.000">
                <input type="hidden" name="amount" id="amount" value="{{ old('amount', $transaction->amount ?? '') }}" required min="0">
            </div>
            @error('amount')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="receipt_path" class="block text-gray-700 font-bold mb-2">Bukti Transaksi (Opsional)</label>
            @if(isset($transaction) && $transaction->receipt_path)
                <div class="mb-2">
                    <p class="text-sm text-gray-600 mb-1">Bukti saat ini:</p>
                    <a href="{{ route('transaction.receipt', $transaction->id) }}" target="_blank" class="text-indigo-500 hover:text-indigo-700 underline text-sm">Lihat Bukti</a>
                </div>
            @endif
            <input type="file" name="receipt_path" id="receipt_path" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" accept="image/*,.pdf">
            <p class="text-xs text-gray-500 mt-1">Format yang diizinkan: JPG, PNG, PDF (Maks. 5MB)</p>
            @error('receipt_path')
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

<script>
    function formatRupiah(element, hiddenId) {
        let angkaMurni = element.value.replace(/\D/g, '');
        let rupiahFormat = angkaMurni.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        element.value = rupiahFormat;
        document.getElementById(hiddenId).value = angkaMurni;
    }
</script>
@endsection
