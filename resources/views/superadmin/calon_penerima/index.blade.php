@extends('superadmin.layouts.app')

@section('title', 'Data Calon Penerima')

@section('content')
    <div class="container mx-auto px-4 py-6 h-screen">
        <!-- Form untuk menambah Data Calon Penerima -->
        <div class="bg-white p-6 rounded-lg mb-6">
            <h2 class="text-2xl font-medium mb-2 text-[22px]">Form Input Calon Penerima</h2>
            <hr class="border-t-2 border-gray-300 mb-4 w-full">
            <form action="{{ route('calon-penerima.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="flex flex-col">
                        <label for="no_pendaftaran" class="text-sm font-medium text-black-700 text-[16px] mb-2">No Pendaftaran</label>
                        <input type="text" id="no_pendaftaran" name="no_pendaftaran" class="w-full p-3 border rounded-lg shadow-sm" required />
                    </div>
                    <div class="flex flex-col">
                        <label for="nama_calon_penerima" class="text-sm font-medium text-black-700 text-[16px] mb-2">Nama Calon Penerima</label>
                        <input type="text" id="nama_calon_penerima" name="nama_calon_penerima" class="w-full p-3 border rounded-lg shadow-sm" required />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="flex flex-col">
                        <label for="asal_sekolah" class="text-sm font-medium text-black-700 text-[16px] mb-2">NISN</label>
                        <input type="text" id="asal_sekolah" name="asal_sekolah" class="w-full p-3 border rounded-lg shadow-sm" required />
                    </div>
                    <div class="flex flex-col">
                        <label for="jenis_beasiswa_id" class="text-sm font-medium text-black-700 text-[16px] mb-2">Pilih Beasiswa</label>
                        <select id="jenis_beasiswa_id" name="jenis_beasiswa_id" class="w-full p-3 border rounded-lg shadow-sm" required>
                            <option value="">Pilih Beasiswa</option>
                            @foreach($jenisBeasiswas as $beasiswa)
                                <option value="{{ $beasiswa->id }}">{{ $beasiswa->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>

                <div>
                    <button type="submit" class="bg-blue-800 text-white px-6 py-2 rounded-lg w-full sm:w-auto mt-4">Submit</button>
                </div>
            </form>
        </div>

        <!-- Tabel Data Calon Penerima -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-4">
            <h2 class="text-2xl font-medium mb-2">Data Calon Penerima</h2>
            <hr class="border-t-2 border-gray-300 mb-4 w-full">
            <!-- Show Entries dan Search -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center">
                    <label for="entries" class="text-sm font-medium mr-2">Show</label>
                    <input type="number" id="entries" class="p-2 border rounded w-20" placeholder="input" min="1" value="5" />
                    <span class="ml-2 text-sm">entries</span>
                </div>
                <div>
                    <input type="text" id="search" placeholder="Cari..." class="p-2 border rounded" />
                </div>
            </div>

            <!-- Tabel Data Calon Penerima -->
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-blue-800 text-white">
                        <th class="border px-4 py-2 text-left font-normal">No</th>
                        <th class="border px-4 py-2 text-left font-normal">No Pendaftaran</th>
                        <th class="border px-4 py-2 text-left font-normal">Nama Calon Penerima</th>
                        <th class="border px-4 py-2 text-left font-normal">NISN</th>
                        <th class="border px-4 py-2 text-left font-normal">Beasiswa</th>
                        <th class="border px-4 py-2 text-left font-normal">Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    @foreach($dataCalonPenerima as $data)
                        <tr class="bg-white">
                            <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="border px-4 py-2">{{ $data->no_pendaftaran }}</td>
                            <td class="border px-4 py-2">{{ $data->nama_calon_penerima }}</td>
                            <td class="border px-4 py-2">{{ $data->asal_sekolah }}</td>
                            <td class="border px-4 py-2">
                                {{ $data->jenisBeasiswa->nama ?? '-' }}
                            </td>
                            <td class="border px-4 py-2 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('calon-penerima.edit', $data->id) }}" class="text-blue-500 hover:underline">
                                        <i class="fas fa-edit text-yellow-300"></i>
                                    </a>
                                    <span class="text-gray-400 mx-2">|</span>
                                    <form action="{{ route('calon-penerima.destroy', $data->id) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

        </div>

        <script>
            // Implementasi fungsi pencarian
            const searchInput = document.getElementById("search");
            const tableBody = document.getElementById("table-body");

            searchInput.addEventListener("input", function () {
                const searchTerm = searchInput.value.toLowerCase();
                const rows = tableBody.getElementsByTagName("tr");

                Array.from(rows).forEach(row => {
                    const columns = row.getElementsByTagName("td");
                    let matchFound = false;

                    Array.from(columns).forEach(column => {
                        if (column.textContent.toLowerCase().includes(searchTerm)) {
                            matchFound = true;
                        }
                    });

                    row.style.display = matchFound ? "" : "none";
                });
            });

            // Implementasi fungsi Show Entries
            const entriesInput = document.getElementById("entries");
            entriesInput.addEventListener("input", function () {
                const rows = tableBody.getElementsByTagName("tr");
                const limit = parseInt(entriesInput.value);
                let rowCount = 0;

                Array.from(rows).forEach(row => {
                    if (row.style.display !== "none") {
                        rowCount++;
                        row.style.display = rowCount <= limit ? "" : "none";
                    }
                });
            });
        </script>

@endsection