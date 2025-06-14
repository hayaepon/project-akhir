@extends('admin.layouts.app')

@section('title', 'Hasil Seleksi')

@section('content')
    <div class="container mx-auto px-4 py-6 h-screen">
        <div class="bg-white p-6 rounded-lg mb-2">
            <div class="flex justify-between items-center mb-4 space-x-4">
                <!-- Tombol Export -->
                <div class="relative">
                    <button id="exportButton" class="flex items-center bg-red-500 text-white py-2 px-6 rounded-xl shadow-md hover:bg-red-600 transition duration-300">
                        <i class="fas fa-file-export mr-2"></i> Export
                    </button>
                    <div id="exportDropdown" class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg hidden">
                        <form action="{{ route('hasil-seleksi.export') }}" method="GET">
                            @csrf
                            <button type="submit" name="format" value="pdf" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-200">Export as PDF</button>
                            <button type="submit" name="format" value="excel" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-200">Export as Excel</button>
                        </form>
                    </div>
                </div>

                <!-- Tombol Filter -->
                <div class="relative">
                    <button id="filterButton" class="flex items-center bg-blue-800 text-white py-2 px-6 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">
                        <i class="fas fa-filter mr-2"></i> Filters
                    </button>
                    <div id="filterDropdown" class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg hidden">
                        <form action="{{ route('hasil-seleksi.index') }}" method="GET" id="filterForm">
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

                <!-- Tabel Hasil Seleksi -->
                <div class="overflow-x-auto mt-4">
                    <div class="flex flex-col max-h-[400px] overflow-y-auto">
                        <table class="min-w-full table-auto border-collapse">
                            <thead>
                                <tr class="bg-blue-800 text-white">
                                    <th class="border px-4 py-2 text-left font-normal">No</th>
                                    <th class="border px-4 py-2 text-left font-normal">Nama Calon Penerima</th>
                                    @foreach($headerKriteria as $namaKriteria)
                                        <th class="border px-4 py-2 text-left font-normal">{{ $namaKriteria }}</th>
                                    @endforeach
                                    <th class="border px-4 py-2 text-left font-normal">Hasil</th>
                                    <th class="border px-4 py-2 text-left font-normal">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hasilSeleksi as $data)
                                    @php
                                        $nilaiKriteria = json_decode($data->nilai_kriteria, true);
                                    @endphp
                                    <tr class="bg-white">
                                        <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                                        <td class="border px-4 py-2">{{ $data->calonPenerima->nama_calon_penerima ?? '-' }}</td>
                                        @foreach($headerKriteria as $id => $namaKriteria)
                                            <td class="border px-4 py-2">
                                                {{ $nilaiKriteria[$id] ?? 0 }}
                                            </td>
                                        @endforeach
                                        <td class="border px-4 py-2">{{ $data->hasil }}</td>
                                        <td class="border px-4 py-2">{{ $data->keterangan ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                </div>
                </div>

                <!-- Script Dropdown -->
                <script>
                    document.getElementById('exportButton').addEventListener('click', function () {
                        var dropdown = document.getElementById('exportDropdown');
                        dropdown.classList.toggle('hidden');
                    });

        window.addEventListener('click', function (e) {
            if (!document.getElementById('exportButton').contains(e.target) &&
                !document.getElementById('exportDropdown').contains(e.target)) {
                document.getElementById('exportDropdown').classList.add('hidden');
            }

            if (!document.getElementById('filterButton').contains(e.target) &&
                !document.getElementById('filterDropdown').contains(e.target)) {
                document.getElementById('filterDropdown').classList.add('hidden');
            }
        });

        document.getElementById('filterButton').addEventListener('click', function () {
            var dropdown = document.getElementById('filterDropdown');
            dropdown.classList.toggle('hidden');
        });
    </script>
@endsection