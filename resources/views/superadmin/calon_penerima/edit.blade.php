@extends('superadmin.layouts.app')

@section('title', 'Edit Calon Penerima')

@section('content')

    <!-- Form Edit Data Calon Penerima -->
    <div class="bg-white p-6 rounded-lg mb-6">
        <h2 class="text-2xl font-medium mb-8 text-[22px]">Form Edit Calon Penerima</h2>

        <form action="{{ route('calon-penerima.update', $data->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT') <!-- Metode PUT digunakan untuk update data -->

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <label for="no_pendaftaran" class="text-sm font-medium text-black-700 text-[16px] mb-2">No Pendaftaran</label>
                    <input type="text" id="no_pendaftaran" name="no_pendaftaran" class="w-full p-3 border-2 border-gray-400 rounded-md shadow-sm text-sm font-medium text-black-700 text-[16px] mb-2" value="{{ old('no_pendaftaran', $data->no_pendaftaran) }}" required />
                </div>
                <div class="flex flex-col">
                    <label for="nama_calon_penerima" class="text-sm font-medium text-black-700 text-[16px] mb-2">Nama Calon Penerima</label>
                    <input type="text" id="nama_calon_penerima" name="nama_calon_penerima" class="w-full p-3 border-2 border-gray-400 rounded-md shadow-sm text-sm font-medium text-black-700 text-[16px] mb-2" value="{{ old('nama_calon_penerima', $data->nama_calon_penerima) }}" required />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <label for="asal_sekolah" class="text-sm font-medium text-black-700 text-[16px] mb-2">Asal Sekolah</label>
                    <input type="text" id="asal_sekolah" name="asal_sekolah" class="w-full p-3 border-2 border-gray-400 rounded-md shadow-sm text-sm font-medium text-black-700 text-[16px] mb-2" value="{{ old('asal_sekolah', $data->asal_sekolah) }}" required />
                </div>
                <div class="flex flex-col">
                    <label for="pilihan_beasiswa" class="text-sm font-medium text-black-700 text-[16px] mb-2">Pilihan Beasiswa</label>
                    <select id="pilihan_beasiswa" name="pilihan_beasiswa" class="w-full p-3 border-2 border-gray-400 rounded-md shadow-sm text-sm font-medium text-black-700 text-[16px] mb-2" required>
                        <option value="KIP-K" {{ $data->pilihan_beasiswa == 'KIP-K' ? 'selected' : '' }}>KIP-K</option>
                        <option value="Tahfidz" {{ $data->pilihan_beasiswa == 'Tahfidz' ? 'selected' : '' }}>Tahfidz</option>
                    </select>
                </div>
            </div>

            <div>
                <button type="submit" class="bg-blue-800 text-white px-6 py-2 rounded-lg w-full sm:w-auto mt-6">Simpan</button>
            </div>
        </form>
    </div>

@endsection
