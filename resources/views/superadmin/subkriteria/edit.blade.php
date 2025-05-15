@extends('superadmin.layouts.app')

@section('title', 'Edit Sub Kriteria')

@section('content')
<div class="container mx-auto px-4 py-6 h-screen">
    <!-- Form untuk mengedit Sub Kriteria -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h2 class="font-bold text-2xl mb-4">Edit Sub Kriteria</h2>
        <form action="{{ route('subkriteria.update', $subKriteria->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="beasiswa" class="block text-sm font-semibold">Beasiswa</label>
                    <select id="beasiswa" name="beasiswa" class="w-full p-3 border rounded-lg shadow-sm">
                        <option value="KIP-K" {{ $subKriteria->beasiswa == 'KIP-K' ? 'selected' : '' }}>KIP-K</option>
                        <option value="Tahfiz" {{ $subKriteria->beasiswa == 'Tahfiz' ? 'selected' : '' }}>Tahfiz</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="kriteria" class="block text-sm font-semibold">Kriteria</label>
                    <select id="kriteria" name="kriteria_id" class="w-full p-3 border rounded-lg shadow-sm" required>
                        <option value="">Select Kriteria</option>
                        @foreach($kriterias as $kriteria)
                            <option value="{{ $kriteria->id }}" {{ $subKriteria->kriteria_id == $kriteria->id ? 'selected' : '' }}>{{ $kriteria->kriteria }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="sub_kriteria" class="block text-sm font-semibold">Sub Kriteria</label>
                    <input type="text" id="sub_kriteria" name="sub_kriteria" value="{{ $subKriteria->sub_kriteria }}" class="w-full p-3 border rounded-lg shadow-sm" required>
                </div>

                <div class="mb-4">
                    <label for="nilai" class="block text-sm font-semibold">Nilai</label>
                    <input type="number" id="nilai" name="nilai" value="{{ $subKriteria->nilai }}" class="w-full p-3 border rounded-lg shadow-sm" required>
                </div>
            </div>

            <div class="mb-4">
                <button type="submit" class="bg-blue-500 text-white py-2 px-6 rounded-lg w-full sm:w-auto">Update Sub Kriteria</button>
            </div>
        </form>
    </div>
</div>
@endsection
