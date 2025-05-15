@extends('superadmin.layouts.app')

@section('title', 'Perhitungan SMART')

@section('content')
<div class="container mx-auto px-4 py-6 h-screen">
    <!-- Form untuk Tombol Hitung -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8 h-full">
        <h2 class="text-2xl font-bold mb-4">Tabel Perhitungan SMART</h2>

        <!-- Tombol Hitung -->
        <form action="{{ route('perhitungan-smart.hitung') }}" method="POST" class="mb-6">
            @csrf
            <div class="flex justify-end mb-4">
                <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded-lg shadow-md">Hitung</button>
            </div>
        </form>

        <!-- Tabel Data Calon Penerima dengan Scroll Horizontal -->
        <div class="overflow-x-auto h-96">
            <table class="min-w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-6 py-4 text-left">No</th>
                        <th class="px-6 py-4 text-left">Nama Calon Penerima</th>
                        <th class="px-6 py-4 text-left">Beasiswa</th>
                        <th class="px-6 py-4 text-left">Kriteria 1</th>
                        <th class="px-6 py-4 text-left">Kriteria 2</th>
                        <th class="px-6 py-4 text-left">Kriteria 3</th>
                        <th class="px-6 py-4 text-left">Kriteria 4</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dataCalonPenerima as $data)
                        <tr class="border-b">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">{{ $data->nama_calon_penerima }}</td>
                            <td class="px-6 py-4">{{ $data->pilihan_beasiswa }}</td>
                            <td class="px-6 py-4">{{ $data->nilai_kriteria1 }}</td>
                            <td class="px-6 py-4">{{ $data->nilai_kriteria2 }}</td>
                            <td class="px-6 py-4">{{ $data->nilai_kriteria3 }}</td>
                            <td class="px-6 py-4">{{ $data->nilai_kriteria4 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
