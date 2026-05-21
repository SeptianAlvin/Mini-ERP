@extends('layouts.app')

@section('title', 'Money Track')
@section('page-title', 'Ringkasan Keuangan')

@section('content')
    <div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">Total Pemasukan</h2>
                <p class="text-2xl font-bold text-green-500">Rp {{ number_format($Pemasukan, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">Total Pengeluaran</h2>
                <p class="text-2xl font-bold text-red-600">Rp {{ number_format($Pengeluaran, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">Saldo Bersih</h2>
                <p class="text-2xl font-bold text-gray-700">Rp {{ number_format($total_saldo, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-2">Grafik Keuangan</h2>
            <p class="text-gray-600">Grafik pemasukan dan pengeluaran bulanan</p>
            <img src="{{ asset('storage/images/grafik.webp') }}" alt="Grafik Keuangan" class="w-full h-auto">
        </div>
    </div>
    <div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-2">Transaksi Terakhir</h2>
            <p class="text-gray-600">Daftar transaksi terakhir yang telah dilakukan</p>
            @if (count($recent_transactions) > 0)
                @foreach ($recent_transactions as $trx)
                    <div class="border-t py-4 flex justify-between items-center">
                        <div>
                            <p class="font-semibold">{{ \Carbon\Carbon::parse($trx->trans_date)->format('d M Y') }} - {{ optional($trx->category)->cat_name ?? '-' }}</p>
                            <p class="text-sm text-gray-600">{{ $trx->desc }}</p>
                        </div>
                        <p class="{{ optional($trx->category)->type === 'income' ? 'text-green-500' : 'text-red-600' }} font-bold text-lg">
                            Rp {{ number_format($trx->amount, 0, ',', '.') }}
                        </p>
                    </div>
                @endforeach
            @else
                <p class="text-gray-500">Belum ada transaksi.</p>
            @endif
        </div>
    </div>
@endsection


