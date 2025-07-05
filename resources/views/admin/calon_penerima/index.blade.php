@extends('admin.layouts.app')

@section('title', 'Data Calon Penerima')

@section('content')
<!-- Tabel Data Calon Penerima -->
<div class="bg-white p-6 mb-4">
    <h2 class="text-2xl font-semibold mb-2">Data Calon Penerima</h2>
    <hr class="border-t-2 border-gray-300 mb-4 w-full">

    <!-- Filter & Search -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <!-- Show Entries -->
        <div class="flex items-center">
            <label for="entries" class="text-sm font-medium mr-2">Show</label>
            <input type="number" id="entries" class="p-2 border rounded w-20" placeholder="input" min="1" value="5"/>
            <span class="ml-2 text-sm">entries</span>
        </div>

        <!-- Filter Beasiswa & Search -->
        <div class="flex items-center space-x-2 relative">
            <div class="relative">
                <button id="filterButton" class="flex items-center bg-gray-500 text-white py-2 px-4 rounded-lg shadow-md hover:bg-gray-300 transition duration-300">
                    <i class="fas fa-filter mr-2"></i> Filters
                </button>
                <div id="filterDropdown" class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg hidden z-10">
                    <select id="beasiswaFilter" class="w-full p-2 border border-gray-300 rounded-lg">
                        <option value="">Semua Beasiswa</option>
                        <option value="kip-k">KIP-K</option>
                        <option value="tahfidz">Tahfidz</option>
                    </select>
                </div>
            </div>
            <input type="text" id="search" placeholder="Cari..." class="p-2 border rounded" />
        </div>
    </div>

    <!-- Tabel -->
    <table class="min-w-full mt-6 table-auto" id="tabelCalon">
        <thead>
            <tr class="bg-blue-800 text-white">
                <th class="border px-4 py-2 text-left font-normal">No</th>
                <th class="border px-4 py-2 text-left font-normal">No Pendaftaran</th>
                <th class="border px-4 py-2 text-left font-normal">Nama Calon Penerima</th>
                <th class="border px-4 py-2 text-left font-normal">NISN</th>
                <th class="border px-4 py-2 text-left font-normal">Beasiswa</th>
            </tr>
        </thead>
        <tbody>
            @foreach($calonPenerimas as $item)
                <tr class="bg-white" data-beasiswa="{{ strtolower($item->jenisBeasiswa->nama ?? '-') }}">
                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-4 py-2">{{ $item->no_pendaftaran }}</td>
                    <td class="border px-4 py-2">{{ $item->nama_calon_penerima }}</td>
                    <td class="border px-4 py-2">{{ $item->NISN }}</td>
                    <td class="border px-4 py-2">{{ $item->jenisBeasiswa->nama ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const entriesInput = document.getElementById("entries");
        const searchInput = document.getElementById("search");
        const beasiswaFilter = document.getElementById("beasiswaFilter");
        const table = document.getElementById("tabelCalon");
        const rows = Array.from(table.querySelectorAll("tbody tr"));

        function updateTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const maxEntries = parseInt(entriesInput.value) || rows.length;
            const selectedBeasiswa = beasiswaFilter.value.toLowerCase();
            let visibleCount = 0;

            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                const rowBeasiswa = row.dataset.beasiswa;
                const matchesSearch = rowText.includes(searchTerm);
                const matchesBeasiswa = !selectedBeasiswa || rowBeasiswa === selectedBeasiswa;

                if (matchesSearch && matchesBeasiswa && visibleCount < maxEntries) {
                    row.style.display = "";
                    visibleCount++;
                } else {
                    row.style.display = "none";
                }
            });
        }

        entriesInput.addEventListener("input", updateTable);
        searchInput.addEventListener("input", updateTable);
        beasiswaFilter.addEventListener("change", updateTable);

        // Dropdown toggle
        const filterButton = document.getElementById("filterButton");
        const filterDropdown = document.getElementById("filterDropdown");

        filterButton.addEventListener("click", function (e) {
            e.stopPropagation();
            filterDropdown.classList.toggle("hidden");
        });

        window.addEventListener("click", function (e) {
            if (!filterButton.contains(e.target) && !filterDropdown.contains(e.target)) {
                filterDropdown.classList.add("hidden");
            }
        });

        updateTable(); // initial load
    });
</script>
@endpush

@endsection
