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

<div class="bg-white p-6 rounded-lg shadow">
    @if (count($dreams) > 0)
        @foreach ($dreams as $dream)
            <div class="border-t py-4 flex flex-col md:flex-row md:justify-between md:items-center">
                <div class="mb-2 md:mb-0">
                    <p class="font-semibold text-lg">{{ $dream->tujuan_tabungan }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <p class="text-indigo-600 font-bold text-lg">
                        Rp {{ number_format($dream->total_tabungan, 0, ',', '.') }}
                    </p>
                    <div class="flex space-x-2 border-l pl-4">
                        <a href="{{ route('dream.edit', $dream->id) }}" class="text-indigo-500 hover:text-indigo-700 text-sm font-bold">Edit</a>
                        <form action="{{ route('dream.destroy', $dream->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus perencanaan ini?');">
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
@endsection
