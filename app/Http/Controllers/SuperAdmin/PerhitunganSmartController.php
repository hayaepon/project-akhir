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
        $jenisBeasiswas = JenisBeasiswa::with('kriterias')->get();

        // Jika filter per jenis beasiswa
        if ($jenisBeasiswaId) {
            $hasilPerhitungan = HitunganSmart::with(['calonPenerima', 'jenisBeasiswa'])
                ->where('jenis_beasiswa_id', $jenisBeasiswaId)
                ->get();

            foreach ($hasilPerhitungan as $item) {
                $item->nilai_kriteria = is_string($item->nilai_kriteria)
                    ? json_decode($item->nilai_kriteria, true)
                    : $item->nilai_kriteria;
            }

            $headerKriteria = $this->getHeaderKriteria($jenisBeasiswaId, $jenisBeasiswas);

            return view('superadmin.perhitungan-smart.index', [
                'hasilPerhitungan' => $hasilPerhitungan,
                'headerKriteria' => $headerKriteria,
                'jenisBeasiswas' => $jenisBeasiswas,
                'jenisBeasiswaId' => $jenisBeasiswaId,
                'grouped' => false,
            ]);
        }
        // Jika filter "Semua Beasiswa"
        else {
            $dataPerJenis = [];
            foreach ($jenisBeasiswas as $jenis) {
                $hasil = HitunganSmart::with(['calonPenerima', 'jenisBeasiswa'])
                    ->where('jenis_beasiswa_id', $jenis->id)
                    ->get();

                foreach ($hasil as $item) {
                    $item->nilai_kriteria = is_string($item->nilai_kriteria)
                        ? json_decode($item->nilai_kriteria, true)
                        : $item->nilai_kriteria;
                }

                $headerKriteria = [];
                foreach ($jenis->kriterias as $kriteria) {
                    $headerKriteria[$kriteria->id] = $kriteria->kriteria;
                }

                $dataPerJenis[] = [
                    'jenis' => $jenis,
                    'hasilPerhitungan' => $hasil,
                    'headerKriteria' => $headerKriteria,
                ];
            }

            return view('superadmin.perhitungan-smart.index', [
                'dataPerJenis' => $dataPerJenis,
                'jenisBeasiswas' => $jenisBeasiswas,
                'jenisBeasiswaId' => null,
                'grouped' => true,
            ]);
        }
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

        foreach ($jenisBeasiswas as $jenisBeasiswa) {
            $kriterias = $jenisBeasiswa->kriterias;
            if ($kriterias->isEmpty()) continue;

            $calonPenerimas = CalonPenerima::where('jenis_beasiswa_id', $jenisBeasiswa->id)
                ->with(['subkriterias'])
                ->get();

            if ($calonPenerimas->isEmpty()) continue;

            $matriks = [];
            foreach ($calonPenerimas as $calon) {
                foreach ($kriterias as $kriteria) {
                    $nilaiSub = $calon->subkriterias->where('pivot.kriteria_id', $kriteria->id)->first();
                    $matriks[$calon->id][$kriteria->id] = $nilaiSub ? (float)$nilaiSub->pivot->nilai : 0;
                }
            }

            $maxMin = [];
            foreach ($kriterias as $kriteria) {
                $values = array_column($matriks, $kriteria->id);
                $maxMin[$kriteria->id] = [
                    'max' => max($values) ?: 1,
                    'min' => min($values) ?: 1,
                ];
            }

            foreach ($matriks as $calonId => $subKriterias) {
                $skor = 0;
                $nilaiNormal = [];

                foreach ($subKriterias as $kriteriaId => $nilai) {
                    $kriteria = $kriterias->firstWhere('id', $kriteriaId);
                    $bobot = $kriteria->bobot ?: 0;

                    $normal = 0;
                    if ($kriteria->atribut === 'benefit') {
                        $normal = $maxMin[$kriteriaId]['max'] ? $nilai / $maxMin[$kriteriaId]['max'] : 0;
                    } elseif ($kriteria->atribut === 'cost') {
                        $normal = $nilai ? $maxMin[$kriteriaId]['min'] / $nilai : 0;
                    }

                    $nilaiNormal[$kriteriaId] = round($normal, 4);
                    $skor += $normal * $bobot;
                }

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
