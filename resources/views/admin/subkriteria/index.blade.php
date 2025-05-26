@extends('admin.layouts.app')

@section('title', 'Kriteria & Bobot')

@section('content')
<!-- Tabel Sub Kriteria -->
<div class="bg-white p-6 mb-2">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-medium text-[22px]">Data Sub Kriteria</h3>

        <!-- Tombol Switch KIP-K & Tahfiz di kanan -->
        <div class="flex space-x-4">
            <button id="kipk-btn" class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-blue-800">KIP-K</button>
            <button id="tahfidz-btn" class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-blue-800">Tahfidz</button>
        </div>
    </div>
    <hr class="border-t-2 border-gray-300 mb-4 w-full">
    <table class="min-w-full border-collapse">
        <thead>
            <tr class="bg-blue-800 text-white">
                <th class="border px-4 py-2 text-left font-normal">No</th>
                <th class="border px-4 py-2 text-left font-normal">Kriteria</th>
                <th class="border px-4 py-2 text-left font-normal">Sub Kriteria</th>
                <th class="border px-4 py-2 text-left font-normal">Nilai</th>
            </tr>
        </thead>
        <tbody>
    @foreach ($subkriterias as $subKriteria)
    <tr class="bg-white" data-beasiswa-id="{{ $subKriteria->kriteria->jenis_beasiswa_id }}">
        <td class="border px-6 py-2 font-normal">{{ $loop->iteration }}</td>
        <td class="border px-6 py-2 font-normal">{{ $subKriteria->kriteria->kriteria }}</td>
        <td class="border px-6 py-2 font-normal">{{ $subKriteria->sub_kriteria }}</td>
        <td class="border px-6 py-2 font-normal">{{ $subKriteria->nilai }}</td>
    </tr>
    @endforeach
</tbody>

    </table>
</div>
</div>




<script>
    document.addEventListener('DOMContentLoaded', function () {
        const kipkBtn = document.getElementById('kipk-btn');
        const tahfidzBtn = document.getElementById('tahfidz-btn');
        const rows = document.querySelectorAll('tbody tr');

        function filterRows(beasiswaId) {
            rows.forEach(row => {
                if (row.dataset.beasiswaId === beasiswaId.toString()) {
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

        // Default: KIP-K (id = 1) aktif
        filterRows('1');
        setActiveButton(kipkBtn);

        kipkBtn.addEventListener('click', function () {
            filterRows('1');
            setActiveButton(kipkBtn);
        });

        tahfidzBtn.addEventListener('click', function () {
            filterRows('2');
            setActiveButton(tahfidzBtn);
        });
    });
</script>
@endsection