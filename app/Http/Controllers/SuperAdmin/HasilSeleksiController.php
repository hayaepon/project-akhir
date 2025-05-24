<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\HasilSeleksi;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf; 
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
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Calon Penerima');
        $sheet->setCellValue('C1', 'Kriteria 1');
        $sheet->setCellValue('D1', 'Kriteria 2');
        $sheet->setCellValue('E1', 'Kriteria 3');
        $sheet->setCellValue('F1', 'Kriteria 4');
        $sheet->setCellValue('G1', 'Hasil');
        $sheet->setCellValue('H1', 'Keterangan');

        
        // Data
        $row = 2;
        foreach ($hasilSeleksi as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item->nama_calon_penerima);
            $sheet->setCellValue('C' . $row, $item->nilai_kriteria1);
            $sheet->setCellValue('D' . $row, $item->nilai_kriteria2);
            $sheet->setCellValue('E' . $row, $item->nilai_kriteria3);
            $sheet->setCellValue('F' . $row, $item->nilai_kriteria4);
            $sheet->setCellValue('G' . $row, $item->hasil);
            $sheet->setCellValue('H' . $row, $item->keterangan);
            $row++;
        }
        // Styling: warna biru, teks putih, bold, rata tengah
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['argb' => 'FFFFFFFF'], // Putih
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'FF1E40AF', // Biru tua (Tailwind: bg-blue-800)
        ],
    ],
];
        // Terapkan style ke seluruh header (A1:H1)
$sheet->getStyle('A1:H1')->applyFromArray($headerStyle);
// Ambil kolom terakhir yang terisi
$highestColumn = $sheet->getHighestColumn();

// Loop semua kolom dari A sampai kolom terakhir
foreach (range('A', $highestColumn) as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

        //ini nnti output ke browser
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'hasil-seleksi.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
    return redirect()->back();
}
}
