@extends('admin.layouts.app')

@section('title', 'Kriteria & Bobot')

@section('content')
<!-- Tabel Sub Kriteria -->
<div class="bg-white p-6 mb-2">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-medium text-[22px]">Data Sub Kriteria</h3>

        <!-- Tombol Switch KIP-K & Tahfiz di kanan -->
        <div class="flex space-x-4">
            <button id="kipk-btn" class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-blue-800">KIP-K</button>
            <button id="tahfiz-btn" class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-blue-800">Tahfiz</button>
        </div>
    </div>
    <hr class="border-t-2 border-gray-300 mb-4 w-full">
    <table class="min-w-full border-collapse">
        <thead>
            <tr class="bg-blue-800 text-white font-medium">
                <th class="border px-4 py-2 text-left">No</th>
                <th class="border px-4 py-2 text-left">Kriteria</th>
                <th class="border px-4 py-2 text-left">Sub Kriteria</th>
                <th class="border px-4 py-2 text-left">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subkriterias as $subKriteria)
            <tr class="bg-white">
                <td class="border px-6 py-2 font-normal">{{ $loop->iteration }}</td>
                <td class="border px-6 py-2 font-normal">{{ $subKriteria->kriteria->kriteria }}</td>
                <td class="border px-6 py-2 font-normal">{{ $subKriteria->sub_kriteria }}</td>
                <td class="border px-6 py-2 font-normal">{{ $subKriteria->nilai }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>

@endsection