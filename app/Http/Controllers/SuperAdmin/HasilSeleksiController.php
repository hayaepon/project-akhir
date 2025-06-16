<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\HasilSeleksi;
use App\Models\CalonPenerima;
use App\Models\Kriteria;
use App\Models\JenisBeasiswa;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Http\Request;
use App\Models\HitunganSmart;
use App\Models\CalonPenerimaSubkriteria;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class HasilSeleksiController extends Controller
{
    public function index(Request $request)
{
    // Jalankan perhitungan SMART sebelum menampilkan data
    $this->hitung();

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

    return view('superadmin.hasil_seleksi.index', compact('hasilSeleksi', 'headerKriteria'));
}


    private function hitung()
    {
        $kriterias = Kriteria::with('subkriterias')->get();
        $calonPenerimas = CalonPenerima::with('subkriteriasTerpilih.subkriteria')->get();

        $dataHasil = [];

        foreach ($calonPenerimas as $calon) {
            $totalSkor = 0;
            $nilaiKriteria = [];

            foreach ($kriterias as $kriteria) {
                $terpilih = $calon->subkriteriasTerpilih
                    ->firstWhere('kriteria_id', $kriteria->id);

                if ($terpilih) {
                    $nilai = $terpilih->nilai;
                    $max = CalonPenerimaSubkriteria::where('kriteria_id', $kriteria->id)
                        ->max('nilai') ?: 1;
                    $normalisasi = $nilai / $max;
                    $skor = $normalisasi * $kriteria->bobot;

                    $nilaiKriteria[$kriteria->id] = round($skor, 4);
                    $totalSkor += $skor;
                } else {
                    $nilaiKriteria[$kriteria->id] = 0;
                }
            }

            $dataHasil[] = [
                'calon_penerima_id' => $calon->id,
                'jenis_beasiswa_id' => $calon->jenis_beasiswa_id,
                'hasil' => round($totalSkor, 4),
                'nilai_kriteria' => json_encode($nilaiKriteria),
                'keterangan' => null,
            ];
        }

        // Hapus data lama dan simpan hasil baru
        HasilSeleksi::query()->delete();
        foreach ($dataHasil as $hasil) {
            HasilSeleksi::create($hasil);
        }
    }

    public function export(Request $request)
{
    $format = $request->input('format');
    $beasiswaFilter = $request->get('beasiswa');

    $jenisBeasiswa = JenisBeasiswa::where('nama', $beasiswaFilter)->first();
    $kriterias = $jenisBeasiswa ? $jenisBeasiswa->kriterias : collect();

    $hasilSeleksiQuery = HasilSeleksi::with('calonPenerima');
    if ($jenisBeasiswa) {
        $hasilSeleksiQuery->where('jenis_beasiswa_id', $jenisBeasiswa->id);
    }
    $hasilSeleksi = $hasilSeleksiQuery->get();

    if ($format == 'pdf') {
        $pdf = Pdf::loadView('superadmin.hasil_seleksi.hasilseleksi_pdf', compact('hasilSeleksi', 'kriterias'));
        return $pdf->download('hasil-seleksi.pdf');
    }

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

        // Set header values
        $sheet->fromArray([$header], null, 'A1');

        // Set Header Style (Biru) dan Border
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'], // Putih
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E40AF'], // Biru
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'], // Hitam
                ],
            ],
        ];

        // Apply header style and border
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray($headerStyle);

        // Data rows
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

            // Insert data into the sheet
            $sheet->fromArray([$rowData], null, 'A' . $row++);

            // Apply border style for each row (Data)
            $sheet->getStyle('A' . ($row - 1) . ':' . $sheet->getHighestColumn() . ($row - 1))
                  ->applyFromArray([
                      'borders' => [
                          'allBorders' => [
                              'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                              'color' => ['rgb' => '000000'], // Hitam
                          ],
                      ],
                  ]);
        }

        // Auto Resize Columns
        foreach (range('A', $sheet->getHighestColumn()) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // Menyesuaikan lebar kolom otomatis
        }

        // Create Excel Writer
        $writer = new Xlsx($spreadsheet);
        $filename = 'hasil-seleksi.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    return redirect()->back()->with('error', 'Format export tidak valid.');
}

}
