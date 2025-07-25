@extends('admin.layouts.app')

@section('title', 'Edit Perhitungan SMART')

@section('content')
<style>
#calon_penerima[disabled], #jenis_beasiswa_id[disabled] {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background: none;
    background-image: none !important;
    box-shadow: none;
}
</style>
<div class="container mx-auto px-4 py-6 min-h-screen">
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-2xl font-semibold mb-4">Edit Input Nilai</h3>

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

        <!-- Form Edit Perhitungan SMART -->
        <form action="{{ route('admin.perhitungan_smart.update', $HitunganSmart->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Nama Calon Penerima -->
                <div class="flex flex-col">
                    <label for="calon_penerima" class="mb-2 font-medium">Nama Calon Penerima</label>
                    <select id="calon_penerima" name="calon_penerima_id"
                        class="border p-3 rounded shadow"
                        required disabled>
                        <option value="">Pilih Calon Penerima</option>
                        @foreach ($calonPenerimas as $calon)
                        <option value="{{ $calon->id }}" {{ $HitunganSmart->calon_penerima_id == $calon->id ? 'selected' : '' }}>
                            {{ $calon->nama_calon_penerima }}
                        </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="calon_penerima_id" value="{{ $HitunganSmart->calon_penerima_id }}">
                    @error('calon_penerima_id')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Pilih Jenis Beasiswa -->
                <div class="flex flex-col">
                    <label for="jenis_beasiswa_id" class="mb-2 font-medium">Beasiswa</label>
                    <select id="jenis_beasiswa_id"
                            disabled required
                            class="border p-3 rounded shadow"
                            style="appearance:none;-webkit-appearance:none;-moz-appearance:none;background:none;">
                        <option value="">Pilih Beasiswa</option>
                        @foreach ($jenisBeasiswas as $beasiswa)
                        <option value="{{ $beasiswa->id }}" {{ $HitunganSmart->jenis_beasiswa_id == $beasiswa->id ? 'selected' : '' }}>
                            {{ $beasiswa->nama }}
                        </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="jenis_beasiswa_id" value="{{ $HitunganSmart->jenis_beasiswa_id }}">
                    @error('jenis_beasiswa_id')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Kriteria Dinamis -->
            <div id="kriteria-container" class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- JS akan isikan --}}
            </div>

            <div class="flex space-x-4 justify-start mt-4">
                <button type="submit" class="bg-green-600 text-white py-2 px-6 rounded-lg shadow-md">Simpan</button>
                <a href="{{ route('admin.perhitungan_smart.index') }}" class="bg-yellow-500 text-white py-2 px-6 rounded-lg shadow-md">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const beasiswaSelect = document.getElementById('jenis_beasiswa_id');
        const kriteriaContainer = document.getElementById('kriteria-container');

        function clearKriteria() {
            kriteriaContainer.innerHTML = '';
        }

        function createKriteriaInput(kriteria, selectedNilai) {
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

            let adaNilai = false; // flag apakah isian lama sudah ada

            kriteria.subkriterias.forEach(sub => {
                const option = document.createElement('option');
                option.value = sub.sub_kriteria;
                option.textContent = `${sub.sub_kriteria} (${sub.nilai})`;
                if (sub.sub_kriteria == selectedNilai) {
                    option.selected = true;
                    adaNilai = true;
                }
                select.appendChild(option);
            });

            // Kalau belum pernah diinput, tambah placeholder "Pilih Nilai"
            if (!adaNilai) {
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Pilih Nilai';
                defaultOption.selected = true;
                select.insertBefore(defaultOption, select.firstChild);
            }

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
                        // ini array [kriteria_id => sub_kriteria] dari controller
                        const selectedNilai = @json($nilaiKriteria ?? []);
                        data.forEach(kriteria => {
                            const input = createKriteriaInput(kriteria, selectedNilai[kriteria.id] ?? '');
                            kriteriaContainer.appendChild(input);
                        });
                    }
                })
                .catch(() => {
                    kriteriaContainer.innerHTML = '<p class="text-red-600">Gagal memuat kriteria.</p>';
                });
        }

        @if($HitunganSmart->jenis_beasiswa_id)
        loadKriteria({{ $HitunganSmart->jenis_beasiswa_id }});
        @endif
    });
</script>
@endsection
