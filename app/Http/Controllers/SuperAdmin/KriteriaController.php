<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\Kriteria;
use App\Models\JenisBeasiswa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index()
    {
        // Eager loading agar data beasiswa ikut ter-load
        $kriterias = Kriteria::with('jenisBeasiswa')->get();
        $jenisBeasiswas = JenisBeasiswa::all();

        return view('superadmin.kriteria.index', compact('kriterias', 'jenisBeasiswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_beasiswa_id' => 'required|exists:jenis_beasiswas,id',
            'kriteria' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0|max:100',
            'atribut' => 'required|in:benefit,cost',
        ]);

        Kriteria::create([
            'jenis_beasiswa_id' => $request->jenis_beasiswa_id,
            'kriteria' => $request->kriteria,
            'bobot' => $request->bobot / 100, 
            'atribut' => $request->atribut,
        ]);

        return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        $jenisBeasiswas = JenisBeasiswa::all();
        return view('superadmin.kriteria.edit', compact('kriteria', 'jenisBeasiswas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_beasiswa_id' => 'required|exists:jenis_beasiswas,id',
            'kriteria' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0|max:100',
            'atribut' => 'required|in:benefit,cost',
        ]);

        $kriteria = Kriteria::findOrFail($id);

        $kriteria->update([
            'jenis_beasiswa_id' => $request->jenis_beasiswa_id,
            'kriteria' => $request->kriteria,
            'bobot' => $request->bobot / 100,
            'atribut' => $request->atribut, 
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
