@extends('layouts.app')
@section('title', 'Transaction - Money Track')
@section('page-title', 'Daftar Transaksi')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <h2 class="text-xl font-semibold">Semua Transaksi</h2>
    <a href="{{ route('transaction.create') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white py-2 px-4 rounded-lg shadow font-semibold">
        + Tambah Transaksi
    </a>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<div class="bg-white p-6 rounded-lg shadow">
    @if (count($transactions) > 0)
        @foreach ($transactions as $trx)
            <div class="border-t py-4 flex flex-col md:flex-row md:justify-between md:items-center">
                <div class="mb-2 md:mb-0">
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($trx->trans_date)->format('d M Y') }} - {{ optional($trx->category)->cat_name ?? '-' }}</p>
                    <p class="text-sm text-gray-600">{{ $trx->desc }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <p class="{{ optional($trx->category)->type === 'income' ? 'text-green-500' : 'text-red-600' }} font-bold text-lg">
                        Rp {{ number_format($trx->amount, 0, ',', '.') }}
                    </p>
                    <div class="flex space-x-2 border-l pl-4">
                        <a href="{{ route('transaction.edit', $trx->id) }}" class="text-indigo-500 hover:text-indigo-700 text-sm font-bold">Edit</a>
                        <form action="{{ route('transaction.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-bold">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <p class="text-gray-500">Belum ada transaksi.</p>
    @endif
</div>
@endsection