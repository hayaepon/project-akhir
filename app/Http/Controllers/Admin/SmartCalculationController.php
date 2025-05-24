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

    // Decode nilai_kriteria untuk setiap hitungan agar bisa diakses di Blade
    foreach ($hitunganSmarts as $item) {
        $item->nilai_kriteria = json_decode($item->nilai_kriteria, true);
    }

    // Ambil semua nama kriteria unik dari semua jenis beasiswa
    $kriteriaSet = [];
    foreach ($jenisBeasiswas as $jenis) {
        foreach ($jenis->kriterias as $kriteria) {
            $kriteriaSet[$kriteria->id] = $kriteria->kriteria;
        }
    }

    $headerKriteria = $kriteriaSet; // key = id_kriteria, value = nama_kriteria

    return view('admin.perhitungan_smart.index', compact(
        'calonPenerimas',
        'jenisBeasiswas',
        'hitunganSmarts',
        'headerKriteria'
    ));

    
}
    // API endpoint untuk fetch kriteria & subkriteria berdasarkan jenis beasiswa
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
            'nilai_kriteria.*' => 'required|string', // ganti ke 'numeric' jika isi angka langsung
        ]);

        // Cek apakah kombinasi sudah pernah dihitung
        $exists = HitunganSmart::where('calon_penerima_id', $validated['calon_penerima_id'])
            ->where('jenis_beasiswa_id', $validated['jenis_beasiswa_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Data untuk calon penerima dan jenis beasiswa ini sudah ada.');
        }

        // Simpan ke database
        HitunganSmart::create([
            'calon_penerima_id' => $validated['calon_penerima_id'],
            'jenis_beasiswa_id' => $validated['jenis_beasiswa_id'],
            'nilai_kriteria' => json_encode($validated['nilai_kriteria']), // encode ke JSON
        ]);

        return redirect()->route('admin.perhitungan_smart.index')->with('success', 'Data berhasil disimpan.');
    }
    // Menampilkan form Edit untuk perhitungan SMART
    public function edit($id)
    {
        // Ambil data perhitungan berdasarkan ID
        $HitunganSmart = HitunganSmart::findOrFail($id);
        $calonPenerimas = CalonPenerima::all();
        $jenisBeasiswas = JenisBeasiswa::with('kriterias.subkriterias')->get();

        // Ambil nilai kriteria yang sudah ada
        $nilaiKriteria = $HitunganSmart->nilai_kriteria;

        return view('admin.perhitungan_smart.edit', compact('perhitungan', 'calonPenerimas', 'jenisBeasiswas', 'nilaiKriteria'));
    }

    // Mengupdate data perhitungan SMART
    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'calon_penerima_id' => 'required|exists:calon_penerimas,id',
            'jenis_beasiswa_id' => 'required|exists:jenis_beasiswas,id',
            'nilai_kriteria' => 'required|array',
            'nilai_kriteria.*' => 'required|string', // bisa juga 'numeric' jika nilai langsung
        ]);

        // Cari data perhitungan berdasarkan ID
        $HitunganSmart = HitunganSmart::findOrFail($id);

        // Update data perhitungan
        $HitunganSmart->update([
            'calon_penerima_id' => $validated['calon_penerima_id'],
            'jenis_beasiswa_id' => $validated['jenis_beasiswa_id'],
            'nilai_kriteria' => $validated['nilai_kriteria'], // update nilai kriteria
        ]);

        return redirect()->route('admin.perhitungan_smart.index')->with('success', 'Data perhitungan SMART berhasil diperbarui.');
    }

    // Menghapus data perhitungan SMART
    public function destroy($id)
    {
        $hitungan = HitunganSmart::findOrFail($id);
        $hitungan->delete();

        return redirect()->route('admin.perhitungan_smart.index')->with('success', 'Data perhitungan berhasil dihapus.');
    }
}
