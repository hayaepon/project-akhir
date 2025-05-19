@extends('admin.layouts.app')

@section('title', 'Kriteria & Bobot')

@section('content')
<!-- Data Kriteria & Bobot -->
<div class="bg-white p-6">
    <h3 class="text-xl font-semibold mb-2">Data Kriteria & Bobot</h3>
    <hr class="border-t-2 border-gray-300 mb-4 w-full">
    <table id="tabelKriteria" class="min-w-full table-auto">
        <thead>
            <tr class="bg-blue-800 text-white font-medium">
                <th class="border px-4 py-2 text-left font-normal">Beasiswa</th>
                <th class="border px-4 py-2 text-left font-normal">Nama Kriteria</th>
                <th class="border px-4 py-2 text-left font-normal">Bobot</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kriterias as $kriteria)
                <tr class="bg-white">
                    <td class="border px-4 py-2">{{ $kriteria->beasiswa }}</td>
                    <td class="border px-4 py-2">{{ $kriteria->kriteria }}</td>
                    <td class="border px-4 py-2">{{ $kriteria->bobot }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        $('#tabelKriteria').DataTable();
    });
</script>
@endpush

@endsection
