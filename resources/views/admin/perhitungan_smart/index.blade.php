@extends('admin.layouts.app')

@section('title', 'Perhitungan SMART')

@section('content')

<div class="container mx-auto px-4 py-6 min-h-screen">
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

    <!-- FORM INPUT PERHITUNGAN -->
    <div class="bg-white p-6 rounded shadow mb-6">
        <h3 class="text-2xl font-semibold mb-2">Input Nilai</h3>
        <hr class="border-t-2 border-gray-300 mb-4">

        <form action="{{ route('admin.perhitungan_smart.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="calon_penerima" class="block mb-2 font-medium">Nama Calon Penerima</label>
                    <select id="calon_penerima" name="calon_penerima_id" class="border p-3 rounded w-full" required>
                        <option value="">Pilih Calon Penerima</option>
                        @foreach ($calonPenerimas as $calon)
                        <option value="{{ $calon->id }}"
                            {{ old('calon_penerima_id') == $calon->id ? 'selected' : '' }}
                            data-beasiswa="{{ $calon->jenis_beasiswa_id }}"
                            {{ in_array($calon->id, $sudahDiinput ?? []) ? 'disabled' : '' }}>
                            {{ $calon->nama_calon_penerima }}
                        </option>
                        @endforeach
                    </select>
                    @error('calon_penerima_id')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div id="beasiswa-container" style="display: none;">
                    <label for="jenis_beasiswa_id" class="block mb-2 font-medium">Beasiswa</label>
                    <select id="jenis_beasiswa_id" name="jenis_beasiswa_id" class="border p-3 rounded w-full" required>
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

            <div id="kriteria-container" class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6" style="display: none;"></div>

            <div class="flex space-x-4 mt-4">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">Simpan</button>
                <a href="{{ route('admin.perhitungan_smart.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg">Batal</a>
            </div>
        </form>
    </div>

    <!-- TABEL PERHITUNGAN -->
    <div class="bg-white p-6 rounded shadow">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-2xl font-semibold mb-2">Tabel Perhitungan SMART</h3>
            <div class="relative">
                <button id="filterButton" class="flex items-center bg-gray-500 text-white py-2 px-6 rounded hover:bg-gray-700">
                    <i class="fas fa-filter mr-2"></i> Filters
                </button>
                <div id="filterDropdown" class="absolute right-0 mt-2 w-48 bg-white shadow rounded-lg hidden z-10">
                    <form action="{{ route('admin.perhitungan_smart.index') }}" method="GET" id="filterForm">
                        <select name="jenis_beasiswa" class="w-full p-2 border rounded-lg" id="filterSelect">
                            <option value="">Semua Beasiswa</option>
                            @foreach ($jenisBeasiswas as $jenis)
                            <option value="{{ $jenis->id }}" @if(request()->get('jenis_beasiswa') == $jenis->id) selected @endif>{{ $jenis->nama }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <hr class="border-t-2 border-gray-300 mb-4">

        {{-- MODE GROUPED: TABEL TERPISAH PER JENIS --}}
        @if(isset($grouped) && $grouped && isset($dataPerJenis))
            @foreach($dataPerJenis as $data)
                <div class="mb-2">
                    <h3 class="text-lg font-bold mb-2">{{ $data['jenis']->nama }}</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto border">
                            <thead class="bg-blue-800 text-white">
                                <tr>
                                    <th class="border px-4 py-2 font-normal">No</th>
                                    <th class="border px-4 py-2 font-normal">Nama Calon</th>
                                    @foreach ($data['headerKriteria'] as $id => $namaKriteria)
                                    <th class="border px-4 py-2 font-normal">{{ $namaKriteria }}</th>
                                    @endforeach
                                    <th class="border px-4 py-2 font-normal">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['hasilPerhitungan'] as $i => $item)
                                <tr class="even:bg-gray-50 hover:bg-gray-100">
                                    <td class="border px-4 py-2">{{ $i + 1 }}</td>
                                    <td class="border px-4 py-2">{{ $item->calonPenerima->nama_calon_penerima }}</td>
                                    @foreach ($data['headerKriteria'] as $idKriteria => $namaKriteria)
                                    <td class="border px-4 py-2 text-center">{{ $item->nilai_kriteria[$idKriteria] ?? '-' }}</td>
                                    @endforeach
                                    <td class="border px-4 py-2 text-center">
                                        <div class="flex justify-center items-center space-x-3">
                                            <a href="{{ route('admin.perhitungan_smart.edit', $item->id) }}" class="text-yellow-500 hover:text-yellow-700">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <span class="text-gray-400">|</span>
                                            <button type="button" onclick="confirmDelete({{ $item->id }})" class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $item->id }}" action="{{ route('admin.perhitungan_smart.destroy', $item->id) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @if(count($data['hasilPerhitungan']) == 0)
                                <tr>
                                    <td colspan="{{ 2 + count($data['headerKriteria']) }}" class="text-center py-4">Belum ada data perhitungan.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

        {{-- MODE TUNGGAL: SATU TABEL --}}
        @else
            @php
                $judulBeasiswa = null;
                if(request()->get('jenis_beasiswa') && isset($jenisBeasiswas)) {
                    $beasiswa = $jenisBeasiswas->firstWhere('id', request()->get('jenis_beasiswa'));
                    $judulBeasiswa = $beasiswa ? $beasiswa->nama : null;
                }
            @endphp
            @if($judulBeasiswa)
                <h3 class="text-lg font-bold mb-2">{{ $judulBeasiswa }}</h3>
            @endif
            <div class="overflow-x-auto max-h-[480px]">
                <table class="w-full table-auto border">
                    <thead class="bg-blue-800 text-white">
                        <tr>
                            <th class="border px-4 py-2 font-normal">No</th>
                            <th class="border px-4 py-2 font-normal">Nama Calon</th>
                            <th class="border px-4 py-2 font-normal">Beasiswa</th>
                            @foreach ($headerKriteria as $id => $namaKriteria)
                            <th class="border px-4 py-2 font-normal">{{ $namaKriteria }}</th>
                            @endforeach
                            <th class="border px-4 py-2 font-normal">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hitunganSmarts as $i => $item)
                        <tr class="even:bg-gray-50 hover:bg-gray-100">
                            <td class="border px-4 py-2">{{ $i + 1 }}</td>
                            <td class="border px-4 py-2">{{ $item->calonPenerima->nama_calon_penerima }}</td>
                            <td class="border px-4 py-2">{{ $item->jenisBeasiswa->nama }}</td>
                            @foreach ($headerKriteria as $idKriteria => $namaKriteria)
                            <td class="border px-4 py-2 text-center">{{ $item->nilai_kriteria[$idKriteria] ?? '-' }}</td>
                            @endforeach
                            <td class="border px-4 py-2 text-center">
                                <div class="flex justify-center items-center space-x-3">
                                    <a href="{{ route('admin.perhitungan_smart.edit', $item->id) }}" class="text-yellow-500 hover:text-yellow-700">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <span class="text-gray-400">|</span>
                                    <button type="button" onclick="confirmDelete({{ $item->id }})" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.perhitungan_smart.destroy', $item->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @if(count($hitunganSmarts) == 0)
                        <tr>
                            <td colspan="{{ 3 + count($headerKriteria) }}" class="text-center py-4">Belum ada data perhitungan.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Script -->
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const calonPenerimaSelect = document.getElementById('calon_penerima');
    const beasiswaContainer = document.getElementById('beasiswa-container');
    const beasiswaSelect = document.getElementById('jenis_beasiswa_id');
    const kriteriaContainer = document.getElementById('kriteria-container');
    const filterButton = document.getElementById('filterButton');
    const filterDropdown = document.getElementById('filterDropdown');
    const filterSelect = document.getElementById('filterSelect');

    function confirmDelete(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }

    window.confirmDelete = confirmDelete;

    const clearKriteria = () => {
        kriteriaContainer.innerHTML = '';
        kriteriaContainer.style.display = 'none';
    };

    const createKriteriaInput = (kriteria) => {
        const div = document.createElement('div');
        div.className = 'flex flex-col';

        const label = document.createElement('label');
        label.className = 'mb-2 font-medium';
        label.textContent = kriteria.kriteria;
        label.setAttribute('for', `kriteria_${kriteria.id}`);

        const select = document.createElement('select');
        select.name = `nilai_kriteria[${kriteria.id}]`;
        select.id = `kriteria_${kriteria.id}`;
        select.className = 'border p-3 rounded shadow';
        select.required = true;

        select.innerHTML = '<option value="">Pilih Nilai</option>';
        kriteria.subkriterias.forEach(sub => {
            const opt = document.createElement('option');
            opt.value = sub.sub_kriteria;
            opt.textContent = `${sub.sub_kriteria} (${sub.nilai})`;
            select.appendChild(opt);
        });

        div.append(label, select);
        return div;
    };

    const loadKriteria = (id) => {
        if (!id) return clearKriteria();
        fetch(`/admin/perhitungan-smart/kriteria/${id}`)
            .then(r => r.json())
            .then(data => {
                clearKriteria();
                if (data.length === 0) {
                    kriteriaContainer.innerHTML = '<p class="text-gray-500">Tidak ada kriteria.</p>';
                } else {
                    data.forEach(k => kriteriaContainer.appendChild(createKriteriaInput(k)));
                    kriteriaContainer.style.display = 'grid';
                }
            })
            .catch(() => {
                clearKriteria();
                kriteriaContainer.innerHTML = '<p class="text-red-600">Gagal memuat data.</p>';
                kriteriaContainer.style.display = 'block';
            });
    };

    calonPenerimaSelect.addEventListener('change', () => {
        const selected = calonPenerimaSelect.options[calonPenerimaSelect.selectedIndex];
        const beasiswaId = selected?.getAttribute('data-beasiswa') || '';
        if (calonPenerimaSelect.value) {
            beasiswaContainer.style.display = 'block';
            beasiswaSelect.value = beasiswaId;
            loadKriteria(beasiswaId);
        } else {
            beasiswaContainer.style.display = 'none';
            clearKriteria();
        }
    });

    @if(old('jenis_beasiswa_id'))
        beasiswaContainer.style.display = 'block';
        loadKriteria({{ old('jenis_beasiswa_id') }});
    @endif

    filterSelect.addEventListener('change', function () {
        this.closest('form').submit();
    });

    filterButton.addEventListener('click', (e) => {
        e.stopPropagation();
        filterDropdown.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
        if (!filterButton.contains(e.target) && !filterDropdown.contains(e.target)) {
            filterDropdown.classList.add('hidden');
        }
    });
});
</script>
@endpush

@endsection
