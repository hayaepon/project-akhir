@extends('superadmin.layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $roleName = ucwords(str_replace('_', ' ', Auth::user()->role));
    $userName = Auth::user()->name;
@endphp

<!-- Sambutan -->
<div class="bg-gradient-to-r from-blue-50 to-blue-100 p-6 rounded-lg shadow-md mb-8">
    <div class="flex justify-center mb-4">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-16">
    </div>
    <h2 class="text-center text-2xl sm:text-3xl font-bold text-blue-900 mb-2">Selamat Datang, {{ $userName }}</h2>
    <p class="text-center text-gray-700 max-w-2xl mx-auto">
        Sistem Pendukung Keputusan Penerimaan Beasiswa STMIK Antar Bangsa menggunakan metode <strong>SMART</strong> 
        untuk membantu seleksi penerimaan beasiswa secara objektif dan efisien.
    </p>
</div>

<!-- Ringkasan Data -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    @php
        $cards = [
            ['icon' => 'users', 'title' => 'Jumlah Pendaftar', 'value' => $jumlahPendaftar],
            ['icon' => 'user-graduate', 'title' => 'Pendaftar KIP-K', 'value' => $jumlahKipk],
            ['icon' => 'book-open', 'title' => 'Pendaftar Tahfidz', 'value' => $jumlahTahfidz],
        ];
    @endphp

    @foreach ($cards as $card)
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition duration-200">
        <div class="flex items-center justify-between mb-3">
            <div class="text-blue-800">
                <i class="fas fa-{{ $card['icon'] }} fa-2x"></i>
            </div>
            <div class="text-right">
                <h3 class="text-2xl font-bold text-gray-800">{{ $card['value'] }}</h3>
                <p class="text-sm text-gray-500">{{ $card['title'] }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Grafik Pendaftaran dan Pie Chart -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Grafik Batang -->
    <div class="bg-white p-6 rounded-xl shadow-sm border">
        <h3 class="text-xl font-semibold text-blue-900 mb-4">Grafik Pendaftaran</h3>
        <div class="h-64">
            <canvas id="grafikPendaftaran"></canvas>
        </div>
    </div>

    <!-- Pie Chart -->
    <div class="bg-white p-6 rounded-xl shadow-sm border">
        <h3 class="text-xl font-semibold text-blue-900 mb-4">Perbandingan Jenis Beasiswa</h3>
        <div class="h-64">
            <canvas id="pieChartBeasiswa"></canvas>
        </div>
    </div>
</div>

<!-- Penjelasan SMART -->
<div class="bg-white p-6 rounded-xl shadow-sm border mb-8">
    <h3 class="text-xl font-semibold text-blue-900 mb-4">📊 Penjelasan Metode SMART</h3>
    <p class="text-gray-700 mb-4">
        <strong>SMART (Simple Multi Attribute Rating Technique)</strong> adalah metode yang digunakan untuk menentukan pilihan terbaik berdasarkan sejumlah kriteria dengan bobot tertentu.
    </p>
    <div class="grid sm:grid-cols-2 gap-4 text-gray-700 text-sm">
        <ul class="list-disc list-inside space-y-1">
            <li><strong>Alternatif:</strong> Calon penerima beasiswa</li>
            <li><strong>Kriteria:</strong> IPK, Penghasilan, Prestasi, dsb.</li>
            <li><strong>Bobot:</strong> Menentukan pentingnya setiap kriteria</li>
        </ul>
        <ul class="list-disc list-inside space-y-1">
            <li><strong>Jenis Kriteria:</strong>
                <ul class="ml-5 list-disc">
                    <li><em>Benefit:</em> Semakin tinggi, semakin baik (contoh: IPK)</li>
                    <li><em>Cost:</em> Semakin rendah, semakin baik (contoh: Penghasilan orang tua)</li>
                </ul>
            </li>
        </ul>
    </div>
    <div class="mt-4">
        <p class="mb-2 font-medium">Langkah-langkah:</p>
        <ol class="list-decimal list-inside text-sm text-gray-700 space-y-1">
            <li>Normalisasi nilai tiap kriteria (benefit/cost)</li>
            <li>Kalikan nilai normalisasi dengan bobot kriteria</li>
            <li>Jumlahkan seluruh hasil untuk skor akhir</li>
        </ol>
        <p class="text-xs text-gray-600 italic mt-2">
            Rumus: <code>Nilai Akhir = (Bobot₁ × Nilai₁) + (Bobot₂ × Nilai₂) + ... + (Bobotₙ × Nilaiₙ)</code>
        </p>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const tahunLabels = @json($grafikPendaftaran->pluck('tahun'));
    const dataPendaftaran = @json($grafikPendaftaran->pluck('total'));
    const jumlahKipk = {{ $jumlahKipk }};
    const jumlahTahfidz = {{ $jumlahTahfidz }};

    // Grafik Batang
    new Chart(document.getElementById('grafikPendaftaran').getContext('2d'), {
        type: 'bar',
        data: {
            labels: tahunLabels,
            datasets: [{
                label: 'Jumlah Pendaftar',
                data: dataPendaftaran,
                backgroundColor: 'rgba(2, 52, 131, 0.5)',
                borderColor: 'rgba(2, 52, 131, 1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Pie Chart
    new Chart(document.getElementById('pieChartBeasiswa').getContext('2d'), {
        type: 'pie',
        data: {
            labels: ['KIP-K', 'Tahfidz'],
            datasets: [{
                data: [jumlahKipk, jumlahTahfidz],
                backgroundColor: [
                    'rgba(59, 130, 246, 0.6)', // Blue
                    'rgba(16, 185, 129, 0.6)'  // Green
                ],
                borderColor: [
                    'rgba(59, 130, 246, 1)',
                    'rgba(16, 185, 129, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush
