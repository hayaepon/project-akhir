@extends('superadmin.layouts.app')

@section('title', 'Sub Kriteria')

@section('content')
<div class="container mx-auto px-4 py-6 h-screen">

    <!-- Flash Message -->
    @if(session('success') || session('error'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: '{{ session('success') ? 'success' : 'error' }}',
                title: '{{ session('success') ? 'Berhasil!' : 'Gagal!' }}',
                text: '{{ session('success') ?? session('error') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

    <!-- Form Sub Kriteria -->
    <div class="bg-white p-6 mb-4">
        <h2 class="font-medium text-2xl mb-2 text-[22px]">Tambah Sub Kriteria</h2>
        <hr class="border-t-2 border-gray-300 mb-4 w-full">
        <form action="{{ route('subkriteria.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="flex flex-col mb-4">
                    <label for="beasiswa" class="text-sm font-medium text-[16px] mb-2">Beasiswa</label>
                    <select id="beasiswa" name="beasiswa" class="w-full p-3 border rounded-lg shadow-sm">
                        <option value="">Pilih Beasiswa</option>
                        @foreach($jenisBeasiswas as $beasiswa)
                            <option value="{{ $beasiswa->id }}">{{ $beasiswa->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col mb-4">
                    <label for="kriteria_id" class="text-sm font-medium text-[16px] mb-2">Kriteria</label>
                    <select id="kriteria_id" name="kriteria_id" class="w-full p-3 border rounded-lg shadow-sm" required>
                        <option value="">Pilih Kriteria</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
                <div class="flex flex-col mb-4">
                    <label for="sub_kriteria" class="text-sm font-medium text-[16px] mb-2">Sub Kriteria</label>
                    <input type="text" id="sub_kriteria" name="sub_kriteria" class="w-full p-3 border rounded-lg shadow-sm" required>
                </div>

                <div class="flex flex-col mb-4">
                    <label for="nilai" class="text-sm font-medium text-[16px] mb-2">Nilai</label>
                    <input type="number" id="nilai" name="nilai" class="w-full p-3 border rounded-lg shadow-sm" required>
                </div>
            </div>

            <div class="mt-6 flex space-x-4 justify-start">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-6 rounded-lg transition">Simpan</button>
                <a href="{{ route('subkriteria.index') }}" class="bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-2 px-8 rounded-lg transition">Batal</a>
            </div>
        </form>
    </div>

    <!-- Tabel Sub Kriteria -->
    <div class="bg-white p-6 mb-4">
        <div class="flex justify-between items-center mb-2">
            <h3 class="font-medium text-2xl text-[22px]">Data Sub Kriteria</h3>
            <div class="flex space-x-4">
                <button id="kipk-btn" class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-blue-800">KIP-K</button>
                <button id="tahfidz-btn" class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-blue-800">Tahfidz</button>
            </div>
        </div>

        <hr class="border-t-2 border-gray-300 mb-4 w-full">

        <table class="min-w-full mt-6 table-auto">
            <thead>
                <tr class="bg-blue-800 text-white">
                    <th class="border px-6 py-2">Beasiswa</th>
                    <th class="border px-6 py-2">Kriteria</th>
                    <th class="border px-6 py-2">Sub Kriteria</th>
                    <th class="border px-6 py-2">Nilai</th>
                    <th class="border px-6 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($grouped as $kriteria_id => $items)
                    @php
                        $rowspan = count($items);
                        $kriteria = $items->first()->kriteria;
                        $beasiswa = $items->first()->kriteria->jenisBeasiswa;
                    @endphp
                    <tr class="border-b" data-beasiswa="{{ strtolower($beasiswa->nama ?? '') }}">
                        <td class="border px-6 py-2" rowspan="{{ $rowspan }}">{{ $beasiswa->nama ?? '-' }}</td>
                        <td class="border px-6 py-2" rowspan="{{ $rowspan }}">{{ $kriteria->kriteria }}</td>
                        <td class="border px-6 py-2">{{ $items->first()->sub_kriteria }}</td>
                        <td class="border px-6 py-2">{{ $items->first()->nilai }}</td>
                        <td class="border px-6 py-2 text-center">
                            <div class="flex justify-center items-center space-x-3">
                                <a href="{{ route('subkriteria.edit', $items->first()->id) }}" class="text-yellow-500 hover:underline">
                                    <i class="fas fa-edit text-yellow-300"></i>
                                </a>
                                <span class="text-gray-400">|</span>
                                <button type="button" onclick="confirmDelete({{ $items->first()->id }})" class="text-red-500 hover:underline">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $items->first()->id }}" action="{{ route('subkriteria.destroy', $items->first()->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @foreach ($items->slice(1) as $item)
                        <tr class="border-b" data-beasiswa="{{ strtolower($beasiswa->nama ?? '') }}">
                            <td class="border px-6 py-2">{{ $item->sub_kriteria }}</td>
                            <td class="border px-6 py-2">{{ $item->nilai }}</td>
                            <td class="border px-6 py-2 text-center">
                                <div class="flex justify-center items-center space-x-3">
                                    <a href="{{ route('subkriteria.edit', $item->id) }}" class="text-yellow-500 hover:underline">
                                        <i class="fas fa-edit text-yellow-300"></i>
                                    </a>
                                    <span class="text-gray-400">|</span>
                                    <button type="button" onclick="confirmDelete({{ $item->id }})" class="text-red-500 hover:underline">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('subkriteria.destroy', $item->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Delete Confirmation
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

    $(document).ready(function() {
        // Load kriteria berdasarkan beasiswa
        $('#beasiswa').on('change', function() {
            const beasiswaID = $(this).val();
            if (beasiswaID) {
                $.ajax({
                    url: '/get-kriteria/' + beasiswaID,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#kriteria_id').empty().append('<option value="">Pilih Kriteria</option>');
                        $.each(data, function(key, value) {
                            $('#kriteria_id').append('<option value="' + value.id + '">' + value.kriteria + '</option>');
                        });
                    }
                });
            } else {
                $('#kriteria_id').empty().append('<option value="">Pilih Kriteria</option>');
            }
        });

        // Filter table
        function filterTable(beasiswa) {
            if (beasiswa === '') {
                $('tbody tr').show();
            } else {
                $('tbody tr').each(function() {
                    const jenis = $(this).data('beasiswa');
                    $(this).toggle(jenis === beasiswa);
                });
            }
        }

        function setActiveButton(beasiswa) {
            $('#kipk-btn, #tahfidz-btn').removeClass('bg-blue-800').addClass('bg-gray-400');
            if (beasiswa === 'kip-k') $('#kipk-btn').removeClass('bg-gray-400').addClass('bg-blue-800');
            if (beasiswa === 'tahfidz') $('#tahfidz-btn').removeClass('bg-gray-400').addClass('bg-blue-800');
        }

        $('#kipk-btn').click(function() {
            filterTable('kip-k');
            setActiveButton('kip-k');
        });

        $('#tahfidz-btn').click(function() {
            filterTable('tahfidz');
            setActiveButton('tahfidz');
        });

        // Default filter
        filterTable('kip-k');
        setActiveButton('kip-k');
    });
</script>
@endpush
