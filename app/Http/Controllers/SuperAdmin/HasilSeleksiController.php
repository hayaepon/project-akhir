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
        $this->hitung();

        $beasiswaFilter = $request->get('beasiswa');

        $query = HasilSeleksi::with('calonPenerima');

        if ($beasiswaFilter) {
            $query->whereHas('calonPenerima', function ($q) use ($beasiswaFilter) {
                $q->where('jenis_beasiswa', $beasiswaFilter);
            });
        }

        $hasilSeleksi = $query->get();

        // Ambil nama kriteria sebagai header
        $headerKriteria = Kriteria::pluck('kriteria', 'id');

        return view('superadmin.hasil_seleksi.index', compact('hasilSeleksi', 'headerKriteria'));
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
                    $max = CalonPenerimaSubkriteria::where('kriteria_id', $kriteria->id)->max('nilai') ?: 1;
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
        $beasiswaFilter = $request->get('beasiswa');

        // Ambil jenis beasiswa
        $jenisBeasiswa = JenisBeasiswa::where('nama', $beasiswaFilter)->first();
        $kriterias = $jenisBeasiswa ? $jenisBeasiswa->kriterias : collect();

        // Ambil hasil seleksi berdasarkan jenis beasiswa
        $hasilSeleksi = HasilSeleksi::with('calonPenerima')
            ->where('jenis_beasiswa_id', $jenisBeasiswa->id ?? null)
            ->get();

        if ($format == 'pdf') {
            $pdf = Pdf::loadView('superadmin.hasil_seleksi.hasilseleksi_pdf', compact('hasilSeleksi', 'kriterias'));
            return $pdf->download('hasil-seleksi.pdf');
        }

        if ($format == 'excel') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Buat header dinamis
            $header = ['No', 'Nama Calon Penerima'];
            foreach ($kriterias as $kriteria) {
                $header[] = $kriteria->nama_kriteria;
            }
            $header[] = 'Hasil';
            $header[] = 'Keterangan';

            // Tulis header
            $sheet->fromArray([$header], null, 'A1');

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
                $rowData[] = $item->keterangan ?? '-';

                $sheet->fromArray([$rowData], null, 'A' . $row++);
            }

            // Simpan file
            $writer = new Xlsx($spreadsheet);
            $filename = 'hasil-seleksi.xlsx';

            // Download response
            $temp_file = tempnam(sys_get_temp_dir(), $filename);
            $writer->save($temp_file);

            return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
        }

        return redirect()->back()->with('error', 'Format export tidak valid.');
    }
}
