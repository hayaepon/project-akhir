@extends('admin.layouts.app')

@section('title', 'Data Calon Penerima')

@section('content')
<!-- Tabel Data Calon Penerima -->
<div class="bg-white p-6 mb-4">
    <h2 class="text-2xl font-semibold mb-2">Data Calon Penerima</h2>
    <hr class="border-t-2 border-gray-300 mb-4 w-full">
    
    <!-- Show Entries dan Search -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <label for="entries" class="text-sm font-medium mr-2">Show</label>
            <input type="number" id="entries" class="p-2 border rounded w-20" placeholder="input" min="1" value="5"/>
            <span class="ml-2 text-sm">entries</span>
        </div>
        <div>
            <input type="text" id="search" placeholder="Cari..." class="p-2 border rounded" />
        </div>
    </div>
    
    <!-- Tabel Data Calon Penerima -->
    <table class="min-w-full mt-6 table-auto" id="tabelCalon">
        <thead>
            <tr class="bg-blue-800 text-white">
                <th class="border px-4 py-2 text-left font-normal">No</th>
                <th class="border px-4 py-2 text-left font-normal">No Pendaftaran</th>
                <th class="border px-4 py-2 text-left font-normal">Nama Calon Penerima</th>
                <th class="border px-4 py-2 text-left font-normal">Asal Sekolah</th>
                <th class="border px-4 py-2 text-left font-normal">Beasiswa</th>
                <th class="border px-4 py-2 text-left font-normal">Aksi</th>
            </tr>
        </thead>
        <tbody id="table-body">
            @foreach($calonPenerimas as $item)
                <tr class="bg-white">
                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-4 py-2">{{ $item->no_pendaftaran }}</td>
                    <td class="border px-4 py-2">{{ $item->nama_calon_penerima}}</td>
                    <td class="border px-4 py-2">{{ $item->asal_sekolah }}</td>
                    <td class="border px-4 py-2">{{ $item->pilihan_beasiswa }}</td>
                    <td class="border px-4 py-2 text-center">
                        <a href="{{ route('calon-penerima.edit', $item->id) }}" class="text-blue-500 hover:underline">
                            <i class="fas fa-edit text-yellow-300"></i>
                        </a>
                        <span class="text-gray-400 mx-2">|</span>
                        <form action="{{ route('calon-penerima.destroy', $item->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        $('#tabelCalon').DataTable();
    });
</script>
@endpush

@endsection
