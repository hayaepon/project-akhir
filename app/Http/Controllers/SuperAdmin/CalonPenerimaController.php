<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\CalonPenerima;
use App\Models\JenisBeasiswa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

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
            'NISN' => 'required',
            'jenis_beasiswa_id' => 'required|exists:jenis_beasiswas,id',
        ]);

        CalonPenerima::create([
            'no_pendaftaran' => $request->no_pendaftaran,
            'nama_calon_penerima' => $request->nama_calon_penerima,
            'NISN' => $request->NISN,
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
            'NISN' => 'required',
            'jenis_beasiswa_id' => 'required|exists:jenis_beasiswas,id',
        ]);

        $data->update([
            'no_pendaftaran' => $request->no_pendaftaran,
            'nama_calon_penerima' => $request->nama_calon_penerima,
            'NISN' => $request->NISN,
            'jenis_beasiswa_id' => $request->jenis_beasiswa_id,
        ]);

        return redirect()->route('calon-penerima.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        CalonPenerima::destroy($id);
        return redirect()->route('calon-penerima.index')->with('success', 'Data berhasil dihapus');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                // Lewati baris header
                if ($index === 0 || strtolower($row[0]) == 'no_pendaftaran') {
                    continue;
                }

                // Validasi kolom: minimal harus ada 4 kolom
                if (count($row) < 4) {
                    continue;
                }

                // Cari ID jenis beasiswa berdasarkan nama di kolom ke-4
                $jenisBeasiswa = JenisBeasiswa::where('nama', trim($row[3]))->first();

                // Kalau tidak ditemukan, lewati baris ini atau bisa juga throw error
                if (!$jenisBeasiswa) {
                    continue; // atau bisa: throw new \Exception('Jenis beasiswa tidak ditemukan: ' . $row[3]);
                }

                CalonPenerima::updateOrCreate(
                    ['no_pendaftaran' => $row[0]], // Cek berdasarkan no_pendaftaran
                    [
                        'nama_calon_penerima' => $row[1],
                        'NISN' => $row[2],
                        'jenis_beasiswa_id' => $jenisBeasiswa->id,
                    ]
                );
            }

            DB::commit();

            return back()->with('success', 'Data berhasil diimport dari spreadsheet.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

}
