@extends('superadmin.layouts.app')

@section('title', 'Hasil Seleksi')

@section('content')
<div class="container mx-auto px-4 py-6 h-screen">
    <!-- Hasil Seleksi -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-2xl font-bold mb-4">Hasil Seleksi</h2>

        <!-- Tabel Hasil Seleksi dengan Scroll -->
        <div class="overflow-x-auto">
            <div class="flex flex-col max-h-[400px] overflow-y-auto"> <!-- Memastikan kontainer fleksibel dan bisa discroll -->
                <table class="min-w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-4 text-left">No</th>
                            <th class="px-6 py-4 text-left">Nama Calon Penerima</th>
                            <th class="px-6 py-4 text-left">Kriteria 1</th>
                            <th class="px-6 py-4 text-left">Kriteria 2</th>
                            <th class="px-6 py-4 text-left">Kriteria 3</th>
                            <th class="px-6 py-4 text-left">Kriteria 4</th>
                            <th class="px-6 py-4 text-left">Hasil</th>
                            <th class="px-6 py-4 text-left">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hasilSeleksi as $data)
                            <tr class="border-b">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">{{ $data->nama_calon_penerima }}</td>
                                <td class="px-6 py-4">{{ $data->nilai_kriteria1 }}</td>
                                <td class="px-6 py-4">{{ $data->nilai_kriteria2 }}</td>
                                <td class="px-6 py-4">{{ $data->nilai_kriteria3 }}</td>
                                <td class="px-6 py-4">{{ $data->nilai_kriteria4 }}</td>
                                <td class="px-6 py-4">{{ $data->hasil }}</td>
                                <td class="px-6 py-4">{{ $data->keterangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
