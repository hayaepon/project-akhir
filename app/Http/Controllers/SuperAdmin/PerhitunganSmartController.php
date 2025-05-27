<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CalonPenerima;
use App\Models\JenisBeasiswa;
use App\Models\Kriteria;
use App\Models\HitunganSmart;

class PerhitunganSmartController extends Controller
{
    public function index()
    {
        $hasilPerhitungan = HitunganSmart::with(['calonPenerima', 'jenisBeasiswa'])->get();

        // Pastikan nilai_kriteria ter-decode menjadi array
        foreach ($hasilPerhitungan as $item) {
            $item->nilai_kriteria = is_string($item->nilai_kriteria)
                ? json_decode($item->nilai_kriteria, true)
                : $item->nilai_kriteria;
        }

        // Ambil semua kriteria unik dari jenis beasiswa yang berelasi
        $jenisBeasiswas = JenisBeasiswa::with('kriterias')->get();
        $headerKriteria = [];

        foreach ($jenisBeasiswas as $jenis) {
            foreach ($jenis->kriterias as $kriteria) {
                $headerKriteria[$kriteria->id] = $kriteria->kriteria;
            }
        }

        return view('superadmin.perhitungan-smart.index', compact('hasilPerhitungan', 'headerKriteria'));
    }

    public function getKriteriaByBeasiswa($jenisBeasiswaId)
    {
        $kriterias = Kriteria::with('subkriterias')
            ->where('jenis_beasiswa_id', $jenisBeasiswaId)
            ->get();

        return response()->json($kriterias);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'calon_penerima_id' => 'required|exists:calon_penerimas,id',
            'jenis_beasiswa_id' => 'required|exists:jenis_beasiswas,id',
            'nilai_kriteria' => 'required|array',
            'nilai_kriteria.*' => 'required|string',
        ]);

        $exists = HitunganSmart::where('calon_penerima_id', $validated['calon_penerima_id'])
            ->where('jenis_beasiswa_id', $validated['jenis_beasiswa_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Data untuk calon penerima dan jenis beasiswa ini sudah ada.');
        }

        HitunganSmart::create([
            'calon_penerima_id' => $validated['calon_penerima_id'],
            'jenis_beasiswa_id' => $validated['jenis_beasiswa_id'],
            'nilai_kriteria' => json_encode($validated['nilai_kriteria']),
        ]);

        return redirect()->route('admin.perhitungan_smart.index')->with('success', 'Data berhasil disimpan.');
    }
}
