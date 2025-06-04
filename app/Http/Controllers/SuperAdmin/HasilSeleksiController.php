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


class HasilSeleksiController extends Controller
{
    public function index(Request $request)
{
    $beasiswaFilter = $request->get('beasiswa');

    // Ambil jenis beasiswa
    $jenisBeasiswa = JenisBeasiswa::where('nama', $beasiswaFilter)->first();

    // Ambil semua kriteria untuk jenis beasiswa ini
    $kriterias = $jenisBeasiswa ? $jenisBeasiswa->kriterias : collect();

    // Ambil hasil perhitungan dari tabel hasil_seleksi
    $hasilSeleksi = HasilSeleksi::with('calonPenerima')
        ->where('jenis_beasiswa_id', $jenisBeasiswa->id ?? null)
        ->get();

    // Header tabel: nama-nama kriteria
    $headerKriteria = [];
    foreach ($kriterias as $kriteria) {
        $headerKriteria[$kriteria->id] = $kriteria->nama_kriteria;
    }

    return view('superadmin.hasil_seleksi.index', compact(
        'hasilSeleksi',
        'headerKriteria',
        'beasiswaFilter',
        'kriterias',
    ));
}


  public function hitung()
{
    $kriterias = Kriteria::with('subkriterias')->get();
    $calonPenerimas = CalonPenerima::with('subkriteriasTerpilih.subkriteria')->get();

    $dataHasil = [];

    foreach ($calonPenerimas as $calon) {
        $totalSkor = 0;
        $nilaiKriteria = [];

        foreach ($kriterias as $kriteria) {
            $terpilih = $calon->subkriteriasTerpilih->firstWhere('kriteria_id', $kriteria->id);

            if ($terpilih) {
                $nilai = $terpilih->nilai;
                $max = CalonPenerimaSubkriteria::where('kriteria_id', $kriteria->id)->max('nilai');
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
            'jenis_beasiswa_id' => $calon->jenis_beasiswa_id,  // pastikan ini ada di model CalonPenerima
            'hasil' => round($totalSkor, 4),                 // ini untuk kolom hasil
            'nilai_kriteria' => json_encode($nilaiKriteria),
            'keterangan' => null,                             // bisa isi null dulu
        ];
    }

    // Hapus data hasil seleksi lama
    HasilSeleksi::query()->delete();

    // Insert hasil baru
    foreach ($dataHasil as $hasil) {
        HasilSeleksi::create($hasil);
    }

    return redirect()->route('hasil-seleksi.index')->with('success', 'Perhitungan selesai.');
}




    public function export(Request $request)
    {
        $format = $request->input('format');
        $hasilSeleksi = HasilSeleksi::all();

        if ($format == 'pdf') {
            $pdf = Pdf::loadView('superadmin.hasil_seleksi.hasilseleksi_pdf', compact('hasilSeleksi'));
            return $pdf->download('hasil-seleksi.pdf');
        }

        if ($format == 'excel') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header
            $sheet->fromArray([
                ['No', 'Nama Calon Penerima', 'Kriteria 1', 'Kriteria 2', 'Kriteria 3', 'Kriteria 4', 'Hasil', 'Keterangan']
            ], null, 'A1');

            $row = 2;
            foreach ($hasilSeleksi as $index => $item) {
                $sheet->fromArray([
                    $index + 1,
                    $item->nama_calon_penerima,
                    $item->nilai_kriteria1,
                    $item->nilai_kriteria2,
                    $item->nilai_kriteria3,
                    $item->nilai_kriteria4,
                    $item->hasil,
                    $item->keterangan
                ], null, 'A' . $row++);
            }

            // Styling Header
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1E40AF'],
                ],
            ];
            $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

            // Auto size
            foreach (range('A', 'H') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Output ke browser
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $fileName = 'hasil-seleksi.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=\"$fileName\"");
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            exit;
        }

        return redirect()->back();
    }
}
