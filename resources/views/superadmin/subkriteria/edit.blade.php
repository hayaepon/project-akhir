@extends('superadmin.layouts.app')

@section('title', 'Edit Sub Kriteria')

@section('content')
<div class="container mx-auto px-4 py-6 h-screen">
    <!-- Form untuk mengedit Sub Kriteria -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h2 class="font-medium text-2xl mb-8 text-[22px]">Edit Sub Kriteria</h2>
         <hr class="border-t-2 border-gray-300 mb-4 w-full">
        <form action="{{ route('subkriteria.update', $subKriteria->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="flex flex-col mb-4">
                    <label for="beasiswa" class="text-sm font-medium text-black-700 text-[16px] mb-2">Beasiswa</label>
                    <select id="beasiswa" name="beasiswa" class="w-full p-3 border rounded-lg shadow-sm">
                        <option value="KIP-K" {{ $subKriteria->beasiswa == 'KIP-K' ? 'selected' : '' }}>KIP-K</option>
                        <option value="Tahfiz" {{ $subKriteria->beasiswa == 'Tahfiz' ? 'selected' : '' }}>Tahfiz</option>
                    </select>
                </div>

                <div class="flex flex-col mb-4">
                    <label for="kriteria" class="text-sm font-medium text-black-700 text-[16px] mb-2">Kriteria</label>
                    <select id="kriteria" name="kriteria_id" class="w-full p-3 border rounded-lg shadow-sm" required>
                        <option value="">Select Kriteria</option>
                        @foreach($kriterias as $kriteria)
                        <option value="{{ $kriteria->id }}" {{ $subKriteria->kriteria_id == $kriteria->id ? 'selected' : '' }}>{{ $kriteria->kriteria }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
                <div class="flex flex-col mb-4">
                    <label for="sub_kriteria" class="text-sm font-medium text-black-700 text-[16px] mb-2">Sub Kriteria</label>
                    <input type="text" id="sub_kriteria" name="sub_kriteria" value="{{ $subKriteria->sub_kriteria }}" class="w-full p-3 border rounded-lg shadow-sm" required>
                </div>

                <div class="flex flex-col mb-4">
                    <label for="nilai" class="text-sm font-medium text-black-700 text-[16px] mb-2">Nilai</label>
                    <input type="number" id="nilai" name="nilai" value="{{ $subKriteria->nilai }}" class="w-full p-3 border rounded-lg shadow-sm" required>
                </div>
            </div>

            <div class="flex space-x-4 justify-start">
                <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded-lg shadow-md">Update</button>
                <a href="{{ route('kriteria.index') }}" class="bg-yellow-400 text-white py-2 px-8 rounded-lg shadow-md">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection