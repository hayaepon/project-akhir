<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\CalonPenerima;
use App\Models\JenisBeasiswa;
use App\Models\HasilSeleksi;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        // Data ringkasan
        $jumlahPendaftar = CalonPenerima::count();
        $jumlahJenisBeasiswa = JenisBeasiswa::count();
        $jumlahKipk = CalonPenerima::where('pilihan_beasiswa', 'KIP-K')->count();
        $jumlahTahfiz = CalonPenerima::where('pilihan_beasiswa', 'Tahfiz')->count();
        $jumlahLolos = HasilSeleksi::where('keterangan', 'lolos')->count();

        // Data grafik pendaftaran per tahun
        $grafikPendaftaran = CalonPenerima::selectRaw('YEAR(created_at) as tahun, COUNT(*) as total')
            ->groupBy('tahun')
            ->orderBy('tahun')
            ->get();


        // Data grafik lolos seleksi per tahun
        $grafikLolos = HasilSeleksi::selectRaw('YEAR(created_at) as tahun, COUNT(*) as total')
            ->where('keterangan', 'lolos')
            ->groupBy('tahun')
            ->orderBy('tahun')
            ->get();

        return view('superadmin.dashboard', [
            'jumlahPendaftar' => $jumlahPendaftar,
            'jumlahJenisBeasiswa' => $jumlahJenisBeasiswa,
            'jumlahKipk' => $jumlahKipk,
            'jumlahTahfiz' => $jumlahTahfiz,
            'jumlahLolos' => $jumlahLolos,
            'grafikPendaftaran' => $grafikPendaftaran,
            'grafikLolos' => $grafikLolos,
        ]);
    }
}
