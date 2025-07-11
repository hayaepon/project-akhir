@extends('superadmin.layouts.app')

@section('title', 'Data Calon Penerima')

@section('content')
<div class="container mx-auto px-4 py-6 h-screen">

    <!-- Form Import Excel -->
    <div class="bg-white p-6 rounded-lg mb-4">
        <h2 class="text-xl font-medium mb-2">Import Data dari Spreadsheet</h2>
        <hr class="border-t-2 border-gray-300 mb-4 w-full">
        <form action="{{ route('calon-penerima.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <input type="file" name="file" accept=".xlsx,.xls" required class="p-2 border rounded w-full sm:w-auto">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Import Excel</button>
            </div>
            <p class="text-sm mt-2 text-blue-600 hover:underline">
    <a href="{{ asset('template/template_import_calon.xlsx') }}" download>
        📥 Download Template Excel (format: .xlsx / .xls)
    </a>
</p>

        </form>
    </div>

    <!-- Form Input -->
    <div class="bg-white p-6 rounded-lg mb-6">
        <h2 class="text-2xl font-medium mb-2 text-[22px]">Form Input Calon Penerima</h2>
        <hr class="border-t-2 border-gray-300 mb-4 w-full">
        <form action="{{ route('calon-penerima.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <label for="no_pendaftaran" class="text-sm font-medium text-[16px] mb-2">No Pendaftaran</label>
                    <input type="text" id="no_pendaftaran" name="no_pendaftaran" class="w-full p-3 border rounded-lg shadow-sm" required />
                </div>
                <div class="flex flex-col">
                    <label for="nama_calon_penerima" class="text-sm font-medium text-[16px] mb-2">Nama Calon Penerima</label>
                    <input type="text" id="nama_calon_penerima" name="nama_calon_penerima" class="w-full p-3 border rounded-lg shadow-sm" required />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <label for="NPSN" class="text-sm font-medium text-[16px] mb-2">NPSN</label>
                    <input type="text" id="NPSN" name="NPSN" class="w-full p-3 border rounded-lg shadow-sm" required />
                </div>
                <div class="flex flex-col">
                    <label for="jenis_beasiswa_id" class="text-sm font-medium text-[16px] mb-2">Pilih Beasiswa</label>
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

    <!-- Tabel Data -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-4">
        <h2 class="text-2xl font-medium mb-2">Data Calon Penerima</h2>
        <hr class="border-t-2 border-gray-300 mb-4 w-full">

        <!-- Show Entries dan Search -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <label for="entries" class="text-sm font-medium mr-2">Show</label>
                <input type="number" id="entries" class="p-2 border rounded w-20" min="1" value="5" />
                <span class="ml-2 text-sm">entries</span>
            </div>
            <div>
                <input type="text" id="search" placeholder="Cari..." class="p-2 border rounded" />
            </div>
        </div>

        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-blue-800 text-white">
                    <th class="border px-4 py-2  font-normal">No</th>
                    <th class="border px-4 py-2  font-normal">No Pendaftaran</th>
                    <th class="border px-4 py-2  font-normal">Nama Calon Penerima</th>
                    <th class="border px-4 py-2  font-normal">NPSN</th>
                    <th class="border px-4 py-2  font-normal">Beasiswa</th>
                    <th class="border px-4 py-2  font-normal">Aksi</th>
                </tr>
            </thead>
            <tbody id="table-body">
                @foreach($dataCalonPenerima as $data)
                    <tr class="bg-white">
                        <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="border px-4 py-2">{{ $data->no_pendaftaran }}</td>
                        <td class="border px-4 py-2">{{ $data->nama_calon_penerima }}</td>
                        <td class="border px-4 py-2">{{ $data->NPSN }}</td>
                        <td class="border px-4 py-2">{{ $data->jenisBeasiswa->nama ?? '-' }}</td>
                        <td class="border px-4 py-2 text-center">
                            <div class="flex justify-center space-x-3">
                                <a href="{{ route('calon-penerima.edit', $data->id) }}" class="text-yellow-500 hover:text-yellow-700">
                                    <i class="fas fa-edit"></i>    
                                 </a>
                                 <span class="text-gray-400">|</span>
                                <form id="delete-form-{{ $data->id }}" action="{{ route('calon-penerima.destroy', $data->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" onclick="confirmDelete({{ $data->id }})" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<!-- SweetAlert & Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data yang dihapus tidak dapat dikembalikan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }

    // Flash Message
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @elseif(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    // Filter search
    const searchInput = document.getElementById("search");
    const tableBody = document.getElementById("table-body");
    searchInput.addEventListener("input", function () {
        const searchTerm = this.value.toLowerCase();
        const rows = tableBody.querySelectorAll("tr");
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? "" : "none";
        });
    });

    // Show entries
    const entriesInput = document.getElementById("entries");
    entriesInput.addEventListener("input", function () {
        const rows = tableBody.querySelectorAll("tr");
        const limit = parseInt(this.value);
        let count = 0;
        rows.forEach(row => {
            if (row.style.display !== "none") {
                count++;
                row.style.display = count <= limit ? "" : "none";
            }
        });
    });
</script>
@endsection
