@extends('superadmin.layouts.app')

@section('title', 'Edit Calon Penerima')

@section('content')
    <div class="container mx-auto px-4 py-6 h-screen">
        @if(session('success') || session('error'))
            <div id="flash-message" class="fixed top-5 right-5 px-6 py-4 rounded-lg shadow-lg z-50 text-white transition-opacity duration-500 ease-in-out
                    {{ session('success') ? 'bg-green-500' : 'bg-red-500' }}">
                <div class="flex items-center space-x-2">
                    <i class="fas {{ session('success') ? 'fa-check-circle' : 'fa-exclamation-circle' }}"></i>
                    <span>{{ session('success') ?? session('error') }}</span>
                </div>
            </div>

            <script>
                setTimeout(() => {
                    const flash = document.getElementById('flash-message');
                    if (flash) {
                        flash.style.opacity = 0;
                        setTimeout(() => flash.remove(), 500);
                    }
                }, 4000);
            </script>
        @endif

        <!-- Form Edit Data Calon Penerima -->
        <div class="bg-white p-6 rounded-lg mb-6">
            <h2 class="text-2xl font-medium mb-4 text-[22px]">Form Edit Calon Penerima</h2>
            <hr class="border-t-2 border-gray-300 mb-4 w-full">
            <form action="{{ route('calon-penerima.update', $data->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT') <!-- Metode PUT digunakan untuk update data -->

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="flex flex-col">
                        <label for="no_pendaftaran" class="text-sm font-medium text-black-700 text-[16px] mb-2">No
                            Pendaftaran</label>
                        <input type="text" id="no_pendaftaran" name="no_pendaftaran"
                            class="w-full p-3 border rounded-lg shadow-sm"
                            value="{{ old('no_pendaftaran', $data->no_pendaftaran) }}" required />
                    </div>
                    <div class="flex flex-col">
                        <label for="nama_calon_penerima" class="text-sm font-medium text-black-700 text-[16px] mb-2">Nama
                            Calon Penerima</label>
                        <input type="text" id="nama_calon_penerima" name="nama_calon_penerima"
                            class="w-full p-3 border rounded-lg shadow-sm"
                            value="{{ old('nama_calon_penerima', $data->nama_calon_penerima) }}" required />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="flex flex-col">
                        <label for="NPSN" class="text-sm font-medium text-black-700 text-[16px] mb-2">NPSN</label>
                        <input type="text" id="NPSN" name="NPSN" class="w-full p-3 border rounded-lg shadow-sm"
                            value="{{ old('NPSN', $data->NPSN) }}" required />
                    </div>

                    <div class="flex flex-col">
                        <label for="jenis_beasiswa_id" class="text-sm font-medium text-black-700 text-[16px] mb-2">Jenis
                            Beasiswa</label>
                        <select id="jenis_beasiswa_id" name="jenis_beasiswa_id"
                            class="w-full p-3 border rounded-lg shadow-sm" required>
                            <option value="">-- Pilih Jenis Beasiswa --</option>
                            @foreach($jenisBeasiswas as $beasiswa)
                                <option value="{{ $beasiswa->id }}" {{ $data->jenis_beasiswa_id == $beasiswa->id ? 'selected' : '' }}>
                                    {{ $beasiswa->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex space-x-4 justify-start mt-8">
                <button type="submit" class="bg-green-600 text-white py-2 px-6 rounded-lg shadow-md">Simpan</button>
                <a href="{{ route('calon-penerima.index') }}" class="bg-yellow-400 text-white py-2 px-8 rounded-lg shadow-md">Batal</a>
            </div>
            </form>
        </div>

@endsection