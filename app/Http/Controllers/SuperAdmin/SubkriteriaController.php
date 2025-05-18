<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Subkriteria;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use App\Models\JenisBeasiswa;

class SubkriteriaController extends Controller
{
    public function index()
    {
        $jenisBeasiswas = JenisBeasiswa::all();
        $kriterias = Kriteria::with('jenisBeasiswa')->get();
        $subKriterias = Subkriteria::with('kriteria.jenisBeasiswa')->get();

        return view('superadmin.subkriteria.index', compact('jenisBeasiswas', 'kriterias', 'subKriterias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kriteria_id' => 'required|exists:kriterias,id',
            'sub_kriteria' => 'required|string',
            'nilai' => 'required|numeric'
        ]);

        // Ambil kriteria yang dipilih untuk mengambil jenis_beasiswa_id-nya
        $kriteria = Kriteria::findOrFail($request->kriteria_id);

        // Simpan data subkriteria beserta jenis_beasiswa_id
        Subkriteria::create([
            'kriteria_id' => $request->kriteria_id,
            'jenis_beasiswa_id' => $kriteria->jenis_beasiswa_id, // tambahkan ini
            'sub_kriteria' => $request->sub_kriteria,
            'nilai' => $request->nilai,
        ]);

        return redirect()->route('subkriteria.index')->with('success', 'Sub Kriteria berhasil ditambahkan');
    }


    public function edit($id)
    {
        $subkriteria = Subkriteria::findOrFail($id);
        return view('superadmin.subkriteria.edit', compact('subkriteria'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sub_kriteria' => 'required|string',
            'nilai' => 'required|numeric',
        ]);

        $subkriteria = Subkriteria::findOrFail($id);
        $subkriteria->update([
            'sub_kriteria' => $request->sub_kriteria,
            'nilai' => $request->nilai,
        ]);

        return redirect()->route('subkriteria.index')->with('success', 'Data diperbarui');
    }

    public function destroy($id)
    {
        Subkriteria::findOrFail($id)->delete();
        return redirect()->route('subkriteria.index')->with('success', 'Data dihapus');
    }

    public function getKriteriaByBeasiswa($beasiswa_id)
    {
        $kriterias = Kriteria::where('jenis_beasiswa_id', $beasiswa_id)->get();
        return response()->json($kriterias);
    }
}
