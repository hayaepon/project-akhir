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

        $hasilSeleksi = $query->orderBy('hasil', 'desc')->get();

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

        // Ambil jenis beasiswa
        $jenisBeasiswa = JenisBeasiswa::where('nama', $beasiswaFilter)->first();

        // Ambil kriteria sesuai jenis beasiswa
        $kriterias = $jenisBeasiswa ? $jenisBeasiswa->kriterias : Kriteria::all();

        // Ambil hasil seleksi sesuai jenis beasiswa
        $hasilSeleksi = HasilSeleksi::with('calonPenerima')
            ->when($jenisBeasiswa, function ($query) use ($jenisBeasiswa) {
                $query->where('jenis_beasiswa_id', $jenisBeasiswa->id);
            })->get();

        // EXPORT PDF
        if ($format == 'pdf') {
            $pdf = Pdf::loadView('superadmin.hasil_seleksi.hasilseleksi_pdf', compact('hasilSeleksi', 'kriterias'));
            return $pdf->download('hasil-seleksi.pdf');
        }

        // EXPORT EXCEL
        if ($format == 'excel') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header
            $header = ['No', 'Nama Calon Penerima'];
            foreach ($kriterias as $kriteria) {
                $header[] = $kriteria->kriteria;
            }
            $header[] = 'Hasil';
            $header[] = 'Keterangan';

            $sheet->fromArray([$header], null, 'A1');

            // Style Header
            $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E40AF'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ]);

            // Data Rows
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

                $sheet->fromArray([$rowData], null, 'A' . $row);

                // Apply border to each row
                $sheet->getStyle('A' . $row . ':' . $sheet->getHighestColumn() . $row)
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['rgb' => '000000'],
                            ],
                        ],
                    ]);

                $row++;
            }

            // Auto size columns
            foreach (range('A', $sheet->getHighestColumn()) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Output file
            $writer = new Xlsx($spreadsheet);
            $filename = 'hasil-seleksi.xlsx';
            $tempFile = tempnam(sys_get_temp_dir(), $filename);
            $writer->save($tempFile);

            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
        }

        return redirect()->back()->with('error', 'Format export tidak valid.');
    }

}
;


