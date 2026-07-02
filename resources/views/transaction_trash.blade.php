@extends('layouts.app')
@section('title', 'Tong Sampah - Transaksi')
@section('page-title', 'Tong Sampah - Transaksi')

@section('content')
<div class="mb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div class="flex flex-col">
        <h2 class="text-xl font-semibold">Transaksi yang Dihapus</h2>
        <a href="{{ route('transaction') }}" class="text-gray-500 hover:text-gray-700 underline font-semibold mt-1">
            &larr; Kembali ke Daftar Transaksi
        </a>
    </div>
    @if(count($transactions) > 0)
    <form action="{{ route('transaction.empty_trash') }}" method="POST" onsubmit="return confirm('PERINGATAN: Semua isi tong sampah akan dihapus secara permanen dan tidak bisa dikembalikan! Anda yakin?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg shadow font-bold flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            Kosongkan Tong Sampah
        </button>
    </form>
    @endif
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="w-full whitespace-nowrap">
        <thead>
            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left">Tanggal</th>
                <th class="py-3 px-6 text-left">Deskripsi</th>
                <th class="py-3 px-6 text-left">Kategori</th>
                <th class="py-3 px-6 text-right">Nominal</th>
                <th class="py-3 px-6 text-center">Waktu Dihapus</th>
                <th class="py-3 px-6 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            @if(count($transactions) > 0)
                @foreach($transactions as $trx)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-3 px-6 text-left whitespace-nowrap font-medium">
                        {{ date('d M Y', strtotime($trx->trans_date)) }}
                    </td>
                    <td class="py-3 px-6 text-left max-w-xs truncate" title="{{ $trx->desc }}">
                        {{ $trx->desc }}
                    </td>
                    <td class="py-3 px-6 text-left">
                        @if($trx->category)
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $trx->category->type == 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $trx->category->cat_name }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="py-3 px-6 text-right font-bold {{ $trx->category && $trx->category->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format($trx->amount, 0, ',', '.') }}
                    </td>
                    <td class="py-3 px-6 text-center">
                        {{ $trx->deleted_at->format('d M Y H:i') }}
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center space-x-2">
                            <form action="{{ route('transaction.restore', $trx->id) }}" method="POST" onsubmit="return confirm('Kembalikan transaksi ini ke daftar utama?');">
                                @csrf
                                <button type="submit" class="bg-indigo-500 text-white px-3 py-1 rounded text-xs font-bold hover:bg-indigo-600">Pulihkan</button>
                            </form>
                            <form action="{{ route('transaction.force_delete', $trx->id) }}" method="POST" onsubmit="return confirm('PERINGATAN: Transaksi ini akan dihapus permanen dan tidak bisa dikembalikan lagi! Lanjutkan?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-xs font-bold hover:bg-red-600">Hapus Permanen</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-500 font-semibold italic">Tong sampah kosong.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection
