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
        // Ambil semua calon penerima
        $calonPenerimas = CalonPenerima::all();
        
        // Ambil jenis beasiswa dengan kriteria dan subkriteria
        $jenisBeasiswas = JenisBeasiswa::with('kriterias.subkriterias')->get();
        
        // Ambil semua HitunganSmart yang ada dan sudah diinput
        $hitunganSmarts = HitunganSmart::all();
        
        // Ambil ID calon penerima yang sudah diinput
        $sudahDiinput = $hitunganSmarts->pluck('calon_penerima_id')->toArray();

        // Filter berdasarkan jenis beasiswa jika ada
        $hitunganSmartsQuery = HitunganSmart::with('calonPenerima', 'jenisBeasiswa');
        
        if ($request->has('jenis_beasiswa') && $request->get('jenis_beasiswa') != '') {
            $hitunganSmartsQuery->where('jenis_beasiswa_id', $request->get('jenis_beasiswa'));
        }
        
        $hitunganSmarts = $hitunganSmartsQuery->get();

        // Decode nilai_kriteria untuk setiap hitungan
        foreach ($hitunganSmarts as $item) {
            $item->nilai_kriteria = is_string($item->nilai_kriteria) ? json_decode($item->nilai_kriteria, true) : $item->nilai_kriteria;
        }

        // Mendapatkan kriteria untuk header
        $headerKriteria = [];
        $selectedBeasiswaId = $request->get('jenis_beasiswa');
        
        if ($selectedBeasiswaId) {
            // Mendapatkan hanya kriteria untuk jenis beasiswa yang dipilih
            $selectedBeasiswa = JenisBeasiswa::with('kriterias')->find($selectedBeasiswaId);
            if ($selectedBeasiswa) {
                foreach ($selectedBeasiswa->kriterias as $kriteria) {
                    $headerKriteria[$kriteria->id] = $kriteria->kriteria;
                }
            }
        } else {
            // Mengambil semua kriteria dari seluruh jenis beasiswa
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
            'headerKriteria',
            'sudahDiinput' // Pass the list of IDs of already entered candidates
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
        // Validasi input
        $validated = $request->validate([
            'calon_penerima_id' => 'required|exists:calon_penerimas,id',
            'jenis_beasiswa_id' => 'required|exists:jenis_beasiswas,id',
            'nilai_kriteria' => 'required|array',
            'nilai_kriteria.*' => 'required|string',
        ]);

        // Mengecek apakah data sudah ada untuk calon penerima dan jenis beasiswa yang sama
        $exists = HitunganSmart::where('calon_penerima_id', $validated['calon_penerima_id'])
            ->where('jenis_beasiswa_id', $validated['jenis_beasiswa_id'])
            ->exists();

        if ($exists) {
            // Mengembalikan error jika data sudah ada
            return redirect()->back()->with('error', 'Data untuk calon penerima dan jenis beasiswa ini sudah ada.');
        }

        // Menyimpan data baru
        HitunganSmart::create([
            'calon_penerima_id' => $validated['calon_penerima_id'],
            'jenis_beasiswa_id' => $validated['jenis_beasiswa_id'],
            'nilai_kriteria' => json_encode($validated['nilai_kriteria']),
        ]);

        // Redirect dengan pesan sukses
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
        // Validasi input
        $validated = $request->validate([
            'calon_penerima_id' => 'required|exists:calon_penerimas,id',
            'jenis_beasiswa_id' => 'required|exists:jenis_beasiswas,id',
            'nilai_kriteria' => 'required|array',
            'nilai_kriteria.*' => 'required|string',
        ]);

        $HitunganSmart = HitunganSmart::findOrFail($id);

        // Update data perhitungan SMART
        $HitunganSmart->update([
            'calon_penerima_id' => $validated['calon_penerima_id'],
            'jenis_beasiswa_id' => $validated['jenis_beasiswa_id'],
            'nilai_kriteria' => json_encode($validated['nilai_kriteria']),
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('admin.perhitungan_smart.index')->with('success', 'Data perhitungan SMART berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $hitungan = HitunganSmart::findOrFail($id);
        $hitungan->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('admin.perhitungan_smart.index')->with('success', 'Data perhitungan berhasil dihapus.');
    }
}
