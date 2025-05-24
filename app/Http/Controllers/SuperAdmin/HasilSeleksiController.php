<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\HasilSeleksi;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf; // 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Http\Response;

use Illuminate\Http\Request;

class HasilSeleksiController extends Controller
{
    public function index()
    {
        $hasilSeleksi = HasilSeleksi::orderByDesc('hasil')->get();
        return view('superadmin.hasil_seleksi.index', compact('hasilSeleksi'));
    }
    public function export(Request $request)
{
    $format = $request->input('format');
    $hasilSeleksi = HasilSeleksi::all(); // Atau query sesuai kebutuhan/filter

    if ($format == 'pdf') {
        $pdf = Pdf::loadView('superadmin.hasil_seleksi.hasilseleksi_pdf', compact('hasilSeleksi'));
        return $pdf->download('hasil-seleksi.pdf');
    } elseif ($format == 'excel') {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Hasil');

        // Data
        $row = 2;
        foreach ($hasilSeleksi as $item) {
            $sheet->setCellValue('A' . $row, $item->id);
            $sheet->setCellValue('B' . $row, $item->nama);
            $sheet->setCellValue('C' . $row, $item->hasil);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'hasil-seleksi.xlsx';

        // Output ke browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    return redirect()->back();
}
}
