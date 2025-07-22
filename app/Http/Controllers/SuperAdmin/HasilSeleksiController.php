<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\HasilSeleksi;
use App\Models\CalonPenerima;
use App\Models\Kriteria;
use App\Models\JenisBeasiswa;
use App\Models\Subkriteria;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class HasilSeleksiController extends Controller
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

        $hasilSeleksi = $query->get()->sortByDesc('hasil')->values();

        $hasilSeleksiKIP = $hasilSeleksi->where('calonPenerima.jenisBeasiswa.nama', 'KIP-K');
        $hasilSeleksiTahfidz = $hasilSeleksi->where('calonPenerima.jenisBeasiswa.nama', 'Tahfidz');

        $headerKriteriaKIP = $this->getHeaderKriteria('KIP-K');
        $headerKriteriaTahfidz = $this->getHeaderKriteria('Tahfidz');

        return view('superadmin.hasil_seleksi.index', compact('hasilSeleksiKIP', 'hasilSeleksiTahfidz', 'headerKriteriaKIP', 'headerKriteriaTahfidz'));
    }

    private function getHeaderKriteria($beasiswaFilter)
    {
        if ($beasiswaFilter) {
            $jenisBeasiswa = JenisBeasiswa::where('nama', $beasiswaFilter)->first();
            return $jenisBeasiswa ? $jenisBeasiswa->kriterias->pluck('kriteria', 'id') : collect();
        } else {
            return Kriteria::pluck('kriteria', 'id');
        }
    }

    public function hitung()
    {
        $kriterias = Kriteria::all();
        $jenisBeasiswas = JenisBeasiswa::all();
        $dataHasilAll = [];

        if ($kriterias->isEmpty()) {
            return back()->with('error', 'Data kriteria belum tersedia.');
        }

        foreach ($jenisBeasiswas as $jenisBeasiswa) {
            $calonPenerimas = CalonPenerima::where('jenis_beasiswa_id', $jenisBeasiswa->id)->get();

            if ($calonPenerimas->isEmpty()) continue;

            $nilaiPerKriteria = [];

            foreach ($calonPenerimas as $calon) {
                $hitungan = \App\Models\HitunganSmart::where('calon_penerima_id', $calon->id)->first();
                if (!$hitungan) continue;

                $nilaiKriteriaJSON = json_decode($hitungan->nilai_kriteria ?? '{}', true);

                foreach ($kriterias as $kriteria) {
                    $nilaiTeks = $nilaiKriteriaJSON[$kriteria->id] ?? null;

                    if ($nilaiTeks) {
                        $sub = Subkriteria::where('kriteria_id', $kriteria->id)
                            ->whereRaw('LOWER(sub_kriteria) = LOWER(?)', [$nilaiTeks])
                            ->first();

                        if ($sub) {
                            $nilaiPerKriteria[$kriteria->id][] = $sub->nilai;
                        }
                    }
                }
            }

            foreach ($calonPenerimas as $calon) {
                $hitungan = \App\Models\HitunganSmart::where('calon_penerima_id', $calon->id)->first();
                if (!$hitungan) continue;

                $nilaiKriteriaJSON = json_decode($hitungan->nilai_kriteria ?? '{}', true);
                $utilityPerKriteria = [];
                $totalSkor = 0;

                foreach ($kriterias as $kriteria) {
                    $nilaiTeks = $nilaiKriteriaJSON[$kriteria->id] ?? null;
                    $nilaiAngka = 0;

                    if ($nilaiTeks) {
                        $sub = Subkriteria::where('kriteria_id', $kriteria->id)
                            ->whereRaw('LOWER(sub_kriteria) = LOWER(?)', [$nilaiTeks])
                            ->first();

                        if ($sub) {
                            $nilaiAngka = $sub->nilai;
                        }
                    }

                    $listNilai = $nilaiPerKriteria[$kriteria->id] ?? null;

                    if ($listNilai && $nilaiAngka > 0) {
                        $max = collect($listNilai)->max() ?: 1;
                        $min = collect($listNilai)->min() ?: 1;

                        if ($max == $min) {
                            $utility = 1;
                        } elseif ($kriteria->atribut === 'benefit') {
                            $utility = ($nilaiAngka - $min) / ($max - $min);
                        } elseif ($kriteria->atribut === 'cost') {
                            $utility = ($max - $nilaiAngka) / ($max - $min);
                        } else {
                            $utility = 0;
                        }

                        $utility = round($utility, 4);
                        $utilityPerKriteria[$kriteria->id] = $utility;
                        $totalSkor += $utility * $kriteria->bobot;
                    } else {
                        $utilityPerKriteria[$kriteria->id] = 0;
                    }
                }

                $dataHasilAll[] = [
                    'calon_penerima_id' => $calon->id,
                    'jenis_beasiswa_id' => $calon->jenis_beasiswa_id,
                    'hasil' => round($totalSkor, 4),
                    'nilai_kriteria' => json_encode($utilityPerKriteria),
                ];
            }
        }

        \DB::transaction(function () use ($dataHasilAll) {
            HasilSeleksi::query()->delete();
            foreach ($dataHasilAll as $hasil) {
                HasilSeleksi::create($hasil);
            }
        });

        return redirect()->route('hasil-seleksi.index')
            ->with('success', 'Perhitungan SMART (utility 0-1 dan skor akhir) berhasil dilakukan.');
    }

    public function export(Request $request)
{
    $format = $request->input('format');
    $beasiswaFilter = $request->get('beasiswa');

    $jenisBeasiswas = JenisBeasiswa::with('kriterias')->whereIn('nama', ['KIP-K', 'Tahfidz'])->get()->keyBy('nama');
    $tables = [];

    if (!$beasiswaFilter || $beasiswaFilter == '') {
        foreach (['KIP-K', 'Tahfidz'] as $nama) {
            if (!isset($jenisBeasiswas[$nama])) continue;
            $jenis = $jenisBeasiswas[$nama];
            $kriterias = $jenis->kriterias;
            $hasilSeleksi = \App\Models\HasilSeleksi::with('calonPenerima')
                ->where('jenis_beasiswa_id', $jenis->id)
                ->orderBy('hasil', 'desc')
                ->get();
            $tables[] = [
                'judul' => $nama,
                'kriterias' => $kriterias,
                'hasilSeleksi' => $hasilSeleksi,
            ];
        }
    } else {
        if (!isset($jenisBeasiswas[$beasiswaFilter])) {
            return redirect()->back()->with('error', 'Jenis beasiswa tidak ditemukan.');
        }
        $jenis = $jenisBeasiswas[$beasiswaFilter];
        $kriterias = $jenis->kriterias;
        $hasilSeleksi = \App\Models\HasilSeleksi::with('calonPenerima')
            ->where('jenis_beasiswa_id', $jenis->id)
            ->orderBy('hasil', 'desc')
            ->get();
        $tables[] = [
            'judul' => $beasiswaFilter,
            'kriterias' => $kriterias,
            'hasilSeleksi' => $hasilSeleksi,
        ];
    }

    $fileName = 'hasil-seleksi';
    if ($beasiswaFilter) $fileName .= '-' . strtolower(str_replace(' ', '-', $beasiswaFilter));

    // EXPORT PDF
    if ($format === 'pdf') {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('superadmin.hasil_seleksi.hasilseleksi_pdf', compact('tables'));
        return $pdf->download($fileName . '.pdf');
    }

    // EXPORT EXCEL
    if ($format === 'excel') {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        foreach ($tables as $sheetIndex => $table) {
            $sheet = $sheetIndex == 0 ? $spreadsheet->getActiveSheet() : $spreadsheet->createSheet();
            $sheet->setTitle($table['judul']);

            // Header
            $header = ['No', 'Nama Calon Penerima'];
            foreach ($table['kriterias'] as $kriteria) {
                $header[] = $kriteria->kriteria;
            }
            $header[] = 'Hasil';
            $header[] = 'Ranking';

            $sheet->fromArray([$header], null, 'A1');

            // Data
            $row = 2;
            foreach ($table['hasilSeleksi'] as $index => $item) {
                $nilaiKriteria = json_decode($item->nilai_kriteria, true);
                $rowData = [
                    $index + 1,
                    $item->calonPenerima->nama_calon_penerima ?? '-',
                ];
                foreach ($table['kriterias'] as $kriteria) {
                    // Pastikan nilai 0 tetap 0, bukan kosong
                    $value = isset($nilaiKriteria[$kriteria->id]) ? $nilaiKriteria[$kriteria->id] : 0;
                    // Jika null atau string kosong, isi 0
                    $rowData[] = ($value === null || $value === '' || $value === false) ? 0 : $value;
                }
                $rowData[] = $item->hasil ?? 0;
                $rowData[] = ($index + 1);

                $colIndex = 1; // Kolom dimulai dari A
foreach ($rowData as $cellValue) {
    $coordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex) . $row;
    $sheet->setCellValueExplicit($coordinate, $cellValue, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $colIndex++;
}

                $row++;
            }

            $highestColumn = $sheet->getHighestColumn();
            $highestRow = $sheet->getHighestRow();

            // Header style: biru tua, font putih, border hitam
            $headerRange = 'A1:' . $highestColumn . '1';
            $sheet->getStyle($headerRange)->applyFromArray([
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
                        'color' => ['rgb' => '000000'], // BORDER HITAM
                    ],
                ],
            ]);

            // Border hitam untuk seluruh tabel
            $allRange = 'A1:' . $highestColumn . $highestRow;
            $sheet->getStyle($allRange)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'], // BORDER HITAM
                    ],
                ],
            ]);

            // SET SEMUA CELL RATA KIRI
            $sheet->getStyle($allRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            // Auto size kolom
            foreach (range('A', $highestColumn) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
        }

        $spreadsheet->setActiveSheetIndex(0);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName . '.xlsx')->deleteFileAfterSend(true);
    }

    return redirect()->back()->with('error', 'Format export tidak valid.');
}


}
