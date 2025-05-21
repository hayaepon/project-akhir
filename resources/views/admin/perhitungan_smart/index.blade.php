@extends('admin.layouts.app')

@section('title', 'Perhitungan SMART')

@section('content')
    <div class="container mx-auto px-4 py-6 min-h-screen">
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-2xl font-semibold mb-4">Perhitungan SMART</h3>

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
                    <a href="{{ route('admin.perhitungan_smart.index') }}" class="bg-yellow-500 text-white py-2 px-6 rounded-lg shadow-md">Batal</a>
                </div>
            </form>
        </div>

        <!-- Tabel Hasil Perhitungan -->
        <div class="bg-white p-6 rounded shadow mt-10">
            <h3 class="text-2xl font-semibold mb-4">Tabel Perhitungan SMART</h3>
            <div class="overflow-auto">
                <table class="min-w-full border border-gray-300 border-collapse">
                    <thead class="bg-blue-700 text-white">
                        <tr>
                            <th class="border px-4 py-2 text-left">No</th>
                            <th class="border px-4 py-2 text-left">Nama Calon</th>
                            <th class="border px-4 py-2 text-left">Beasiswa</th>
                            @if(count($headerKriteria ?? []))
                                @foreach ($headerKriteria as $kriteria)
                                    <th class="border px-4 py-2 text-left">{{ $kriteria }}</th>
                                @endforeach
                            @else
                                <th class="border px-4 py-2 text-left" colspan="3">Kriteria</th>
                            @endif
                            <th class="border px-4 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($hitunganSmarts as $perhitungan)
                            <tr>
                                <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="border px-4 py-2">{{ $perhitungan->calonPenerima->nama_calon_penerima }}</td>
                                <td class="border px-4 py-2">{{ $perhitungan->jenis_beasiswa->nama }}</td>

                            @php
                                $nilai = $perhitungan->nilai_kriteria; // sudah berbentuk array karena casting
                            @endphp
                            @foreach ($headerKriteria as $index => $kriteria)
                                <td class="border px-4 py-2">{{ $nilai[$index] ?? '-' }}</td>
                            @endforeach

                                <td class="border px-4 py-2">
                                    <form action="{{ route('admin.perhitungan_smart.destroy', $perhitungan->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Yakin ingin menghapus?')" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 4 + count($headerKriteria ?? [1, 2, 3]) }}" class="text-center text-gray-600 py-4">Belum ada data perhitungan.</td>
                            </tr>
                        @endforelse
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
    </script>
@endsection
