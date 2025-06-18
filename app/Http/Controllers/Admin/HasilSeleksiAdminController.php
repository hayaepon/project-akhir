<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilSeleksi;
use App\Models\JenisBeasiswa;
use App\Models\Kriteria;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class HasilSeleksiAdminController extends Controller
{
     public function index(Request $request)
{

    $beasiswaFilter = $request->get('beasiswa');

    $query = HasilSeleksi::with('calonPenerima');

    if ($beasiswaFilter) {
        $query->whereHas('calonPenerima.jenisBeasiswa', function ($q) use ($beasiswaFilter) {
            $q->where('nama', $beasiswaFilter);
        });
    }

    $hasilSeleksi = $query->get();

    // Ambil header kriteria berdasarkan filter
    if ($beasiswaFilter) {
        $jenisBeasiswa = JenisBeasiswa::where('nama', $beasiswaFilter)->first();
        $headerKriteria = $jenisBeasiswa
            ? $jenisBeasiswa->kriterias->pluck('kriteria', 'id')
            : collect();
    } else {
        $headerKriteria = Kriteria::pluck('kriteria', 'id');
    }

    return view('admin.hasil_seleksi.index', compact('hasilSeleksi', 'headerKriteria'));
}

  public function export(Request $request)
    {
        $format = $request->input('format');
        $beasiswaFilter = $request->get('beasiswa');

        $jenisBeasiswa = JenisBeasiswa::where('nama', $beasiswaFilter)->first();
        $kriterias = $jenisBeasiswa ? $jenisBeasiswa->kriterias : collect();
        if ($jenisBeasiswa) {
        $kriterias = $jenisBeasiswa->kriterias;
        } else {
        $kriterias = Kriteria::all();
        }


        $hasilSeleksiQuery = HasilSeleksi::with('calonPenerima');
        if ($jenisBeasiswa) {
            $hasilSeleksiQuery->where('jenis_beasiswa_id', $jenisBeasiswa->id);
        }
        $hasilSeleksi = $hasilSeleksiQuery->get();

        if ($format == 'pdf') {
            $pdf = Pdf::loadView('admin.hasil_seleksi.hasilseleksi_pdf', compact('hasilSeleksi', 'kriterias'));
            return $pdf->download('hasil-seleksi.pdf');
        }

        if ($format == 'excel') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $header = ['No', 'Nama Calon Penerima'];
            foreach ($kriterias as $kriteria) {
                $header[] = $kriteria->kriteria;
            }
            $header[] = 'Hasil';
            $header[] = 'Keterangan';

            $sheet->fromArray([$header], null, 'A1');

            $row = 2;
            foreach ($hasilSeleksi as $index => $item) {
                $nilaiKriteria = json_decode($item->nilai_kriteria, true);
                $rowData = [
                    $index + 1,
                    $item->calonPenerima->nama_calon_penerima ?? '-',
                ];
                foreach ($kriterias as $kriteria) {
                    $rowData[] = $nilaiKriteria[$kriteria->id] ?? 0;
                }
                $rowData[] = $item->hasil;
                $rowData[] = $item->keterangan ?? '-';

                $sheet->fromArray([$rowData], null, 'A' . $row++);
            }

            $writer = new Xlsx($spreadsheet);
            $filename = 'hasil-seleksi.xlsx';
            $tempFile = tempnam(sys_get_temp_dir(), $filename);
            $writer->save($tempFile);

            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
        }

        return redirect()->back()->with('error', 'Format export tidak valid.');
    }
}

