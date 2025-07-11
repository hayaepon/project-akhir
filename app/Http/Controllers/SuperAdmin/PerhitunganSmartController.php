<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CalonPenerima;
use App\Models\JenisBeasiswa;
use App\Models\Kriteria;
use App\Models\HitunganSmart;
use App\Models\Subkriteria;

class PerhitunganSmartController extends Controller
{
    public function index(Request $request)
    {
        $jenisBeasiswaId = $request->query('jenis_beasiswa');

        // Ambil hasil perhitungan berdasarkan jenis beasiswa yang difilter
        $hasilPerhitungan = HitunganSmart::with(['calonPenerima', 'jenisBeasiswa'])
            ->when($jenisBeasiswaId, function ($query) use ($jenisBeasiswaId) {
                $query->where('jenis_beasiswa_id', $jenisBeasiswaId);
            })
            ->get();

        // Decode nilai kriteria (json)
        foreach ($hasilPerhitungan as $item) {
            $item->nilai_kriteria = is_string($item->nilai_kriteria)
                ? json_decode($item->nilai_kriteria, true)
                : $item->nilai_kriteria;
        }

        // Ambil jenis beasiswa untuk filter
        $jenisBeasiswas = JenisBeasiswa::with('kriterias')->get();

        // Ambil header kriteria berdasarkan jenis beasiswa yang difilter
        $headerKriteria = $this->getHeaderKriteria($jenisBeasiswaId, $jenisBeasiswas);

        return view('superadmin.perhitungan-smart.index', compact('hasilPerhitungan', 'headerKriteria', 'jenisBeasiswas', 'jenisBeasiswaId'));
    }

    // Fungsi untuk mendapatkan header kriteria berdasarkan jenis beasiswa
    private function getHeaderKriteria($jenisBeasiswaId, $jenisBeasiswas)
    {
        $headerKriteria = [];
        if ($jenisBeasiswaId) {
            $jenisDipilih = $jenisBeasiswas->firstWhere('id', $jenisBeasiswaId);
            if ($jenisDipilih) {
                foreach ($jenisDipilih->kriterias as $kriteria) {
                    $headerKriteria[$kriteria->id] = $kriteria->kriteria;
                }
            }
        } else {
            foreach ($jenisBeasiswas as $jenis) {
                foreach ($jenis->kriterias as $kriteria) {
                    $headerKriteria[$kriteria->id] = $kriteria->kriteria;
                }
            }
        }
        return $headerKriteria;
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

        // Check if the record already exists
        $exists = HitunganSmart::where('calon_penerima_id', $validated['calon_penerima_id'])
            ->where('jenis_beasiswa_id', $validated['jenis_beasiswa_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Data untuk calon penerima dan jenis beasiswa ini sudah ada.');
        }

        // Store the SMART calculation data
        HitunganSmart::create([
            'calon_penerima_id' => $validated['calon_penerima_id'],
            'jenis_beasiswa_id' => $validated['jenis_beasiswa_id'],
            'nilai_kriteria' => json_encode($validated['nilai_kriteria']),
        ]);

        return redirect()->route('superadmin.perhitungan-smart.index')->with('success', 'Data berhasil disimpan.');
    }

    public function hitung(Request $request)
    {
        $jenisBeasiswas = JenisBeasiswa::with('kriterias')->get();

        // Loop through each jenis beasiswa
        foreach ($jenisBeasiswas as $jenisBeasiswa) {
            $kriterias = $jenisBeasiswa->kriterias;
            if ($kriterias->isEmpty()) continue;

            $calonPenerimas = CalonPenerima::where('jenis_beasiswa_id', $jenisBeasiswa->id)
                ->with(['subkriterias']) // Eager load subkriteria
                ->get();

            if ($calonPenerimas->isEmpty()) continue;

            $matriks = [];
            foreach ($calonPenerimas as $calon) {
                foreach ($kriterias as $kriteria) {
                    $nilaiSub = $calon->subkriterias->where('pivot.kriteria_id', $kriteria->id)->first();
                    $matriks[$calon->id][$kriteria->id] = $nilaiSub ? (float)$nilaiSub->pivot->nilai : 0;
                }
            }

            // Calculate max and min for normalization
            $maxMin = [];
            foreach ($kriterias as $kriteria) {
                $values = array_column($matriks, $kriteria->id);
                $maxMin[$kriteria->id] = [
                    'max' => max($values) ?: 1,
                    'min' => min($values) ?: 1,
                ];
            }

            // Calculate normalized scores and final score
            foreach ($matriks as $calonId => $subKriterias) {
                $skor = 0;
                $nilaiNormal = [];

                foreach ($subKriterias as $kriteriaId => $nilai) {
                    // Get kriteria object to fetch weight and attribute (benefit/cost)
                    $kriteria = $kriterias->firstWhere('id', $kriteriaId);
                    $bobot = $kriteria->bobot ?: 0;

                    $normal = 0;
                    if ($kriteria->atribut === 'benefit') {
                        // Normalize benefit: value / max value of the criteria
                        $normal = $maxMin[$kriteriaId]['max'] ? $nilai / $maxMin[$kriteriaId]['max'] : 0;
                    } elseif ($kriteria->atribut === 'cost') {
                        // Normalize cost: min value / value
                        $normal = $nilai ? $maxMin[$kriteriaId]['min'] / $nilai : 0;
                    }

                    $nilaiNormal[$kriteriaId] = round($normal, 4);
                    $skor += $normal * $bobot; // Final score: weight * normalized value
                }

                // Save the final results
                HitunganSmart::updateOrCreate(
                    [
                        'calon_penerima_id' => $calonId,
                        'jenis_beasiswa_id' => $jenisBeasiswa->id,
                    ],
                    [
                        'nilai_kriteria' => json_encode($nilaiNormal),
                        'skor_akhir' => round($skor, 4),
                    ]
                );
            }
        }

        return redirect()->route('hasil_seleksi.index')->with('success', 'Perhitungan SMART berhasil dilakukan.');
    }
}
