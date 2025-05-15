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
        return view('superadmin.subkriteria.index', compact('subKriterias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'beasiswa' => 'required',
            'kriteria' => 'required',
            'sub_kriteria' => 'required',
            'nilai' => 'required|numeric'
        ]);

        $kriteria = Kriteria::firstOrCreate([
            'kriteria' => $request->kriteria,
            'beasiswa' => $request->beasiswa
        ]);

        Subkriteria::create([
            'kriteria_id' => $kriteria->id,
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
