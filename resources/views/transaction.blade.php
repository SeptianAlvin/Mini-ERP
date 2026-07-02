@extends('layouts.app')
@section('title', 'Transaction - Money Track')
@section('page-title', 'Daftar Transaksi')

@section('content')
<div class="mb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <h2 class="text-xl font-semibold">Semua Transaksi</h2>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('transaction.trash') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg shadow font-semibold flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            Tong Sampah
        </a>
        <button onclick="document.getElementById('printModal').classList.remove('hidden')" class="bg-gray-100 border border-gray-300 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg shadow font-semibold flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Laporan
        </button>
        <a href="{{ route('transaction.create') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white py-2 px-4 rounded-lg shadow font-semibold">
            + Tambah Transaksi
        </a>
    </div>
</div>

<!-- Modal Print -->
<div id="printModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md m-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Cetak Laporan Transaksi</h3>
            <button onclick="document.getElementById('printModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <!-- Tambahkan menampilkan error validasi print -->
        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <script>
            // Tampilkan modal jika ada error validasi
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('printModal').classList.remove('hidden');
            });
        </script>
        @endif

        <form action="{{ route('transaction.print') }}" method="GET" target="_blank">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" required value="{{ old('start_date', date('Y-m-01')) }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-indigo-300">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Akhir</label>
                <input type="date" name="end_date" required value="{{ old('end_date', date('Y-m-t')) }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-indigo-300">
            </div>
            <div class="flex justify-end mt-6">
                <button type="button" onclick="document.getElementById('printModal').classList.add('hidden')" class="mr-2 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Batal</button>
                <button type="submit" onclick="setTimeout(() => document.getElementById('printModal').classList.add('hidden'), 500)" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 flex items-center shadow">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak PDF
                </button>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<div class="bg-white p-4 rounded-lg shadow mb-6">
    <form action="{{ route('transaction') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="w-full md:w-1/4">
            <label class="block text-gray-700 text-sm font-bold mb-1">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari deskripsi..." class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-indigo-300">
        </div>
        <div class="w-full md:w-1/4">
            <label class="block text-gray-700 text-sm font-bold mb-1">Bulan</label>
            <select name="month" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-indigo-300">
                <option value="">Semua Bulan</option>
                @foreach(range(1, 12) as $m)
                    <option value="{{ sprintf('%02d', $m) }}" {{ request('month') == sprintf('%02d', $m) ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-1/4">
            <label class="block text-gray-700 text-sm font-bold mb-1">Tahun</label>
            <select name="year" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-indigo-300">
                <option value="">Semua Tahun</option>
                @isset($availableYears)
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                @endisset
            </select>
        </div>
        <div class="w-full md:w-1/4">
            <label class="block text-gray-700 text-sm font-bold mb-1">Tipe</label>
            <select name="type" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-indigo-300">
                <option value="">Semua Tipe</option>
                <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
                <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
            </select>
        </div>
        <div class="w-full md:w-auto">
            <button type="submit" class="w-full md:w-auto px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 font-bold shadow">Filter</button>
        </div>
    </form>
</div>

<div class="bg-white p-6 rounded-lg shadow">
    @if ($transactions->count() > 0)
        @foreach ($transactions as $trx)
            <div class="border-t py-4 flex flex-col md:flex-row md:justify-between md:items-center">
                <div class="mb-2 md:mb-0">
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($trx->trans_date)->format('d M Y') }} - {{ optional($trx->category)->cat_name ?? '-' }}</p>
                    <p class="text-sm text-gray-600 mb-1">{{ $trx->desc }}</p>
                    @if($trx->receipt_path)
                        <a href="{{ route('transaction.receipt', $trx->id) }}" target="_blank" class="text-xs inline-flex items-center text-indigo-500 hover:text-indigo-700 bg-indigo-50 px-2 py-1 rounded">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            Lihat Bukti
                        </a>
                    @endif
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
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    @else
        <p class="text-gray-500 text-center py-4">Tidak ada transaksi ditemukan.</p>
    @endif
</div>
@endsection