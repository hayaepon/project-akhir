@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

<!-- Sambutan -->
<div class="bg-white p-6 mb-8">
    <div class="flex justify-center mb-4">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-16">
    </div>
    <h2 class="text-center text-2xl font-bold mb-2">Selamat Datang Admin1</h2>
    <p class="text-center text-gray-600">
        Selamat datang di Sistem Pendukung Keputusan Penerimaan Beasiswa STMIK Antar Bangsa.
        Kelola data pendaftar, atur bobot kriteria, dan lakukan seleksi beasiswa dengan cepat dan akurat menggunakan metode SMART.
    </p>
</div>

<!-- Ringkasan Data -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-md text-center">
        <div class="text-blue-700 mb-2">
            <i class="fas fa-users fa-2x"></i>
        </div>
        <h3 class="text-2xl font-bold">130</h3>
        <p class="text-gray-600">Jumlah Pendaftar</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md text-center">
        <div class="text-blue-700 mb-2">
            <i class="fas fa-user-graduate fa-2x"></i>
        </div>
        <h3 class="text-2xl font-bold">70</h3>
        <p class="text-gray-600">Pendaftar KIP-K</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md text-center">
        <div class="text-blue-700 mb-2">
            <i class="fas fa-book-open fa-2x"></i>
        </div>
        <h3 class="text-2xl font-bold">60</h3>
        <p class="text-gray-600">Pendaftar Tahfiz</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md text-center">
        <div class="text-blue-700 mb-2">
            <i class="fas fa-check-circle fa-2x"></i>
        </div>
        <h3 class="text-2xl font-bold">50</h3>
        <p class="text-gray-600">Lolos Seleksi</p>
    </div>
</div>

<!-- Grafik Pendaftaran -->
<div class="bg-white p-6 rounded-lg shadow-md mb-8">
    <h3 class="text-xl font-bold mb-4">Grafik Pendaftaran</h3>
    <div class="h-64 bg-gradient-to-r from-blue-400 to-green-400 rounded-lg"></div>
</div>

<!-- Data Tambahan -->
<div class="bg-white p-6 rounded-lg shadow-md mb-8">
    <h3 class="text-xl font-bold mb-4">Data Tambahan</h3>
    <p class="text-gray-700 mb-6">
    <div class="h-96 bg-gray-200 rounded-lg"></div>
</div>

<!-- Grafik Lolos Seleksi -->
<div class="bg-white p-6 rounded-lg shadow-md mb-8">
    <h3 class="text-xl font-bold mb-4">Grafik Lolos Seleksi</h3>
    <div class="h-64 bg-gradient-to-r from-pink-400 to-yellow-400 rounded-lg"></div>
</div>

@endsection
