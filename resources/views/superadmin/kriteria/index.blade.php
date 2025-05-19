@extends('superadmin.layouts.app')

@section('title', 'Kriteria & Bobot')

@section('content')
    <div class="container mx-auto px-4 py-6 h-screen">

        <!-- Tambah Kriteria -->
        <div class="bg-white p-6 mb-6">
            <h2 class="text-2xl font-medium mb-4 text-[22px]">Tambah Kriteria & Bobot</h2>
             <hr class="border-t-2 border-gray-300 mb-4 w-full">
            <form action="{{ route('kriteria.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Beasiswa -->
                <select id="jenis_beasiswa_id" name="jenis_beasiswa_id" class="w-full p-3 border rounded-lg shadow-sm" required>
                    <option value="">Pilih Beasiswa</option>
                    @foreach($jenisBeasiswas as $beasiswa)
                        <option value="{{ $beasiswa->id }}" {{ (old('jenis_beasiswa_id') == $beasiswa->id || (isset($kriteria) && $kriteria->jenis_beasiswa_id == $beasiswa->id)) ? 'selected' : '' }}>
                            {{ $beasiswa->nama }}
                        </option>
                    @endforeach
                </select>

                <!-- Kriteria -->
                <div class="flex flex-col mb-4">
                    <label for="kriteria" class="text-sm font-medium text-black-700 text-[16px] mb-2">Kriteria</label>
                    <input type="text" id="kriteria" name="kriteria" class="w-full p-3 border rounded-lg shadow-sm" required>
                </div>

                <!-- Bobot -->
                <div class="flex flex-col mb-4">
                    <label for="bobot" class="text-sm font-medium text-black-700 text-[16px] mb-2">Bobot Kriteria (%)</label>
                    <input type="number" id="bobot" name="bobot" class="w-full p-3 border rounded-lg shadow-sm" required>
                </div>

                <div class="flex space-x-4 justify-start">
                    <button type="submit" class="bg-green-600 text-white py-2 px-6 rounded-lg shadow-md ">Simpan</button>
                    <a href="{{ route('kriteria.index') }}" class="bg-yellow-400 text-white py-2 px-8 rounded-lg shadow-md ">Batal</a>
                </div>

            </form>
        </div>

        <!-- Data Kriteria & Bobot -->
        <div class="bg-white p-6 mb-8">
            <h3 class="text-xl font-medium mb-4 text-[22px]">Data Kriteria & Bobot</h3>
            <hr class="border-t-2 border-gray-300 mb-4 w-full">

            <table class="min-w-full mt-6 table-auto">
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
                            <td class="border px-4 py-2">{{ $kriteria->jenisBeasiswa->nama ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $kriteria->kriteria }}</td>
                            <td class="border px-4 py-2">{{ $kriteria->bobot }}</td>
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
