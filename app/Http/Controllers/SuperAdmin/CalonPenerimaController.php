<?php

namespace App\Http\Controllers\Superadmin;
use App\Models\CalonPenerima;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisBeasiswa;

class CalonPenerimaController extends Controller
{
    public function index()
    {
        $jenisBeasiswas = JenisBeasiswa::all(); // ambil semua data beasiswa
        $dataCalonPenerima = CalonPenerima::all(); // ambil semua data calon penerima

        return view('superadmin.calon_penerima.index', compact('jenisBeasiswas', 'dataCalonPenerima'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_pendaftaran' => 'required|unique:calon_penerimas',
            'nama_calon_penerima' => 'required',
            'asal_sekolah' => 'required',
            'pilihan_beasiswa' => 'required|in:KIP-K,Tahfidz',
        ]);

        CalonPenerima::create($request->all());

        return redirect()->route('calon-penerima.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = CalonPenerima::findOrFail($id);
        return view('superadmin.calon_penerima.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = CalonPenerima::findOrFail($id);

        $request->validate([
            'no_pendaftaran' => 'required|unique:calon_penerimas,no_pendaftaran,' . $id,
            'nama_calon_penerima' => 'required',
            'asal_sekolah' => 'required',
            'pilihan_beasiswa' => 'required|in:KIP-K,Tahfidz',
        ]);

        $data->update($request->all());

        return redirect()->route('calon-penerima.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        CalonPenerima::destroy($id);
        return redirect()->route('calon-penerima.index')->with('success', 'Data berhasil dihapus');
    }
}
