@extends('superadmin.layouts.app')

@section('title', 'Edit Kriteria & Bobot')

@section('content')

    <!-- Edit Kriteria -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-2xl font-medium mb-4 text-[22px]">Edit Kriteria & Bobot</h2>
        <hr class="border-t-2 border-gray-300 mb-4 w-full">
        <form action="{{ route('kriteria.update', $kriteria->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Beasiswa -->
            <div class="flex flex-col mb-4">
                <label for="jenis_beasiswa_id" class="text-sm font-medium text-black-700 text-[16px] mb-2">Beasiswa</label>
                <select id="jenis_beasiswa_id" name="jenis_beasiswa_id" class="w-full p-3 border rounded-lg shadow-sm" required>
                    <option value="">Pilih Beasiswa</option>
                    @foreach($jenisBeasiswas as $beasiswa)
                        <option value="{{ $beasiswa->id }}" {{ old('jenis_beasiswa_id', $kriteria->jenis_beasiswa_id) == $beasiswa->id ? 'selected' : '' }}>
                            {{ $beasiswa->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Kriteria -->
            <div class="flex flex-col mb-4">
                <label for="kriteria" class="text-sm font-medium text-black-700 text-[16px] mb-2">Kriteria</label>
                <input type="text" id="kriteria" name="kriteria" class="w-full p-3 border rounded-lg shadow-sm" value="{{ old('kriteria', $kriteria->kriteria) }}" required>
            </div>

            <!-- Bobot -->
            <input type="number" id="bobot" name="bobot" class="w-full p-3 border rounded-lg shadow-sm"
                value="{{ old('bobot', $kriteria->bobot * 100) }}" required>

            <!-- atribut Kriteria -->
            <select id="atribut" name="atribut" class="w-full p-3 border rounded-lg shadow-sm" required>
                <option value="">Pilih atribut</option>
                <option value="benefit" {{ old('atribut', $kriteria->atribut) == 'benefit' ? 'selected' : '' }}>Benefit</option>
                <option value="cost" {{ old('atribut', $kriteria->atribut) == 'cost' ? 'selected' : '' }}>Cost</option>
            </select>


            <div class="flex space-x-4 justify-start">
                <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded-lg shadow-md">Update</button>
                <a href="{{ route('kriteria.index') }}" class="bg-yellow-400 text-white py-2 px-8 rounded-lg shadow-md">Batal</a>
            </div>
        </form>
    </div>

@endsection
