@extends('layouts.app')
@section('title', 'Dream Planning - Money Track')
@section('page-title', 'Daftar Dream Planning')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <h2 class="text-xl font-semibold">Semua Tabungan Tujuan</h2>
    <a href="{{ route('dream.create') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white py-2 px-4 rounded-lg shadow font-semibold">
        + Tambah Tabungan Tujuan
    </a>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

@if ($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
    <strong class="font-bold">Oops! Ada kesalahan:</strong>
    <ul class="list-disc pl-5 mt-1">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="bg-white p-6 rounded-lg shadow">
    @if (count($dreams) > 0)
        @foreach ($dreams as $dream)
            <div class="border-t py-4 flex flex-col lg:flex-row lg:justify-between lg:items-center gap-6">
                @php
                    $terkumpul = $dream->terkumpul ?? 0;
                    $target = $dream->total_tabungan;
                    $persen = $target > 0 ? min(100, round(($terkumpul / $target) * 100)) : 0;
                    $barColor = $persen >= 100 ? 'bg-green-500' : 'bg-indigo-500';
                @endphp
                <div class="flex-1 w-full">
                    <div class="flex justify-between mb-1">
                        <p class="font-semibold text-lg">{{ $dream->tujuan_tabungan }}</p>
                        <p class="text-sm font-bold text-gray-600">{{ $persen }}%</p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                        <div class="{{ $barColor }} h-3 rounded-full transition-all duration-500" style="width: {{ $persen }}%"></div>
                    </div>
                    <p class="text-sm text-gray-600">
                        Terkumpul: <span class="font-bold text-green-600">Rp {{ number_format($terkumpul, 0, ',', '.') }}</span> / Target: Rp {{ number_format($target, 0, ',', '.') }}
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-4 w-full lg:w-auto">
                    @if ($dream->status === 'completed')
                        <div class="px-4 py-2 bg-gray-100 text-gray-700 font-bold rounded-md border border-gray-300 flex items-center shadow-sm">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Selesai & Dicairkan
                        </div>
                    @else
                        <form action="{{ route('dream.add_funds', $dream->id) }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            @method('PUT')
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500 text-sm">Rp</span>
                                <input type="text" id="visible-{{ $dream->id }}" placeholder="Nominal" required class="border-gray-300 rounded-md shadow-sm text-sm p-2 pl-8 w-32 xl:w-36 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" oninput="formatRupiah(this, '{{ $dream->id }}')">
                                <input type="hidden" name="tambahan_dana" id="hidden-{{ $dream->id }}" required min="1">
                            </div>
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-md text-sm font-bold shadow transition duration-150 ease-in-out">+ Top Up</button>
                        </form>

                        @if ($persen >= 100)
                            <form action="{{ route('dream.withdraw', $dream->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-yellow-900 px-3 py-2 rounded-md text-sm font-bold shadow transition duration-150 ease-in-out flex items-center" onclick="return confirm('Tabungan ini akan ditutup dan saldonya akan dikembalikan ke Saldo Utama Anda. Lanjutkan?');">
                                    🎉 Cairkan
                                </button>
                            </form>
                        @endif
                    @endif
                    
                    <div class="flex space-x-3 sm:border-l sm:pl-4">
                        <a href="{{ route('dream.edit', $dream->id) }}" class="text-indigo-500 hover:text-indigo-700 text-sm font-bold self-center">Edit</a>
                        <form action="{{ route('dream.destroy', $dream->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus perencanaan ini?');" class="self-center">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-bold">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <p class="text-gray-500">Belum ada target tabungan.</p>
    @endif
</div>

<script>
    function formatRupiah(element, id) {
        // Hapus semua karakter selain angka
        let angkaMurni = element.value.replace(/\D/g, '');
        
        // Format dengan pemisah titik ribuan, jutaan, dll
        let rupiahFormat = angkaMurni.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        
        // Tampilkan ke user
        element.value = rupiahFormat;
        
        // Masukkan angka murni ke hidden input untuk dikirim ke server
        document.getElementById('hidden-' + id).value = angkaMurni;
    }
</script>
@endsection
