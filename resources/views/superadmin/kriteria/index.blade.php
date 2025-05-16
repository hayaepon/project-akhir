@extends('superadmin.layouts.app')

@section('title', 'Kriteria & Bobot')

@section('content')
<div class="container mx-auto px-4 py-6 h-screen">

    <!-- Tambah Kriteria -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-2xl font-bold mb-4">Tambah Kriteria & Bobot</h2>

        <form action="{{ route('kriteria.store') }}" method="POST">
            @csrf

            <!-- Beasiswa -->
            <div class="mb-4">
                <label for="beasiswa" class="block text-gray-700 font-semibold">Beasiswa</label>
                <select id="beasiswa" name="beasiswa" class="w-full p-3 border rounded-lg shadow-sm">
                    <option value="">Pilih Beasiswa</option>
                    <option value="KIP-K">KIP-K</option>
                    <option value="Tahfidz">Tahfidz</option>
                </select>
            </div>

            <!-- Kriteria -->
            <div class="mb-4">
                <label for="kriteria" class="block text-gray-700 font-semibold">Kriteria</label>
                <input type="text" id="kriteria" name="kriteria" class="w-full p-3 border rounded-lg shadow-sm" required>
            </div>

            <!-- Bobot -->
            <div class="mb-4">
                <label for="bobot" class="block text-gray-700 font-semibold">Bobot Kriteria (%)</label>
                <input type="number" id="bobot" name="bobot" class="w-full p-3 border rounded-lg shadow-sm" required>
            </div>

            <div class="flex space-x-4 justify-start">
                <button type="submit" class="bg-green-600 text-white py-2 px-6 rounded-lg shadow-md">Simpan</button>
                <a href="{{ route('kriteria.index') }}" class="bg-yellow-400 text-white py-2 px-6 rounded-lg shadow-md">Batal</a>
            </div>

        </form>
    </div>

    <!-- Data Kriteria & Bobot -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h3 class="text-xl font-bold mb-4">Data Kriteria & Bobot</h3>

        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-blue-800 text-white font-medium">
                    <th class="border px-4 py-2 text-left font-normal">Beasiswa</th>
                    <th class="border px-4 py-2 text-left font-normal">Kriteria</th>
                    <th class="border px-4 py-2 text-left font-normal">Bobot</th>
                    <th class="border px-4 py-2 text-left font-normal">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kriterias as $kriteria)
                    <tr class="bg-white">
                        <td class="border px-4 py-2">{{ $kriteria->beasiswa }}</td>
                        <td class="pborder px-4 py-2">{{ $kriteria->kriteria }}</td>
                        <td class="border px-4 py-2">{{ $kriteria->bobot }}%</td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('kriteria.edit', $kriteria->id) }}" class="text-yellow-500">
                            <i class="fas fa-edit text-yellow-300"></i>
                            </a> |
                            <form action="{{ route('kriteria.destroy', $kriteria->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600">
                                <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

@endsection
