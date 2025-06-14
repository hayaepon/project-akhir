<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CalonPenerima;
use App\Models\JenisBeasiswa;
use App\Models\HasilSeleksi;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Data ringkasan
        $jumlahPendaftar = CalonPenerima::count();
        $jumlahJenisBeasiswa = JenisBeasiswa::count();
        $kipkId = JenisBeasiswa::where('nama', 'KIP-K')->value('id');
        $tahfidzId = JenisBeasiswa::where('nama', 'Tahfidz')->value('id');

        $jumlahKipk = CalonPenerima::where('jenis_beasiswa_id', $kipkId)->count();
        $jumlahTahfidz = CalonPenerima::where('jenis_beasiswa_id', $tahfidzId)->count();
        $jumlahLolos = HasilSeleksi::where('keterangan', 'lolos')->count();

        // Grafik pendaftaran
        $grafikPendaftaran = CalonPenerima::selectRaw('YEAR(created_at) as tahun, COUNT(*) as total')
            ->whereNotNull('created_at')
            ->groupBy('tahun')
            ->orderBy('tahun')
            ->get();

        // Grafik lolos
        $grafikLolos = HasilSeleksi::selectRaw('YEAR(created_at) as tahun, COUNT(*) as total')
            ->where('keterangan', 'lolos')
            ->whereNotNull('created_at')
            ->groupBy('tahun')
            ->orderBy('tahun')
            ->get();

        return view('admin.dashboard', [
            'jumlahPendaftar' => $jumlahPendaftar,
            'jumlahJenisBeasiswa' => $jumlahJenisBeasiswa,
            'jumlahKipk' => $jumlahKipk,
            'jumlahTahfidz' => $jumlahTahfidz,
            'jumlahLolos' => $jumlahLolos,
            'grafikPendaftaran' => $grafikPendaftaran,
            'grafikLolos' => $grafikLolos,
        ]);
    }
}
