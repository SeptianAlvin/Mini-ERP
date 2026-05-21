@extends('layouts.app')
@section('title', isset($dream) ? 'Edit Dream Planning - Money Track' : 'Tambah Dream Planning - Money Track')
@section('page-title', isset($dream) ? 'Edit Dream Planning' : 'Tambah Dream Planning')

@section('content')
<div class="bg-white p-6 rounded-lg shadow max-w-lg">
    <form action="{{ isset($dream) ? route('dream.update', $dream->id) : route('dream.store') }}" method="POST">
        @csrf
        @if(isset($dream))
            @method('PUT')
        @endif

        <div class="mb-4">
            <label for="tujuan_tabungan" class="block text-gray-700 font-bold mb-2">Tujuan Tabungan</label>
            <input type="text" name="tujuan_tabungan" id="tujuan_tabungan" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('tujuan_tabungan', $dream->tujuan_tabungan ?? '') }}" required placeholder="Contoh: Beli Laptop">
            @error('tujuan_tabungan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="visible_total_tabungan" class="block text-gray-700 font-bold mb-2">Total Tabungan / Target</label>
            <div class="relative">
                <span class="absolute left-3 top-2.5 text-gray-500 font-semibold">Rp</span>
                <input type="text" id="visible_total_tabungan" class="w-full pl-10 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('total_tabungan', $dream->total_tabungan ?? '') ? number_format(old('total_tabungan', $dream->total_tabungan ?? ''), 0, ',', '.') : '' }}" required oninput="formatRupiah(this, 'total_tabungan')" placeholder="Contoh: 15.000.000">
                <input type="hidden" name="total_tabungan" id="total_tabungan" value="{{ old('total_tabungan', $dream->total_tabungan ?? '') }}" required min="0">
            </div>
            @error('total_tabungan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('dream') }}" class="text-gray-500 hover:text-gray-700">Batal</a>
            <button type="submit" class="bg-indigo-500 text-white font-bold py-2 px-4 rounded hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                {{ isset($dream) ? 'Simpan Perubahan' : 'Tambah Impian' }}
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
