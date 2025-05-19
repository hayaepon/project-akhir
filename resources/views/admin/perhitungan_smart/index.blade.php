@extends('admin.layouts.app')

@section('title', 'Perhitungan SMART')

@section('content')
<!-- Perhitungan SMART -->
<div class="container mx-auto px-4 py-6 h-screen">
    <div class="bg-white p-6">
        <h3 class="text-2xl font-medium mb-2 text-[22px]">Perhitungan SMART</h3>
        <hr class="border-t-2 border-gray-300 mb-4 w-full">
        <!-- Form Perhitungan SMART -->
        <form action="{{ route('admin.perhitungan_smart.index') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Kolom 1: Nama Calon Penerima -->
                <div class="flex flex-col">
                    <label for="calon_penerima" class="text-sm font-medium text-black-700 text-[16px] mb-2">Nama Calon Penerima</label>
                    <select id="calon_penerima" name="calon_penerima" class="w-full p-3 border rounded-lg shadow-sm">
                        <option value="">Pilih Nama</option>
                        <option value="calon1">Calon 1</option>
                        <option value="calon2">Calon 2</option>
                    </select>
                </div>

                <!-- Kolom 2: Beasiswa -->
                <div class="flex flex-col">
                    <label for="beasiswa" class="text-sm font-medium text-black-700 text-[16px] mb-2">Beasiswa</label>
                    <select id="beasiswa" name="beasiswa" class="w-full p-3 border rounded-lg shadow-sm">
                        <option value="">Pilih Beasiswa</option>
                        <option value="kipk">KIPK</option>
                        <option value="tahfidz">Tahfidz</option>
                    </select>
                </div>
            </div>

            <!-- Kriteria dan Subkriteria Tanpa Pembungkus -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
                <div class="flex flex-col">
                    <label for="kriteria1" class="text-sm font-medium text-black-700 text-[16px] mb-2">Kriteria 1</label>
                    <input type="text" id="kriteria1" name="kriteria1" class="w-full p-3 border rounded-lg shadow-sm" placeholder="Masukkan Kriteria 1">
                </div>

                <div class="flex flex-col">
                    <label for="subkriteria1" class="text-sm font-medium text-black-700 text-[16px] mb-2">Sub Kriteria 1</label>
                    <select id="subkriteria1" name="subkriteria1" class="w-full p-3 border rounded-lg shadow-sm">
                        <option value="">Pilih Sub Kriteria</option>
                        <option value="1">Sub Kriteria 1</option>
                        <option value="2">Sub Kriteria 2</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
                <div class="flex flex-col">
                    <label for="kriteria2" class="text-sm font-medium text-black-700 text-[16px] mb-2">Kriteria 2</label>
                    <input type="text" id="kriteria2" name="kriteria2" class="w-full p-3 border rounded-lg shadow-sm" placeholder="Masukkan Kriteria 2">
                </div>

                <div class="flex flex-col">
                    <label for="subkriteria2" class="text-sm font-medium text-black-700 text-[16px] mb-2">Sub Kriteria 2</label>
                    <select id="subkriteria2" name="subkriteria2" class="w-full p-3 border rounded-lg shadow-sm">
                        <option value="">Pilih Sub Kriteria</option>
                        <option value="1">Sub Kriteria 1</option>
                        <option value="2">Sub Kriteria 2</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
                <div class="flex flex-col">
                    <label for="kriteria3" class="text-sm font-medium text-black-700 text-[16px] mb-2">Kriteria 3</label>
                    <input type="text" id="kriteria3" name="kriteria3" class="w-full p-3 border rounded-lg shadow-sm" placeholder="Masukkan Kriteria 3">
                </div>

                <div class="flex flex-col">
                    <label for="subkriteria3" class="text-sm font-medium text-black-700 text-[16px] mb-2">Sub Kriteria 3</label>
                    <select id="subkriteria3" name="subkriteria3" class="w-full p-3 border rounded-lg shadow-sm">
                        <option value="">Pilih Sub Kriteria</option>
                        <option value="1">Sub Kriteria 1</option>
                        <option value="2">Sub Kriteria 2</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
                <div class="flex flex-col">
                    <label for="kriteria4" class="text-sm font-medium text-black-700 text-[16px] mb-2">Kriteria 4</label>
                    <input type="text" id="kriteria4" name="kriteria4" class="w-full p-3 border rounded-lg shadow-sm" placeholder="Masukkan Kriteria 4">
                </div>

                <div class="flex flex-col">
                    <label for="subkriteria4" class="text-sm font-medium text-black-700 text-[16px] mb-2">Sub Kriteria 4</label>
                    <select id="subkriteria4" name="subkriteria4" class="w-full p-3 border rounded-lg shadow-sm">
                        <option value="">Pilih Sub Kriteria</option>
                        <option value="1">Sub Kriteria 1</option>
                        <option value="2">Sub Kriteria 2</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="bg-blue-800 text-white px-6 py-2 rounded-lg w-full sm:w-auto mt-6">Submit</button>
        </form>
    </div>

    <!-- Tabel Hasil Perhitungan -->
    <div class="bg-white p-6">
        <h3 class="text-2xl font-medium mb-2 text-[22px]">Hasil Perhitungan SMART</h3>
        <hr class="border-t-2 border-gray-300 mb-4 w-full">

        <table class="min-w-full table-auto border-collapse border border-gray-200">
            <thead class="bg-blue-800 text-white">
                <tr>
                    <th class="border px-4 py-2 text-left">No</th>
                    <th class="border px-4 py-2 text-left">Nama Calon Penerima</th>
                    <th class="border px-4 py-2 text-left">Beasiswa</th>
                    <th class="border px-4 py-2 text-left">Kriteria 1</th>
                    <th class="border px-4 py-2 text-left">Kriteria 2</th>
                    <th class="border px-4 py-2 text-left">Kriteria 3</th>
                    <th class="border px-4 py-2 text-left">Kriteria 4</th>
                    <th class="border px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border px-4 py-2">1</td>
                    <td class="border px-4 py-2">Calon 1</td>
                    <td class="border px-4 py-2">KIPK</td>
                    <td class="border px-4 py-2">80</td>
                    <td class="border px-4 py-2">75</td>
                    <td class="border px-4 py-2">90</td>
                    <td class="border px-4 py-2">85</td>
                    <td class="border px-4 py-2 text-center">
                        <div class="flex justify-center items-center space-x-3">
                        <a href="#" class="text-blue-500 hover:underline">
                            <i class="fas fa-edit text-yellow-300"></i>
                        </a>
                        <span class="text-gray-400">|</span>
                        <form action="#" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline">
                            <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <!-- Tambahkan lebih banyak baris jika ada data lainnya -->
            </tbody>
        </table>
    </div>
</div>
@endsection
