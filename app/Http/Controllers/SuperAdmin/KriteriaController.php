<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\Kriteria;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::all();
        return view('superadmin.kriteria.index', compact('kriterias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'beasiswa' => 'required',
            'kriteria' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0|max:100',
        ]);

        Kriteria::create([
            'beasiswa' => $request->beasiswa,
            'kriteria' => $request->kriteria,
            'bobot' => $request->bobot / 100, // konversi ke pecahan desimal
        ]);

        return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        return view('superadmin.kriteria.edit', compact('kriteria'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'beasiswa' => 'required',
            'kriteria' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0|max:100',
        ]);

        $kriteria = Kriteria::findOrFail($id);

        $kriteria->update([
            'beasiswa' => $request->beasiswa,
            'kriteria' => $request->kriteria,
            'bobot' => $request->bobot / 100, // konversi ke desimal juga di update
        ]);

        return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        $kriteria->delete();

        return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil dihapus.');
    }
}
