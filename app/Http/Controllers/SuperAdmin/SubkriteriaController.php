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

        $kriteria = Kriteria::findOrFail($request->kriteria_id);

        Subkriteria::create([
            'kriteria_id' => $request->kriteria_id,
            'jenis_beasiswa_id' => $kriteria->jenis_beasiswa_id,
            'sub_kriteria' => $request->sub_kriteria,
            'nilai' => $request->nilai,
        ]);

        return redirect()->route('subkriteria.index')->with('success', 'Sub Kriteria berhasil ditambahkan');
    }

    public function edit($id)
    {
        $subKriteria = Subkriteria::findOrFail($id);
        $jenisBeasiswas = JenisBeasiswa::all();
        $kriterias = Kriteria::where('jenis_beasiswa_id', $subKriteria->jenis_beasiswa_id)->get();

        return view('superadmin.subkriteria.edit', compact('subKriteria', 'kriterias', 'jenisBeasiswas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kriteria_id' => 'required|exists:kriterias,id',
            'sub_kriteria' => 'required|string',
            'nilai' => 'required|numeric',
            'jenis_beasiswa_id' => 'required|exists:jenis_beasiswas,id',
        ]);

        $subkriteria = Subkriteria::findOrFail($id);
        $subkriteria->update([
            'kriteria_id' => $request->kriteria_id,
            'jenis_beasiswa_id' => $request->jenis_beasiswa_id,
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
