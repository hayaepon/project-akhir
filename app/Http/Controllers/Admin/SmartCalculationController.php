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
        $hitunganSmarts = HitunganSmart::with('calonPenerima', 'jenisBeasiswa')->get();

        // Ambil semua nama kriteria unik dari jenis beasiswa yang aktif
        $kriteriaSet = [];
        foreach ($jenisBeasiswas as $jenis) {
            foreach ($jenis->kriterias as $kriteria) {
                $kriteriaSet[$kriteria->id] = $kriteria->kriteria;
            }
        }

        $headerKriteria = array_values($kriteriaSet); // hanya ambil nama-nya

        return view('admin.perhitungan_smart.index', compact(
            'calonPenerimas',
            'jenisBeasiswas',
            'hitunganSmarts',
            'headerKriteria'
        ));
    }

    public function getKriteriaByBeasiswa($jenis_beasiswa_id)
    {
        // API endpoint untuk fetch kriteria & subkriteria sesuai beasiswa (AJAX)
        $kriterias = JenisBeasiswa::findOrFail($jenis_beasiswa_id)->kriterias()->with('subkriterias')->get();

        return response()->json($kriterias);
    }

    // Simpan data perhitungan beserta nilai kriteria
    public function store(Request $request)
    {
        $validated = $request->validate([
            'calon_penerima_id' => 'required|exists:calon_penerimas,id',
            'jenis_beasiswa_id' => 'required|exists:jenis_beasiswas,id',
            'nilai_kriteria' => 'required|array',
            'nilai_kriteria.*' => 'required|string', // bisa juga 'numeric' jika nilai langsung
        ]);

        // Pastikan tidak ada data duplikat untuk calon & beasiswa yang sama
        $exists = HitunganSmart::where('calon_penerima_id', $validated['calon_penerima_id'])
            ->where('jenis_beasiswa_id', $validated['jenis_beasiswa_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Data untuk calon penerima dan jenis beasiswa ini sudah ada.');
        }

        // Simpan ke tabel hitungan_smarts
        HitunganSmart::create([
            'calon_penerima_id' => $validated['calon_penerima_id'],
            'jenis_beasiswa_id' => $validated['jenis_beasiswa_id'],
            'nilai_kriteria' => $validated['nilai_kriteria'], // sudah dalam bentuk array
        ]);

        return redirect()->route('admin.perhitungan_smart.index')->with('success', 'Data berhasil disimpan.');
    }


    public function getKriteria($jenis_beasiswa_id)
    {
        $kriterias = Kriteria::with('subkriterias')
            ->where('jenis_beasiswa_id', $jenis_beasiswa_id)
            ->get();

        return response()->json($kriterias);
    }

    public function destroy($id)
    {
        $hitungan = HitunganSmart::findOrFail($id);
        $hitungan->delete();

        return redirect()->route('admin.perhitungan_smart.index')->with('success', 'Data perhitungan berhasil dihapus.');
    }




}
