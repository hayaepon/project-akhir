@extends('superadmin.layouts.app')

@section('title', 'Hasil Seleksi')

@section('content')
<div class="container mx-auto px-4 py-6 h-screen">
    <!-- Hasil Seleksi -->
    <div class="bg-white p-6 rounded-lg mb-2">
        <div class="flex justify-between items-center mb-4 space-x-4"> <!-- Posisikan tombol di kanan -->
            <!-- Tombol Export dengan Dropdown -->
            <div class="relative">
                <!-- Tombol Export PDF/Excel -->
                <button id="exportButton" class="flex items-center bg-red-500 text-white py-2 px-6 rounded-xl shadow-md hover:bg-red-600 transition duration-300">
                    <i class="fas fa-file-export mr-2"></i> Export
                </button>

                <!-- Dropdown Pilihan Format (PDF/Excel) -->
                <div id="exportDropdown" class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg hidden">
                    <form action="{{ route('hasil-seleksi.export') }}" method="GET">
                        @csrf
                        <button type="submit" name="format" value="pdf" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-200">Export as PDF</button>
                        <button type="submit" name="format" value="excel" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-200">Export as Excel</button>
                    </form>
                </div>
            </div>

            <!-- Tombol Filter dengan Ikon dan Dropdown -->
            <div class="relative">
                <button id="filterButton" class="flex items-center bg-blue-800 text-white py-2 px-6 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-filter mr-2"></i> Filters
                </button>

                <!-- Dropdown Filter -->
                <div id="filterDropdown" class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg hidden">
                    <form action="#" method="GET" id="filterForm">
                        @csrf
                        <select name="beasiswa" class="w-full p-2 border border-gray-300 rounded-lg" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Filter Beasiswa</option>
                            <option value="KIP-K" @if(request()->get('beasiswa') == 'KIP-K') selected @endif>KIP-K</option>
                            <option value="Tahfidz" @if(request()->get('beasiswa') == 'Tahfidz') selected @endif>Tahfidz</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel Hasil Seleksi dengan Scroll -->
        <div class="overflow-x-auto mt-4">
            <div class="flex flex-col max-h-[400px] overflow-y-auto">
                <table class="min-w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-blue-800 text-white">
                            <th class="border px-4 py-2 text-left font-normal">No</th>
                            <th class="border px-4 py-2 text-left font-normal">Nama Calon Penerima</th>
                            <th class="border px-4 py-2 text-left font-normal">Kriteria 1</th>
                            <th class="border px-4 py-2 text-left font-normal">Kriteria 2</th>
                            <th class="border px-4 py-2 text-left font-normal">Kriteria 3</th>
                            <th class="border px-4 py-2 text-left font-normal">Kriteria 4</th>
                            <th class="border px-4 py-2 text-left font-normal">Hasil</th>
                            <th class="border px-4 py-2 text-left font-normal">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hasilSeleksi as $data)
                            <tr class="bg-white">
                                <td class="border px-4 py-2 text-left font-normal">{{ $loop->iteration }}</td>
                                <td class="border px-4 py-2 text-left font-normal">{{ $data->nama_calon_penerima }}</td>
                                <td class="border px-4 py-2 text-left font-normal">{{ $data->nilai_kriteria1 }}</td>
                                <td class="border px-4 py-2 text-left font-normal">{{ $data->nilai_kriteria2 }}</td>
                                <td class="border px-4 py-2 text-left font-normal">{{ $data->nilai_kriteria3 }}</td>
                                <td class="border px-4 py-2 text-left font-normal">{{ $data->nilai_kriteria4 }}</td>
                                <td class="border px-4 py-2 text-left font-normal">{{ $data->hasil }}</td>
                                <td class="border px-4 py-2 text-left font-normal">{{ $data->keterangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk Menampilkan/menyembunyikan Dropdown -->
<script>
    // Menampilkan/Menyembunyikan Dropdown Export
    document.getElementById('exportButton').addEventListener('click', function() {
        var dropdown = document.getElementById('exportDropdown');
        dropdown.classList.toggle('hidden');
    });

    // Menutup dropdown jika klik di luar area filter
    window.addEventListener('click', function(e) {
        var dropdown = document.getElementById('exportDropdown');
        var button = document.getElementById('exportButton');
        
        if (!button.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // Menampilkan/Menyembunyikan Dropdown Filter
    document.getElementById('filterButton').addEventListener('click', function() {
        var filterDropdown = document.getElementById('filterDropdown');
        filterDropdown.classList.toggle('hidden');
    });

    // Menutup dropdown jika klik di luar area filter
    window.addEventListener('click', function(e) {
        var filterDropdown = document.getElementById('filterDropdown');
        var filterButton = document.getElementById('filterButton');
        
        if (!filterButton.contains(e.target) && !filterDropdown.contains(e.target)) {
            filterDropdown.classList.add('hidden');
        }
    });
</script>
@endsection