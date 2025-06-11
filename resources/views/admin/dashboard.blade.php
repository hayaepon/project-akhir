@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

<!-- Sambutan -->
<div class="bg-white p-6 mb-8">
    <div class="flex justify-center mb-4">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-16">
    </div>
    <h2 class="text-center text-2xl font-bold mb-4">Selamat Datang Admin</h2>
    <p class="text-center text-gray-600 mb-4">
        Selamat datang di Sistem Pendukung Keputusan Penerimaan Beasiswa STMIK Antar Bangsa.
        Kelola data pendaftar, atur bobot kriteria, dan lakukan seleksi beasiswa dengan cepat dan akurat menggunakan metode SMART.
    </p>
</div>

<!-- Ringkasan Data -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-md text-center relative">
        <div class="bg-blue-800 absolute inset-y-0 left-0 w-2 rounded-l-lg"></div>
        <div class="flex flex-col justify-center pl-4 w-full">
            <div class="text-blue-800 mb-2">
                <i class="fas fa-users fa-2x"></i>
            </div>
            <h3 class="text-2xl font-bold">{{ $jumlahPendaftar }}</h3>
            <p class="text-gray-600">Jumlah Pendaftar</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md text-center relative">
        <div class="bg-blue-800 absolute inset-y-0 left-0 w-2 rounded-l-lg"></div>
        <div class="flex flex-col justify-center pl-4 w-full">
            <div class="text-blue-800 mb-2">
                <i class="fas fa-user-graduate fa-2x"></i>
            </div>
            <h3 class="text-2xl font-bold">{{ $jumlahKipk }}</h3>
            <p class="text-gray-600">Pendaftar KIP-K</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md text-center relative">
        <div class="bg-blue-800 absolute inset-y-0 left-0 w-2 rounded-l-lg"></div>
        <div class="flex flex-col justify-center pl-4 w-full">
            <div class="text-blue-800 mb-2">
                <i class="fas fa-book-open fa-2x"></i>
            </div>
            <h3 class="text-2xl font-bold">{{ $jumlahTahfidz }}</h3>
            <p class="text-gray-600">Pendaftar Tahfidz</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md text-center relative">
        <div class="bg-blue-800 absolute inset-y-0 left-0 w-2 rounded-l-lg"></div>
        <div class="flex flex-col justify-center pl-4 w-full">
            <div class="text-blue-800 mb-2">
                <i class="fas fa-check-circle fa-2x"></i>
            </div>
            <h3 class="text-2xl font-bold">{{ $jumlahLolos }}</h3>
            <p class="text-gray-600">Lolos Seleksi</p>
        </div>
    </div>
</div>

<!-- Grafik Pendaftaran -->
<div class="bg-white p-6 rounded-lg  mb-8">
    <h3 class="text-xl font-bold mb-4 ">Grafik Pendaftaran</h3>
    <div class="h-64 rounded-lg flex items-center justify-center">
        <canvas id="grafikPendaftaran"></canvas>
    </div>
</div>

<!-- Grafik Lolos Seleksi -->
<div class="bg-white p-6 rounded-lg  mb-8">
    <h3 class="text-xl font-bold mb-4">Grafik Lolos Seleksi</h3>
    <div class="h-64  rounded-lg flex items-center justify-center">
        <canvas id="grafikLolosSeleksi"></canvas>
    </div>
</div>


@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Ambil label tahun dan data dari controller
    const tahunLabels = @json($grafikPendaftaran->pluck('tahun'));
    const dataPendaftaran = @json($grafikPendaftaran->pluck('total'));
    const dataLolos = @json($grafikLolos->pluck('total'));

    // Grafik Pendaftaran per Tahun
    new Chart(document.getElementById('grafikPendaftaran').getContext('2d'), {
        type: 'bar',
        data: {
            labels: tahunLabels,
            datasets: [{
                label: 'Jumlah Pendaftar',
                data: dataPendaftaran,
                backgroundColor: 'rgba(2, 52, 131, 0.5)',
                borderColor: 'rgba(2, 52, 131, 0.5)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            if (Number.isInteger(value)) {
                                return value;
                            }
                        }
                    }
                }
            }
        }
    });

    // Grafik Lolos Seleksi per Tahun
    new Chart(document.getElementById('grafikLolosSeleksi').getContext('2d'), {
        type: 'bar',
        data: {
            labels: tahunLabels,
            datasets: [{
                label: 'Lolos Seleksi',
                data: dataLolos,
                backgroundColor: 'rgba(236,72,153,0.5)',
                borderColor: 'rgba(236,72,153,1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            if (Number.isInteger(value)) {
                                return value;
                            }
                        }
                    }
                }
            }
        }
    });
</script>
@endpush

