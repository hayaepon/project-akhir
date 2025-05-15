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
            <table class="min-w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-blue-800 text-white">
                        <th class="border px-4 py-2 text-left font-normal">No</th>
                        <th class="border px-4 py-2 text-left font-normal">Nama Calon Penerima</th>
                        <th class="border px-4 py-2 text-left font-normal">Beasiswa</th>
                        <th class="border px-4 py-2 text-left font-normal">Kriteria 1</th>
                        <th class="border px-4 py-2 text-left font-normal">Kriteria 2</th>
                        <th class="border px-4 py-2 text-left font-normal">Kriteria 3</th>
                        <th class="border px-4 py-2 text-left font-normal">Kriteria 4</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dataCalonPenerima as $data)
                        <tr class="border-b">
                            <td class="border px-4 py-2 text-left font-normal text-center">{{ $loop->iteration }}</td>
                            <td class="border px-4 py-2 text-left font-normal">{{ $data->nama_calon_penerima }}</td>
                            <td class="border px-4 py-2 text-left font-normal">{{ $data->pilihan_beasiswa }}</td>
                            <td class="border px-4 py-2 text-left font-normal">{{ $data->nilai_kriteria1 }}</td>
                            <td class="border px-4 py-2 text-left font-normal">{{ $data->nilai_kriteria2 }}</td>
                            <td class="border px-4 py-2 text-left font-normal">{{ $data->nilai_kriteria3 }}</td>
                            <td class="border px-4 py-2 text-left font-normal">{{ $data->nilai_kriteria4 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
