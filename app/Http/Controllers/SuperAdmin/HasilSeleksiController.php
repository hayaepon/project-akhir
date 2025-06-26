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

    // Urutkan dari skor tertinggi ke terendah
    $hasilSeleksi = $query->get()->sortByDesc('hasil')->values(); // penting: reset index agar ranking benar

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
    $jenisBeasiswas = JenisBeasiswa::all();
    $dataHasilAll = [];

    if ($kriterias->isEmpty()) {
        return back()->with('error', 'Data kriteria belum tersedia.');
    }

    foreach ($jenisBeasiswas as $jenisBeasiswa) {
        $calonPenerimas = CalonPenerima::where('jenis_beasiswa_id', $jenisBeasiswa->id)->get();

        if ($calonPenerimas->isEmpty()) continue;

        $nilaiPerKriteria = [];

        // Ambil nilai angka untuk min-max
        foreach ($calonPenerimas as $calon) {
            $hitungan = HitunganSmart::where('calon_penerima_id', $calon->id)->first();
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

        // Hitung utility dan skor akhir
        foreach ($calonPenerimas as $calon) {
            $hitungan = HitunganSmart::where('calon_penerima_id', $calon->id)->first();
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

                    // Hitung skor akhir
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

    // Simpan ke DB
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

    // Ambil jenis beasiswa
    $jenisBeasiswa = JenisBeasiswa::where('nama', $beasiswaFilter)->first();

    // Ambil kriteria sesuai jenis beasiswa
    $kriterias = $jenisBeasiswa ? $jenisBeasiswa->kriterias : Kriteria::all();

    // Ambil hasil seleksi sesuai jenis beasiswa dan urutkan
    $hasilSeleksi = HasilSeleksi::with('calonPenerima')
        ->when($jenisBeasiswa, function ($query) use ($jenisBeasiswa) {
            $query->where('jenis_beasiswa_id', $jenisBeasiswa->id);
        })
        ->orderBy('hasil', 'desc')
        ->get();

    // Tambahkan keterangan ranking ke setiap hasil seleksi
    foreach ($hasilSeleksi as $index => $item) {
        $item->keterangan = 'Ranking ' . ($index + 1);
    }

    // EXPORT PDF
    if ($format === 'pdf') {
        $pdf = Pdf::loadView('superadmin.hasil_seleksi.hasilseleksi_pdf', compact('hasilSeleksi', 'kriterias'));
        return $pdf->download('hasil-seleksi.pdf');
    }

    // EXPORT EXCEL
    if ($format === 'excel') {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $header = ['No', 'Nama Calon Penerima'];
        foreach ($kriterias as $kriteria) {
            $header[] = $kriteria->kriteria;
        }
        $header[] = 'Hasil';
        $header[] = 'Ranking';

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

        // Isi data
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
            $rowData[] = ($index + 1);

            $sheet->fromArray([$rowData], null, 'A' . $row);

            // Tambahkan border ke tiap baris
            $sheet->getStyle('A' . $row . ':' . $sheet->getHighestColumn() . $row)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ]);

            $row++;
        }

        // Auto size kolom
        foreach (range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Simpan dan download
        $writer = new Xlsx($spreadsheet);
        $filename = 'hasil-seleksi.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    return redirect()->back()->with('error', 'Format export tidak valid.');
}
};