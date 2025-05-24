<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\HasilSeleksi;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf; // 
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
        // Export Excel bisa pakai Laravel Excel (lihat catatan bawah)
        // return Excel::download(new HasilSeleksiExport, 'hasil-seleksi.xlsx');
    }

    return redirect()->back();
}
}
