@extends('superadmin.layouts.app')

@section('title', 'Kriteria & Bobot')

@section('content')
    <div class="container mx-auto px-4 py-6 h-screen">

        <!-- Tambah Kriteria -->
        <div class="bg-white p-6 mb-6">
            <h2 class="text-2xl font-medium mb-2 text-[22px]">Tambah Kriteria & Bobot</h2>
            <hr class="border-t-2 border-gray-300 mb-4 w-full">
            <form action="{{ route('kriteria.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Beasiswa -->
                <div class="flex flex-col mb-4">
                    <label for="bobot" class="text-sm font-medium text-black-700 text-[16px] mb-2">Beasiswa</label>
                    <select id="jenis_beasiswa_id" name="jenis_beasiswa_id" class="w-full p-3 border rounded-lg shadow-sm"
                        required>
                        <option value="">Pilih Beasiswa</option>
                        @foreach($jenisBeasiswas as $beasiswa)
                            <option value="{{ $beasiswa->id }}" {{ (old('jenis_beasiswa_id') == $beasiswa->id || (isset($kriteria) && $kriteria->jenis_beasiswa_id == $beasiswa->id)) ? 'selected' : '' }}>{{ $beasiswa->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kriteria -->
                <div class="flex flex-col mb-4">
                    <label for="kriteria" class="text-sm font-medium text-black-700 text-[16px] mb-2">Kriteria</label>
                    <input type="text" id="kriteria" name="kriteria" class="w-full p-3 border rounded-lg shadow-sm"
                        required>
                </div>

                <!-- Bobot -->
                <div class="flex flex-col mb-4">
                    <label for="bobot" class="text-sm font-medium text-black-700 text-[16px] mb-2">Bobot Kriteria</label>
                    <input type="number" id="bobot" name="bobot" class="w-full p-3 border rounded-lg shadow-sm" required>
                </div>

                <!-- Atribut -->
                <div class="flex flex-col mb-8">
                    <label for="bobot" class="text-sm font-medium text-black-700 text-[16px] mb-2">Atribut</label>
                    <select id="atribut" name="atribut" class="w-full p-3 border rounded-lg shadow-sm" required>
                        <option value="">Pilih Atribut</option>
                        <option value="benefit" {{ old('atribut') == 'benefit' ? 'selected' : '' }}>Benefit</option>
                        <option value="cost" {{ old('atribut') == 'cost' ? 'selected' : '' }}>Cost</option>
                    </select>
                </div>

                <div class="flex space-x-4 justify-start">
                    <button type="submit" class="bg-green-600 text-white py-2 px-6 rounded-lg shadow-md">Simpan</button>
                    <a href="{{ route('kriteria.index') }}"
                        class="bg-yellow-400 text-white py-2 px-8 rounded-lg shadow-md">Batal</a>
                </div>

            </form>
        </div>

        <!-- Data Kriteria & Bobot -->
        <div class="bg-white p-6 mb-4">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-xl font-medium text-[22px]">Data Kriteria & Bobot</h3>

                <!-- Tombol Switch KIP-K & Tahfidz di kanan -->
                <!-- <div class="flex space-x-4">
                    <button id="kipk-btn" class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-blue-800">KIP-K</button>
                    <button id="tahfidz-btn" class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-blue-800">Tahfidz</button>
                </div> -->
            </div>

            <hr class="border-t-2 border-gray-300 mb-4 w-full">


            <table class="min-w-full mt-6 table-auto">
                <thead>
                    <tr class="bg-blue-800 text-white">
                        <th class="border px-6 py-2 text-left font-normal">Beasiswa</th>
                        <th class="border px-6 py-2 text-left font-normal">Kriteria</th>
                        <th class="border px-6 py-2 text-left font-normal">Bobot</th>
                        <th class="border px-6 py-2 text-left font-normal">Atribut</th>
                        <th class="border px-6 py-2 text-left font-normal">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedKriterias as $beasiswaNama => $kriteriaList)
                        <tr class="bg-gray-100">
                            <td class="border px-6 py-2" rowspan="{{ count($kriteriaList) }}">{{ $beasiswaNama }}</td>
                            <td class="border px-6 py-2">{{ $kriteriaList[0]->kriteria }}</td>
                            <td class="border px-6 py-2">{{ $kriteriaList[0]->bobot }}</td>
                            <td class="border px-6 py-2 capitalize">{{ $kriteriaList[0]->atribut }}</td>
                            <td class="border px-6 py-2 text-center">
                                <!-- Aksi -->
                                <div class="flex justify-center items-center space-x-3">
                                    <a href="{{ route('kriteria.edit', $kriteriaList[0]->id) }}"
                                        class="text-yellow-500 hover:text-yellow-700">
                                        <i class="fas fa-edit text-yellow-300"></i>
                                    </a>
                                    <span class="text-gray-400">|</span>
                                    <form action="{{ route('kriteria.destroy', $kriteriaList[0]->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @foreach($kriteriaList->slice(1) as $kriteria)
                            <tr>
                                <td class="border px-6 py-2">{{ $kriteria->kriteria }}</td>
                                <td class="border px-6 py-2">{{ $kriteria->bobot }}</td>
                                <td class="border px-6 py-2 capitalize">{{ $kriteria->atribut }}</td>
                                <td class="border px-6 py-2 text-center">
                                    <div class="flex justify-center items-center space-x-3">
                                        <a href="{{ route('kriteria.edit', $kriteria->id) }}"
                                            class="text-yellow-500 hover:text-yellow-700">
                                            <i class="fas fa-edit text-yellow-300"></i>
                                        </a>
                                        <span class="text-gray-400">|</span>
                                        <form action="{{ route('kriteria.destroy', $kriteria->id) }}" method="POST"
                                            class="form-delete" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
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

    <script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11">
            document.addEventListener('DOMContentLoaded', function() {
            const kipkBtn = document.getElementById('kipk-btn');
            const tahfidzBtn = document.getElementById('tahfidz-btn');
            const rows = document.querySelectorAll('tbody tr');

            function filterRows(jenis) {
                if (jenis === 'all') {
                rows.forEach(row => row.style.display = '');
            return;
                }
                rows.forEach(row => {
                    const beasiswa = row.getAttribute('data-beasiswa');
            if (beasiswa === jenis) {
                row.style.display = '';
                    } else {
                row.style.display = 'none';
                    }
                });
            }

            function setActiveButton(activeBtn) {
                [kipkBtn, tahfidzBtn].forEach(btn => {
                    btn.classList.remove('bg-blue-800');
                    btn.classList.add('bg-gray-400');
                });
            activeBtn.classList.remove('bg-gray-400');
            activeBtn.classList.add('bg-blue-800');
            }

            // Saat load, belum ada tombol dipilih: tampil semua data, tombol default belum aktif
            filterRows('all');

            kipkBtn.addEventListener('click', function() {
                filterRows('kip-k');
            setActiveButton(kipkBtn);
            });

            tahfidzBtn.addEventListener('click', function() {
                filterRows('tahfidz');
            setActiveButton(tahfidzBtn);
            });

            filterTable('kip-k');  //klo mau dicampur antara kipk dan tahfiz kosongin aja kipknya filterTable('');
            setActiveButton('kip-k');
        });
    </script>
    <script>
            // NOTIFIKASI FLASH MESSAGE
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
                });
            @elseif(session('error'))
                Swal.fire({
                    icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 3000
                });
            @endif

        // KONFIRMASI HAPUS
        document.querySelectorAll('form.form-delete').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault(); // Hentikan submit bawaan

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Submit form jika dikonfirmasi
                        }
                    });
                });
        });
    </script>

@endsection