<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CalonPenerima;
use App\Models\JenisBeasiswa;
use App\Models\Kriteria;
use App\Models\PerhitunganSmart;
class SmartCalculationController extends Controller
{
    public function index()
    {
        $calonPenerimas = CalonPenerima::all();
        $jenisBeasiswas = JenisBeasiswa::with('kriterias.subkriterias')->get();
        $perhitunganSmarts = PerhitunganSmart::with('calonPenerima', 'jenisBeasiswa')->get();

        return view('admin.perhitungan_smart.index', compact('calonPenerimas', 'jenisBeasiswas', 'perhitunganSmarts'));
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
            'nilai_kriteria' => 'required|array', // format: [kriteria_id => nilai]
            'nilai_kriteria.*' => 'required|numeric',
        ]);

        // Cek apakah data sudah ada untuk kombinasi ini (opsional)
        $existing = PerhitunganSmart::where('calon_penerima_id', $validated['calon_penerima_id'])
            ->where('jenis_beasiswa_id', $validated['jenis_beasiswa_id'])
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Data perhitungan untuk calon dan beasiswa ini sudah ada.');
        }

        PerhitunganSmart::create([
            'calon_penerima_id' => $validated['calon_penerima_id'],
            'jenis_beasiswa_id' => $validated['jenis_beasiswa_id'],
            'nilai_kriteria' => $validated['nilai_kriteria'],
        ]);

        return redirect()->route('admin.perhitungan_smart.index')->with('success', 'Data perhitungan berhasil disimpan.');
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
        $perhitungan = PerhitunganSmart::findOrFail($id);

        // Hapus relasi nilai_kriterias jika diperlukan
        $perhitungan->nilaiKriterias()->delete();

        // Hapus data utama
        $perhitungan->delete();

        return redirect()->route('perhitungan_smart.index')->with('success', 'Data perhitungan berhasil dihapus.');
    }


}
