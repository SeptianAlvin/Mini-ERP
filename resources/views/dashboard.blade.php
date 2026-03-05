@extends('layouts.app')

@section('title', 'Dashboard - Money Track')
@section('page-title', 'Ringkasan Keuangan')

@section('content')
    <div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">Total Pemasukan</h2>
                <p class="text-2xl font-bold text-green-500">Rp 5.000.000</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">Total Pengeluaran</h2>
                <p class="text-2xl font-bold text-red-600">Rp 3.000.000</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">Saldo Bersih</h2>
                <p class="text-2xl font-bold text-gray-700">Rp 2.000.000</p>
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
            <div>
                <table class="w-full text-left">
                    <thead>
                        <tr>
                            <th class="py-2">Tanggal</th>
                            <th class="py-2">Deskripsi</th>
                            <th class="py-2">Kategori</th>
                            <th class="py-2">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2">2024-06-01</td>
                            <td class="py-2">Gaji Bulanan</td>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
