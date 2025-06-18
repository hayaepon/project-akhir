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
    public function index()
    {
        $calonPenerimas = CalonPenerima::all();
        $jenisBeasiswas = JenisBeasiswa::with('kriterias.subkriterias')->get();
        
        // Filter hitunganSmarts based on jenis_beasiswa if filter is applied
        $hitunganSmartsQuery = HitunganSmart::with('calonPenerima', 'jenisBeasiswa');
        
        if (request()->has('jenis_beasiswa') && request()->get('jenis_beasiswa') != '') {
            $hitunganSmartsQuery->where('jenis_beasiswa_id', request()->get('jenis_beasiswa'));
        }
        
        $hitunganSmarts = $hitunganSmartsQuery->get();

        // Decode nilai_kriteria for each hitungan
        foreach ($hitunganSmarts as $item) {
            $item->nilai_kriteria = is_string($item->nilai_kriteria) ? json_decode($item->nilai_kriteria, true) : $item->nilai_kriteria;
        }

        // Get criteria based on filter or all criteria if no filter
        $selectedBeasiswaId = request()->get('jenis_beasiswa');
        $headerKriteria = [];
        
        if ($selectedBeasiswaId) {
            // Get only criteria for the selected beasiswa
            $selectedBeasiswa = JenisBeasiswa::with('kriterias')->find($selectedBeasiswaId);
            if ($selectedBeasiswa) {
                foreach ($selectedBeasiswa->kriterias as $kriteria) {
                    $headerKriteria[$kriteria->id] = $kriteria->kriteria;
                }
            }
        } else {
            // Get all unique criteria names from all beasiswa types
            foreach ($jenisBeasiswas as $jenis) {
                foreach ($jenis->kriterias as $kriteria) {
                    if (!in_array($kriteria->kriteria, $headerKriteria)) {
                        $headerKriteria[$kriteria->id] = $kriteria->kriteria;
                    }
                }
            }
        }

        return view('admin.perhitungan_smart.index', compact(
            'calonPenerimas',
            'jenisBeasiswas',
            'hitunganSmarts',
            'headerKriteria'
        ));
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

        // Decode nilai_kriteria agar bisa digunakan di view
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