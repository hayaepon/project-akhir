<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\HasilSeleksi;
use App\Models\CalonPenerima;
use App\Models\Kriteria;
use App\Models\JenisBeasiswa;
use App\Models\Subkriteria;
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

        return view('superadmin.hasil_seleksi.index', compact('hasilSeleksi', 'headerKriteria'));
    }


    public function hitung()
    {
        $kriterias = Kriteria::all();
        $calonPenerimas = CalonPenerima::all();

        $dataHasil = [];

        foreach ($calonPenerimas as $calon) {
            $hitungan = HitunganSmart::where('calon_penerima_id', $calon->id)->first();

            if (!$hitungan) {
                continue; // Lewati jika belum ada data di hitungan_smarts
            }

            $nilaiKriteriaJSON = json_decode($hitungan->nilai_kriteria, true);

            $totalSkor = 0;
            $skorPerKriteria = [];

            foreach ($kriterias as $kriteria) {
                $nilaiTeks = $nilaiKriteriaJSON[$kriteria->id] ?? null;

                if ($nilaiTeks) {
                    $sub = Subkriteria::where('kriteria_id', $kriteria->id)
                        ->where('sub_kriteria', $nilaiTeks)
                        ->first();

                    if ($sub) {
                        $nilai = $sub->nilai;

                        if ($kriteria->atribut === 'benefit') {
                            $max = Subkriteria::where('kriteria_id', $kriteria->id)->max('nilai') ?: 1;
                            $normalisasi = $nilai / $max;
                        } elseif ($kriteria->atribut === 'cost') {
                            $min = Subkriteria::where('kriteria_id', $kriteria->id)->min('nilai') ?: 1;
                            $normalisasi = $min / $nilai;
                        } else {
                            $normalisasi = 0;
                        }

                        $skor = $normalisasi * $kriteria->bobot;

                        $skorPerKriteria[$kriteria->id] = round($skor, 4);
                        $totalSkor += $skor;
                    } else {
                        $skorPerKriteria[$kriteria->id] = 0;
                    }
                } else {
                    $skorPerKriteria[$kriteria->id] = 0;
                }
            }


            $dataHasil[] = [
                'calon_penerima_id' => $calon->id,
                'jenis_beasiswa_id' => $calon->jenis_beasiswa_id,
                'hasil' => round($totalSkor, 4),
                'nilai_kriteria' => json_encode($skorPerKriteria),
                'keterangan' => null,
            ];
        }

        // Simpan hasil ke tabel hasil_seleksis
        HasilSeleksi::query()->delete(); // kosongkan data lama

        foreach ($dataHasil as $hasil) {
            HasilSeleksi::create($hasil);
        }

        return redirect()->route('hasil-seleksi.index', ['beasiswa' => $calonPenerimas->first()->jenisBeasiswa->nama])
            ->with('success', 'Perhitungan berhasil dilakukan.');
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
