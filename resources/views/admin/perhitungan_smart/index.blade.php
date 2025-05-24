@extends('admin.layouts.app')

@section('title', 'Perhitungan SMART')

@section('content')

    <div class="container mx-auto px-4 py-6 min-h-screen">
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-2xl font-semibold mb-4">Perhitungan SMART</h3>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form Perhitungan SMART -->
            <form action="{{ route('admin.perhitungan_smart.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Nama Calon Penerima -->
                    <div class="flex flex-col">
                        <label for="calon_penerima" class="mb-2 font-medium">Nama Calon Penerima</label>
                        <select id="calon_penerima" name="calon_penerima_id" class="border p-3 rounded shadow" required>
                            <option value="">Pilih Calon Penerima</option>
                            @foreach ($calonPenerimas as $calon)
                                <option value="{{ $calon->id }}" {{ old('calon_penerima_id') == $calon->id ? 'selected' : '' }}>
                                    {{ $calon->nama_calon_penerima }}
                                </option>
                            @endforeach
                        </select>
                        @error('calon_penerima_id')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Pilih Jenis Beasiswa -->
                    <div class="flex flex-col">
                        <label for="jenis_beasiswa_id" class="mb-2 font-medium">Beasiswa</label>
                        <select id="jenis_beasiswa_id" name="jenis_beasiswa_id" class="border p-3 rounded shadow" required>
                            <option value="">Pilih Beasiswa</option>
                            @foreach ($jenisBeasiswas as $beasiswa)
                                <option value="{{ $beasiswa->id }}" {{ old('jenis_beasiswa_id') == $beasiswa->id ? 'selected' : '' }}>
                                    {{ $beasiswa->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('jenis_beasiswa_id')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Kriteria Dinamis -->
                <div id="kriteria-container" class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Diisi oleh JavaScript --}}
                </div>

                <div class="flex space-x-4 justify-start mt-4">
                    <button type="submit" class="bg-green-600 text-white py-2 px-6 rounded-lg shadow-md">Simpan</button>
                    <a href="{{ route('admin.perhitungan_smart.index') }}"
                        class="bg-yellow-500 text-white py-2 px-6 rounded-lg shadow-md">Batal</a>
                    </div>
                    </form>
                    </div>

                    <!-- Tabel Hasil Perhitungan -->
                    <div class="bg-white p-6 rounded shadow mt-10">
                        <h3 class="text-2xl font-semibold mb-4">Tabel Perhitungan SMART</h3>

                        <!-- Filter Beasiswa -->
                        <div class="flex gap-4 mb-4">
                            <button onclick="filterTable('KIP-K')"
                                class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">KIP-K</button>
                            <button onclick="filterTable('Tahfidz')"
                                class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">Tahfidz</button>
                        </div>

                        <div class="overflow-x-auto">
                            <table id="smartTable" class="min-w-full table-auto border-collapse border border-gray-300">
                                <thead class="bg-blue-700 text-white">
                                    <tr>
                                        <th class="border border-gray-300 px-4 py-2 text-left whitespace-nowrap">No</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left whitespace-nowrap">Nama Calon</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left whitespace-nowrap">Beasiswa</th>
                                        @foreach ($headerKriteria as $namaKriteria)
                                            <th class="border border-gray-300 px-4 py-2 text-center whitespace-nowrap">{{ $namaKriteria }}</th>
                                        @endforeach
                                        <th class="border border-gray-300 px-4 py-2 text-center whitespace-nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hitunganSmarts as $i => $item)
                                        <tr data-beasiswa="{{ $item->jenisBeasiswa->nama }}" class="even:bg-gray-50 hover:bg-gray-100">
                                            <td class="border border-gray-300 px-4 py-2 text-left">{{ $i + 1 }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-left">
                                                {{ $item->calonPenerima->nama_calon_penerima }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-left">{{ $item->jenisBeasiswa->nama }}</td>
                                            @foreach (array_keys($headerKriteria) as $idKriteria)
                                                <td class="border border-gray-300 px-4 py-2 text-center">
                                                    {{ $item->nilai_kriteria[$idKriteria] ?? '-' }}
                                                </td>
                                            @endforeach
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                <div class="flex justify-center space-x-3">
                                                    <!-- Tombol Edit -->
                                                    <a href="{{ route('admin.perhitungan_smart.edit', $item->id) }}"
                                                        class="text-yellow-500 hover:text-yellow-700">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <span class="text-gray-400">|</span>
                                                    <!-- Tombol Hapus -->
                                                    <form action="{{ route('admin.perhitungan_smart.destroy', $item->id) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Yakin ingin menghapus data ini?')"
                                                            class="text-red-600 hover:text-red-800">
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
                                </div>
                                </div>

                                {{-- Script: Load Kriteria --}}
                                <script>
                                    document.addEventListener('DOMContentLoaded', () => {
                                        const beasiswaSelect = document.getElementById('jenis_beasiswa_id');
                                        const kriteriaContainer = document.getElementById('kriteria-container');

                                        function clearKriteria() {
                                            kriteriaContainer.innerHTML = '';
                                        }

                                        function createKriteriaInput(kriteria) {
                                            const wrapper = document.createElement('div');
                                            wrapper.classList.add('flex', 'flex-col');

                                            const label = document.createElement('label');
                                            label.classList.add('mb-2', 'font-medium');
                                            label.textContent = kriteria.kriteria;
                                            label.setAttribute('for', `kriteria_${kriteria.id}`);
                                            wrapper.appendChild(label);

                                            const select = document.createElement('select');
                                            select.name = `nilai_kriteria[${kriteria.id}]`;
                                            select.id = `kriteria_${kriteria.id}`;
                                            select.classList.add('border', 'p-3', 'rounded', 'shadow');
                                            select.required = true;

                                            const defaultOption = document.createElement('option');
                                            defaultOption.value = '';
                                            defaultOption.textContent = 'Pilih Nilai';
                                            select.appendChild(defaultOption);

                                            kriteria.subkriterias.forEach(sub => {
                                                const option = document.createElement('option');
                                                option.value = sub.sub_kriteria;
                                                option.textContent = `${sub.sub_kriteria} (${sub.nilai})`;
                                                select.appendChild(option);
                                            });

                                            wrapper.appendChild(select);
                                            return wrapper;
                                        }

                                        function loadKriteria(jenis_beasiswa_id) {
                                            fetch(`/admin/perhitungan-smart/kriteria/${jenis_beasiswa_id}`)
                                                .then(response => response.json())
                                                .then(data => {
                                                    clearKriteria();
                                                    if (data.length === 0) {
                                                        kriteriaContainer.innerHTML = '<p class="text-gray-500">Tidak ada kriteria untuk beasiswa ini.</p>';
                                                    } else {
                                                        data.forEach(kriteria => {
                                                            const input = createKriteriaInput(kriteria);
                                                            kriteriaContainer.appendChild(input);
                                                        });
                                                    }
                                                })
                                                .catch(() => {
                                                    kriteriaContainer.innerHTML = '<p class="text-red-600">Gagal memuat kriteria.</p>';
                                                });
                                        }

                                        beasiswaSelect.addEventListener('change', () => {
                                            const id = beasiswaSelect.value;
                                            if (id) loadKriteria(id);
                                            else clearKriteria();
                                        });

                                        @if(old('jenis_beasiswa_id'))
                                            loadKriteria({{ old('jenis_beasiswa_id') }});
                                        @endif
                                        });

                                    // Script Filter Tabel Berdasarkan Beasiswa
                                    function filterTable(beasiswa) {
                                        const rows = document.querySelectorAll('#smartTable tbody tr');
                                        rows.forEach(row => {
                                            const jenis = row.getAttribute('data-beasiswa') || '';
                                            if (beasiswa === 'all') {
                                                row.style.display = '';
                                            } else {
                                                row.style.display = (jenis.toLowerCase().includes(beasiswa.toLowerCase())) ? '' : 'none';
                                            }
                                        });
                                    }
                                </script>
@endsection