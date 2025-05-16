<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Subkriteria;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class SubkriteriaController extends Controller
{
    public function index()
    {
        $subKriterias = Subkriteria::with('kriteria')->get();
        $kriterias = Kriteria::all(); // ambil semua kriteria
        return view('superadmin.subkriteria.index', compact('subKriterias', 'kriterias'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'kriteria_id' => 'required|exists:kriterias,id',
            'sub_kriteria' => 'required',
            'nilai' => 'required|numeric'
        ]);

        // Ambil beasiswa dari kriteria yang dipilih
        $kriteria = Kriteria::findOrFail($request->kriteria_id);

        Subkriteria::create([
            'kriteria_id' => $request->kriteria_id,
            'beasiswa' => $kriteria->beasiswa,  // ambil dari kriteria
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
        $subkriteria = Subkriteria::findOrFail($id);
        $subkriteria->update($request->only('sub_kriteria', 'nilai'));
        return redirect()->route('subkriteria.index')->with('success', 'Data diperbarui');
    }

    public function destroy($id)
    {
        Subkriteria::findOrFail($id)->delete();
        return redirect()->route('subkriteria.index')->with('success', 'Data dihapus');
    }
}
