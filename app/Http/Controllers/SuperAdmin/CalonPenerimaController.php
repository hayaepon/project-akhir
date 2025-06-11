<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\CalonPenerima;
use App\Models\JenisBeasiswa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CalonPenerimaController extends Controller
{
    public function index()
    {
        $jenisBeasiswas = JenisBeasiswa::all();
        $dataCalonPenerima = CalonPenerima::with('jenisBeasiswa')->get(); // eager loading relasi

        return view('superadmin.calon_penerima.index', compact('jenisBeasiswas', 'dataCalonPenerima'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_pendaftaran' => 'required|unique:calon_penerimas',
            'nama_calon_penerima' => 'required',
            'asal_sekolah' => 'required',
            'jenis_beasiswa_id' => 'required|exists:jenis_beasiswas,id',
        ]);

        CalonPenerima::create([
            'no_pendaftaran' => $request->no_pendaftaran,
            'nama_calon_penerima' => $request->nama_calon_penerima,
            'asal_sekolah' => $request->asal_sekolah,
            'jenis_beasiswa_id' => $request->jenis_beasiswa_id,
        ]);

        return redirect()->route('calon-penerima.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = CalonPenerima::findOrFail($id);
        $jenisBeasiswas = JenisBeasiswa::all();

        return view('superadmin.calon_penerima.edit', compact('data', 'jenisBeasiswas'));
    }

    public function update(Request $request, $id)
    {
        $data = CalonPenerima::findOrFail($id);

        $request->validate([
            'no_pendaftaran' => 'required|unique:calon_penerimas,no_pendaftaran,' . $id,
            'nama_calon_penerima' => 'required',
            'asal_sekolah' => 'required',
            'jenis_beasiswa_id' => 'required|exists:jenis_beasiswas,id',
        ]);

        $data->update([
            'no_pendaftaran' => $request->no_pendaftaran,
            'nama_calon_penerima' => $request->nama_calon_penerima,
            'asal_sekolah' => $request->asal_sekolah,
            'jenis_beasiswa_id' => $request->jenis_beasiswa_id,
        ]);

        return redirect()->route('calon-penerima.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        CalonPenerima::destroy($id);
        return redirect()->route('calon-penerima.index')->with('success', 'Data berhasil dihapus');
    }
}
