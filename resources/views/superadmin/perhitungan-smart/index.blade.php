@extends('superadmin.layouts.app')

@section('title', 'Perhitungan SMART')

@section('content')
<div class="container mx-auto px-4 py-2 h-screen">
    <div class="bg-white p-4 rounded-lg shadow-md mb-4 h-full">
        <div class="flex items-center mb-2 space-x-4 w-full"> 
            <h2 class="text-2xl font-semibold flex-grow">Tabel Perhitungan SMART</h2> 

            <form action="{{ route('hasil_seleksi.hitung') }}" method="GET" class="flex">
                @if($jenisBeasiswaId)
                <input type="hidden" name="jenis_beasiswa" value="{{ $jenisBeasiswaId }}">
                @endif
                <button type="submit" class="bg-blue-800 text-white py-2 px-6 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">
                    Hitung
                </button>
            </form>

            <div class="relative">
                <button id="filterButton" class="flex items-center bg-gray-500 text-white py-2 px-6 rounded-lg shadow-md hover:bg-gray-300 transition duration-300 mr-2">
                    <i class="fas fa-filter mr-2"></i> Filters
                </button>
                <div id="filterDropdown" class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg hidden">
                    <form action="{{ route('perhitungan-smart.index') }}" method="GET" id="filterForm">
                        @csrf
                        <select name="jenis_beasiswa" class="w-full p-2 border border-gray-300 rounded-lg" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Semua Beasiswa</option>
                            @foreach ($jenisBeasiswas as $jenis)
                            <option value="{{ $jenis->id }}" @if(request()->get('jenis_beasiswa') == $jenis->id) selected @endif>{{ $jenis->nama }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <hr class="border-t-2 border-gray-300 mb-2 w-full">

        {{-- TABEL TERPISAH JIKA FILTER SEMUA BEASISWA --}}
        @if(isset($grouped) && $grouped && isset($dataPerJenis))
            @foreach($dataPerJenis as $data)
                <div class="mb-2">
                    <h3 class="text-lg font-bold mb-2">{{ $data['jenis']->nama }}</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-blue-800 text-white">
                                    <th class="border px-4 py-2 text-left font-normal">No</th>
                                    <th class="border px-4 py-2 text-left font-normal">Nama Calon Penerima</th>
                                    @foreach ($data['headerKriteria'] as $namaKriteria)
                                    <th class="border px-4 py-2 text-center font-normal">{{ $namaKriteria }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data['hasilPerhitungan'] as $i => $hasil)
                                <tr class="border-b even:bg-gray-50 hover:bg-gray-100">
                                    <td class="border px-4 py-2 text-center">{{ $i + 1 }}</td>
                                    <td class="border px-4 py-2">{{ $hasil->calonPenerima->nama_calon_penerima }}</td>
                                    @foreach (array_keys($data['headerKriteria']) as $idKriteria)
                                    <td class="border px-4 py-2 text-center">
                                        @php
                                        $nilaiMentah = $hasil->nilai_kriteria[$idKriteria] ?? null;
                                        $nilaiAngka = null;
                                        if ($nilaiMentah) {
                                            $sub = \App\Models\Subkriteria::where('kriteria_id', $idKriteria)
                                                ->where('sub_kriteria', $nilaiMentah)
                                                ->first();
                                            $nilaiAngka = $sub ? $sub->nilai : null;
                                        }
                                        @endphp
                                        {{ $nilaiAngka !== null ? $nilaiAngka : '-' }}
                                    </td>
                                    @endforeach
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ 2 + count($data['headerKriteria']) }}" class="text-center py-4">Belum ada data perhitungan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

        {{-- TABEL TUNGGAL JIKA FILTER PER JENIS --}}
        @else
            @php
                // Ambil nama beasiswa yang sedang difilter
                $judulBeasiswa = null;
                if($jenisBeasiswaId && isset($jenisBeasiswas)) {
                    $beasiswa = $jenisBeasiswas->firstWhere('id', $jenisBeasiswaId);
                    $judulBeasiswa = $beasiswa ? $beasiswa->nama : null;
                }
            @endphp
            @if($judulBeasiswa)
                <h3 class="text-lg font-bold mb-2">{{ $judulBeasiswa }}</h3>
            @endif
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-blue-800 text-white">
                            <th class="border px-4 py-2 text-left font-normal">No</th>
                            <th class="border px-4 py-2 text-left font-normal">Nama Calon Penerima</th>
                            <th class="border px-4 py-2 text-left font-normal">Beasiswa</th>
                            @foreach ($headerKriteria as $namaKriteria)
                            <th class="border px-4 py-2 text-center font-normal">{{ $namaKriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($hasilPerhitungan as $i => $hasil)
                        <tr class="border-b even:bg-gray-50 hover:bg-gray-100">
                            <td class="border px-4 py-2 text-center">{{ $i + 1 }}</td>
                            <td class="border px-4 py-2">{{ $hasil->calonPenerima->nama_calon_penerima }}</td>
                            <td class="border px-4 py-2">
                                {{ $hasil->calonPenerima->jenisBeasiswa->nama ?? '-' }}
                            </td>
                            @foreach (array_keys($headerKriteria) as $idKriteria)
                            <td class="border px-4 py-2 text-center">
                                @php
                                $nilaiMentah = $hasil->nilai_kriteria[$idKriteria] ?? null;
                                $nilaiAngka = null;
                                if ($nilaiMentah) {
                                    $sub = \App\Models\Subkriteria::where('kriteria_id', $idKriteria)
                                        ->where('sub_kriteria', $nilaiMentah)
                                        ->first();
                                    $nilaiAngka = $sub ? $sub->nilai : null;
                                }
                                @endphp
                                {{ $nilaiAngka !== null ? $nilaiAngka : '-' }}
                            </td>
                            @endforeach
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ 3 + count($headerKriteria) }}" class="text-center py-4">Belum ada data perhitungan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<script>
    document.getElementById('filterButton').addEventListener('click', function() {
        const filterDropdown = document.getElementById('filterDropdown');
        filterDropdown.classList.toggle('hidden');
    });
</script>
@endsection
