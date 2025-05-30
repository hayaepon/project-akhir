@extends('superadmin.layouts.app')

@section('title', 'Perhitungan SMART')

@section('content')
<div class="container mx-auto px-4 py-6 h-screen">
    <!-- Form untuk Tombol Hitung dan Judul Sejajar -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8 h-full">
        <div class="flex justify-between items-center mb-2">
            <!-- Judul -->
            <h2 class="text-2xl font-semibold">Tabel Perhitungan SMART</h2>

            <!-- Tombol Hitung -->
            <form action="{{ route('perhitungan-smart.hitung') }}" method="POST" class="flex">
                @csrf
                <button type="submit" class="bg-blue-800 text-white py-2 px-6 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">Hitung</button>
            </form>
        </div>
        <hr class="border-t-2 border-gray-300 mb-4 w-full">

        <!-- Tabel Data Calon Penerima dengan Scroll Horizontal -->
        <div class="overflow-x-auto h-96">
            <table class="min-w-full table-auto border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-blue-800 text-white">
                        <th class="border px-4 py-2 text-left font-normal">No</th>
                        <th class="border px-4 py-2 text-left font-normal">Nama Calon Penerima</th>
                        <th class="border px-4 py-2 text-left font-normal">Beasiswa</th>
                        @foreach ($headerKriteria as $namaKriteria)
                            <th class="border px-4 py-2 text-center font-normal">{{ $namaKriteria }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hasilPerhitungan as $i => $hasil)
                        <tr class="border-b even:bg-gray-50 hover:bg-gray-100">
                            <td class="border px-4 py-2 text-center">{{ $i + 1 }}</td>
                            <td class="border px-4 py-2">{{ $hasil->calonPenerima->nama_calon_penerima }}</td>
                            <td class="border px-4 py-2">{{ $hasil->jenisBeasiswa->nama }}</td>
                            @foreach (array_keys($headerKriteria) as $idKriteria)
                                <td class="border px-4 py-2 text-center">
                                    {{ $hasil->nilai_kriteria[$idKriteria] ?? '-' }}
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 3 + count($headerKriteria) }}" class="text-center py-4">Belum ada data perhitungan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
