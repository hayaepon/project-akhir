@extends('admin.layouts.app')

@section('title', 'Kriteria & Bobot')

@section('content')
<!-- Data Kriteria & Bobot -->
<div class="bg-white p-6 mb-2">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-medium text-[22px]">Data Kriteria & Bobot</h3>
        <!-- Tombol Switch KIP-K & Tahfiz di kanan -->
        <div class="flex space-x-4">
            <button id="kipk-btn" class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-blue-800">KIP-K</button>
            <button id="tahfiz-btn" class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-blue-800">Tahfiz</button>
        </div>
    </div>
    <hr class="border-t-2 border-gray-300 mb-4 w-full">
    <table id="tabelKriteria" class="min-w-full table-auto">
        <thead>
            <tr class="bg-blue-800 text-white font-medium">
                <th class="border px-4 py-2 text-left font-normal">Beasiswa</th>
                <th class="border px-4 py-2 text-left font-normal">Nama Kriteria</th>
                <th class="border px-4 py-2 text-left font-normal">Bobot</th>
                <th class="border px-4 py-2 text-left font-normal">Atribut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kriterias as $kriteria)
            <tr class="bg-white">
                <td class="border px-6 py-2">{{ $kriteria->jenisBeasiswa->nama ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $kriteria->kriteria }}</td>
                <td class="border px-4 py-2">{{ $kriteria->bobot }}</td>
                <td class="border px-4 py-2">{{ $kriteria->atribut }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#tabelKriteria').DataTable();
    });
</script>
@endpush

@endsection