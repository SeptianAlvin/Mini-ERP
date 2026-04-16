@extends('layouts.app')
@section('title', 'Categories - Money Track')
@section('page-title', 'Daftar Kategori')

@foreach ($categories as $category)
    <div class="bg-white p-4 rounded-lg shadow mb-4">
        <h2 class="text-lg font-semibold mb-2">{{ $category->cat_name }}</h2>
        <p class="text-gray-600">Tipe: {{ $category->type }}</p>
    </div>
@endforeach 
