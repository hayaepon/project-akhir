<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CalonPenerima;
use App\Models\JenisBeasiswa;
use App\Models\Kriteria;
use App\Models\HitunganSmart;

class SmartCalculationController extends Controller
{
    public function index(Request $request)
    {
        $calonPenerimas = CalonPenerima::all();
        $jenisBeasiswas = JenisBeasiswa::with('kriterias.subkriterias')->get();
        $allHitunganSmarts = HitunganSmart::all();
        $sudahDiinput = $allHitunganSmarts->pluck('calon_penerima_id')->toArray();

        $selectedBeasiswaId = $request->get('jenis_beasiswa');
        $grouped = false;
        $dataPerJenis = [];
        $headerKriteria = [];
        $hitunganSmarts = collect();

        if ($selectedBeasiswaId) {
            // Filter hanya satu jenis beasiswa
            $jenis = $jenisBeasiswas->firstWhere('id', $selectedBeasiswaId);
            if ($jenis) {
                $hitunganSmarts = HitunganSmart::with('calonPenerima', 'jenisBeasiswa')
                    ->where('jenis_beasiswa_id', $jenis->id)
                    ->get();

                foreach ($hitunganSmarts as $item) {
                    $item->nilai_kriteria = is_string($item->nilai_kriteria) ? json_decode($item->nilai_kriteria, true) : $item->nilai_kriteria;
                }

                foreach ($jenis->kriterias as $kriteria) {
                    $headerKriteria[$kriteria->id] = $kriteria->kriteria;
                }
            }
            // Kirim variabel untuk mode satu tabel
            return view('admin.perhitungan_smart.index', compact(
                'calonPenerimas',
                'jenisBeasiswas',
                'hitunganSmarts',
                'headerKriteria',
                'sudahDiinput',
                'grouped'
            ));
        } else {
            // Semua beasiswa: grouped mode
            $grouped = true;
            foreach ($jenisBeasiswas as $jenis) {
                $hitunganSmarts = HitunganSmart::with('calonPenerima', 'jenisBeasiswa')
                    ->where('jenis_beasiswa_id', $jenis->id)
                    ->get();

                foreach ($hitunganSmarts as $item) {
                    $item->nilai_kriteria = is_string($item->nilai_kriteria) ? json_decode($item->nilai_kriteria, true) : $item->nilai_kriteria;
                }

                $headerKriteriaJenis = [];
                foreach ($jenis->kriterias as $kriteria) {
                    $headerKriteriaJenis[$kriteria->id] = $kriteria->kriteria;
                }

                $dataPerJenis[] = [
                    'jenis' => $jenis,
                    'hasilPerhitungan' => $hitunganSmarts,
                    'headerKriteria' => $headerKriteriaJenis,
                ];
            }
            // Kirim variabel untuk mode grouped
            return view('admin.perhitungan_smart.index', compact(
                'calonPenerimas',
                'jenisBeasiswas',
                'dataPerJenis',
                'grouped',
                'sudahDiinput'
            ));
        }
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

    public function edit($id)
    {
        $HitunganSmart = HitunganSmart::findOrFail($id);
        $calonPenerimas = CalonPenerima::all();
        $jenisBeasiswas = JenisBeasiswa::with('kriterias.subkriterias')->get();

        $nilaiKriteria = is_string($HitunganSmart->nilai_kriteria) ? json_decode($HitunganSmart->nilai_kriteria, true) : $HitunganSmart->nilai_kriteria;

        return view('admin.perhitungan_smart.edit', compact('HitunganSmart', 'calonPenerimas', 'jenisBeasiswas', 'nilaiKriteria'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'calon_penerima_id' => 'required|exists:calon_penerimas,id',
            'jenis_beasiswa_id' => 'required|exists:jenis_beasiswas,id',
            'nilai_kriteria' => 'required|array',
            'nilai_kriteria.*' => 'required|string',
        ]);

        $HitunganSmart = HitunganSmart::findOrFail($id);

        $HitunganSmart->update([
            'calon_penerima_id' => $validated['calon_penerima_id'],
            'jenis_beasiswa_id' => $validated['jenis_beasiswa_id'],
            'nilai_kriteria' => json_encode($validated['nilai_kriteria']),
        ]);

        return redirect()->route('admin.perhitungan_smart.index')->with('success', 'Data perhitungan SMART berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $hitungan = HitunganSmart::findOrFail($id);
        $hitungan->delete();

        return redirect()->route('admin.perhitungan_smart.index')->with('success', 'Data perhitungan berhasil dihapus.');
    }
}
