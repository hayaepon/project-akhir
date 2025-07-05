@extends('superadmin.layouts.app')

@section('title', 'Edit Sub Kriteria')

@section('content')
<div class="container mx-auto px-4 py-6 h-screen">
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h2 class="font-medium text-2xl mb-8 text-[22px]">Edit Sub Kriteria</h2>
        <hr class="border-t-2 border-gray-300 mb-4 w-full">

        <form action="{{ route('subkriteria.update', $subKriteria->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="flex flex-col mb-4">
                    <label for="beasiswa" class="text-sm font-medium mb-2">Beasiswa</label>
                    <select id="beasiswa" name="jenis_beasiswa_id" class="w-full p-3 border rounded-lg shadow-sm" required onchange="loadKriteria()">
                        <option value="">Pilih Beasiswa</option>
                        @foreach ($jenisBeasiswas as $beasiswa)
                            <option value="{{ $beasiswa->id }}" {{ $subKriteria->jenis_beasiswa_id == $beasiswa->id ? 'selected' : '' }}>
                                {{ $beasiswa->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col mb-4">
                    <label for="kriteria_id" class="text-sm font-medium mb-2">Kriteria</label>
                    <select id="kriteria_id" name="kriteria_id" class="w-full p-3 border rounded-lg shadow-sm" required>
                        <option value="">Pilih Kriteria</option>
                        @foreach ($kriterias as $kriteria)
                            <option value="{{ $kriteria->id }}" {{ $subKriteria->kriteria_id == $kriteria->id ? 'selected' : '' }}>
                                {{ $kriteria->kriteria }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
                <div class="flex flex-col mb-4">
                    <label for="sub_kriteria" class="text-sm font-medium mb-2">Sub Kriteria</label>
                    <input type="text" id="sub_kriteria" name="sub_kriteria" value="{{ $subKriteria->sub_kriteria }}" class="w-full p-3 border rounded-lg shadow-sm" required>
                </div>

                <div class="flex flex-col mb-4">
                    <label for="nilai" class="text-sm font-medium mb-2">Nilai</label>
                    <input type="number" id="nilai" name="nilai" value="{{ $subKriteria->nilai }}" class="w-full p-3 border rounded-lg shadow-sm" required>
                </div>
            </div>

            <div class="flex space-x-4 justify-start">
                <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded-lg shadow-md">Perbaharui</button>
                <a href="{{ route('subkriteria.index') }}" class="bg-yellow-400 text-white py-2 px-8 rounded-lg shadow-md">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
    function loadKriteria() {
        const beasiswaId = document.getElementById('beasiswa').value;
        const kriteriaSelect = document.getElementById('kriteria_id');
        kriteriaSelect.innerHTML = '<option>Loading...</option>';

        fetch(`/superadmin/subkriteria/get-kriteria/${beasiswaId}`)
            .then(response => response.json())
            .then(data => {
                kriteriaSelect.innerHTML = '<option value="">Pilih Kriteria</option>';
                data.forEach(kriteria => {
                    const option = document.createElement('option');
                    option.value = kriteria.id;
                    option.text = kriteria.kriteria;
                    kriteriaSelect.appendChild(option);
                });
            });
    }
</script>
@endsection
