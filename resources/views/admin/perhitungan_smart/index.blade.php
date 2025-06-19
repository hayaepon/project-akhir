@extends('admin.layouts.app')

@section('title', 'Perhitungan SMART')

@section('content')

<div class="container mx-auto px-4 py-6 min-h-screen">
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-2xl font-semibold mb-2">Pertanyaan Wawancara</h3>
        <hr class="border-t-2 border-gray-300 mb-4 w-full">
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
                <div class="flex flex-col h-full">
                    <label for="calon_penerima" class="mb-2 font-medium">Nama Calon Penerima</label>
                    <select id="calon_penerima" name="calon_penerima_id" class="border p-3 rounded shadow w-full" required>
                        <option value="">Pilih Calon Penerima</option>
                        @foreach ($calonPenerimas as $calon)
                        <option value="{{ $calon->id }}" {{ old('calon_penerima_id') == $calon->id ? 'selected' : '' }} data-beasiswa="{{ $calon->jenis_beasiswa_id }}">
                            {{ $calon->nama_calon_penerima }}
                        </option>
                        @endforeach
                    </select>
                    @error('calon_penerima_id')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Pilih Jenis Beasiswa (Hidden by default) -->
                <div class="flex flex-col h-full" id="beasiswa-container" style="display: none;">
                    <label for="jenis_beasiswa_id" class="mb-2 font-medium">Beasiswa</label>
                    <select id="jenis_beasiswa_id" name="jenis_beasiswa_id" class="border p-3 rounded shadow w-full" required>
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
            <div id="kriteria-container" class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6" style="display: none;"></div>

            <div class="flex space-x-4 justify-start mt-4">
                <button type="submit" class="bg-green-600 text-white py-2 px-6 rounded-lg shadow-md">Simpan</button>
                <a href="{{ route('admin.perhitungan_smart.index') }}" class="bg-yellow-500 text-white py-2 px-6 rounded-lg shadow-md">Batal</a>
            </div>
        </form>
    </div>

    <!-- Tabel Hasil Perhitungan -->
    <div class="bg-white p-6 mb-2">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-xl font-medium text-[22px]">Tabel Perhitungan SMART</h3>

            <!-- Tombol Filter dengan Dropdown -->
            <div class="relative">
                <button id="filterButton" class="flex items-center bg-gray-500 text-white py-2 px-6 rounded-lg shadow-md hover:bg-gray-300 transition duration-300 mr-2">
                    <i class="fas fa-filter mr-2"></i> Filters
                </button>
                <div id="filterDropdown" class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg hidden z-10">
                    <form action="{{ route('admin.perhitungan_smart.index') }}" method="GET" id="filterForm">
                        <select name="jenis_beasiswa" class="w-full p-2 border border-gray-300 rounded-lg" id="filterSelect">
                            <option value="">Semua Beasiswa</option>
                            @foreach ($jenisBeasiswas as $jenis)
                            <option value="{{ $jenis->id }}" @if(request()->get('jenis_beasiswa') == $jenis->id) selected @endif>{{ $jenis->nama }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <hr class="border-t-2 border-gray-300 mb-4 w-full">

        <div class="overflow-x-auto h-96">
            <table id="smartTable" class="min-w-full table-auto border-collapse border border-gray-300">
                <thead class="bg-blue-800 text-white">
                    <tr>
                        <th class="border px-4 py-2 text-left font-normal">No</th>
                        <th class="border px-4 py-2 text-left font-normal">Nama Calon</th>
                        <th class="border px-4 py-2 text-left font-normal">Beasiswa</th>
                        @foreach ($headerKriteria as $id => $namaKriteria)
                        <th class="border px-4 py-2 text-left font-normal">{{ $namaKriteria }}</th>
                        @endforeach
                        <th class="border px-4 py-2 text-left font-normal">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hitunganSmarts as $i => $item)
                    <tr class="even:bg-gray-50 hover:bg-gray-100">
                        <td class="border px-4 py-2 text-left">{{ $i + 1 }}</td>
                        <td class="border px-4 py-2 text-left">{{ $item->calonPenerima->nama_calon_penerima }}</td>
                        <td class="border px-4 py-2 text-left">{{ $item->jenisBeasiswa->nama }}</td>
                        @foreach ($headerKriteria as $idKriteria => $namaKriteria)
                        <td class="border px-4 py-2 text-center">{{ $item->nilai_kriteria[$idKriteria] ?? '-' }}</td>
                        @endforeach
                        <td class="border px-4 py-2 text-center">
                            <div class="flex justify-center space-x-3">
                                <a href="{{ route('admin.perhitungan_smart.edit', $item->id) }}" class="text-yellow-500 hover:text-yellow-700">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <span class="text-gray-400">|</span>
                                <form action="{{ route('admin.perhitungan_smart.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin ingin menghapus data ini?')" class="text-red-600 hover:text-red-800">
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
    const calonPenerimaSelect = document.getElementById('calon_penerima');
    const beasiswaContainer = document.getElementById('beasiswa-container');
    const beasiswaSelect = document.getElementById('jenis_beasiswa_id');
    const kriteriaContainer = document.getElementById('kriteria-container');
    const filterButton = document.getElementById('filterButton');
    const filterDropdown = document.getElementById('filterDropdown');
    const filterSelect = document.getElementById('filterSelect');

    // Clear kriteria
    const clearKriteria = () => { 
        kriteriaContainer.innerHTML = '';
        kriteriaContainer.style.display = 'none';
    };

    // Generate input kriteria
    const createKriteriaInput = (kriteria) => {
        const wrap = document.createElement('div');
        wrap.className = 'flex flex-col';

        const lbl = document.createElement('label');
        lbl.className = 'mb-2 font-medium';
        lbl.textContent = kriteria.kriteria;
        lbl.setAttribute('for', `kriteria_${kriteria.id}`);

        const sel = document.createElement('select');
        sel.name  = `nilai_kriteria[${kriteria.id}]`;
        sel.id    = `kriteria_${kriteria.id}`;
        sel.className = 'border p-3 rounded shadow';
        sel.required  = true;

        sel.innerHTML = '<option value="">Pilih Nilai</option>';
        kriteria.subkriterias.forEach(sub => {
            const opt = document.createElement('option');
            opt.value = sub.sub_kriteria;
            opt.textContent = `${sub.sub_kriteria} (${sub.nilai})`;
            sel.appendChild(opt);
        });

        wrap.append(lbl, sel);
        return wrap;
    };

    // Load kriteria sesuai jenis beasiswa
    const loadKriteria = (id) => {
        if (!id) {
            clearKriteria();
            return;
        }
        fetch(`/admin/perhitungan-smart/kriteria/${id}`)
            .then(r => r.json())
            .then(data => {
                clearKriteria();
                if (data.length === 0) {
                    kriteriaContainer.innerHTML = '<p class="text-gray-500">Tidak ada kriteria untuk beasiswa ini.</p>';
                } else {
                    data.forEach(k => kriteriaContainer.appendChild(createKriteriaInput(k)));
                }
                kriteriaContainer.style.display = 'grid';
            })
            .catch(() => {
                kriteriaContainer.innerHTML = '<p class="text-red-600">Gagal memuat kriteria.</p>';
                kriteriaContainer.style.display = 'block';
            });
    };

    // Ketika select calon penerima di form input berubah
    calonPenerimaSelect.addEventListener('change', () => {
        const selectedOption = calonPenerimaSelect.options[calonPenerimaSelect.selectedIndex];
        const beasiswaId = selectedOption ? selectedOption.getAttribute('data-beasiswa') : '';
        if (calonPenerimaSelect.value) {
            beasiswaContainer.style.display = 'flex'; // Show beasiswa
            beasiswaSelect.value = beasiswaId ? beasiswaId : '';
            loadKriteria(beasiswaId);
        } else {
            beasiswaContainer.style.display = 'none'; // Hide beasiswa
            beasiswaSelect.value = '';
            clearKriteria();
        }
    });

    // Muat kriteria jika ada nilai lama (edit / error validation)
    @if(old('jenis_beasiswa_id'))
        beasiswaContainer.style.display = 'flex';
        loadKriteria({{ old('jenis_beasiswa_id') }});
    @endif

    // Otomatis kirim form filter ketika dropdown berubah
    filterSelect.addEventListener('change', function() {
        this.closest('form').submit();
    });

    // Toggle tampilan dropdown filter
    filterButton.addEventListener('click', (e) => {
        e.stopPropagation();
        filterDropdown.classList.toggle('hidden');
    });

    // Klik di luar dropdown ➜ tutup
    document.addEventListener('click', (e) => {
        if (!filterButton.contains(e.target) && !filterDropdown.contains(e.target)) {
            filterDropdown.classList.add('hidden');
        }
    });
});
</script>

@endsection
