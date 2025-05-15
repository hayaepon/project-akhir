@extends('superadmin.layouts.app')

@section('title', 'Edit Kriteria & Bobot')

@section('content')

<!-- Edit Kriteria -->
<div class="bg-white p-6 rounded-lg shadow-md mb-8">
    <h2 class="text-2xl font-bold mb-4">Edit Kriteria & Bobot</h2>

    <form action="{{ route('kriteria.update', $kriteria->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Menandakan bahwa ini adalah request update -->

        <!-- Beasiswa -->
        <div class="mb-4">
            <label for="beasiswa" class="block text-gray-700 font-semibold">Beasiswa</label>
            <select id="beasiswa" name="beasiswa" class="w-full p-3 border rounded-lg shadow-sm" required>
                <option value="KIP-K" @if($kriteria->beasiswa == 'KIP-K') selected @endif>KIP-K</option>
                <option value="Tahfiz" @if($kriteria->beasiswa == 'Tahfiz') selected @endif>Tahfiz</option>
            </select>
        </div>

        <!-- Kriteria -->
        <div class="mb-4">
            <label for="kriteria" class="block text-gray-700 font-semibold">Kriteria</label>
            <input type="text" id="kriteria" name="kriteria" class="w-full p-3 border rounded-lg shadow-sm" value="{{ old('kriteria', $kriteria->kriteria) }}" required>
        </div>

        <!-- Bobot -->
        <div class="mb-4">
            <label for="bobot" class="block text-gray-700 font-semibold">Bobot Kriteria (%)</label>
            <input type="number" id="bobot" name="bobot" class="w-full p-3 border rounded-lg shadow-sm" value="{{ old('bobot', $kriteria->bobot) }}" required>
        </div>

        <div class="flex space-x-4 justify-start">
            <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded-lg shadow-md">Update</button>
            <a href="{{ route('kriteria.index') }}" class="bg-yellow-400 text-white py-2 px-6 rounded-lg shadow-md">Batal</a>
        </div>
    </form>
</div>

@endsection
