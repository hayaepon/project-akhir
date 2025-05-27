@extends('admin.layouts.app')

@section('title', 'Kriteria & Bobot')

@section('content')
<!-- Data Kriteria & Bobot -->
<div class="bg-white p-6 mb-2">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-medium text-[22px]">Data Kriteria & Bobot</h3>
        <!-- Tombol Switch KIP-K & Tahfiz di kanan -->
        <div class="flex space-x-4">
            <button id="kipk-btn" class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-blue-800">KIP-K</button>
            <button id="tahfidz-btn" class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-blue-800">Tahfidz</button>
        </div>
    </div>
    <hr class="border-t-2 border-gray-300 mb-4 w-full">
    <table id="tabelKriteria" class="min-w-full table-auto">
        <thead>
            <tr class="bg-blue-800 text-white">
                <th class="border px-4 py-2 text-left font-normal">Beasiswa</th>
                <th class="border px-4 py-2 text-left font-normal">Nama Kriteria</th>
                <th class="border px-4 py-2 text-left font-normal">Bobot</th>
                <th class="border px-4 py-2 text-left font-normal">Atribut</th>
            </tr>
        </thead>
       <tbody>
    @foreach($kriterias as $kriteria)
    <tr class="bg-white beasiswa-row" data-beasiswa="{{ strtolower(str_replace(' ', '', $kriteria->jenisBeasiswa->nama ?? 'unknown')) }}">
        <td class="border px-6 py-2">{{ $kriteria->jenisBeasiswa->nama ?? '-' }}</td>
        <td class="border px-4 py-2">{{ $kriteria->kriteria }}</td>
        <td class="border px-4 py-2">{{ $kriteria->bobot }}</td>
        <td class="border px-4 py-2">{{ $kriteria->atribut }}</td>
    </tr>
    @endforeach
</tbody>
    </table>
</div>

<script>
        document.addEventListener('DOMContentLoaded', function () {
            const kipkBtn = document.getElementById('kipk-btn');
            const tahfidzBtn = document.getElementById('tahfidz-btn');
            const rows = document.querySelectorAll('tbody tr');

            function filterRows(jenis) {
                if(jenis === 'all'){
                    rows.forEach(row => row.style.display = '');
                    return;
                }
                rows.forEach(row => {
                    const beasiswa = row.getAttribute('data-beasiswa');
                    if (beasiswa === jenis) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            function setActiveButton(activeBtn) {
                [kipkBtn, tahfidzBtn].forEach(btn => {
                    btn.classList.remove('bg-blue-800');
                    btn.classList.add('bg-gray-400');
                });
                activeBtn.classList.remove('bg-gray-400');
                activeBtn.classList.add('bg-blue-800');
            }

            // Saat load, belum ada tombol dipilih: tampil semua data, tombol default belum aktif
            filterRows('all');

            kipkBtn.addEventListener('click', function() {
                filterRows('kip-k');
                setActiveButton(kipkBtn);
            });

            tahfidzBtn.addEventListener('click', function() {
                filterRows('tahfidz');
                setActiveButton(tahfidzBtn);
            });
        });
    </script>


@endsection