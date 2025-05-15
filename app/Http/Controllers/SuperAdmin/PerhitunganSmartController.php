<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PerhitunganSmart;

class PerhitunganSmartController extends Controller
{
    public function index()
    {
        $dataCalonPenerima = PerhitunganSmart::all();
        return view('superadmin.perhitungan-smart.index', compact('dataCalonPenerima'));
    }

    public function hitung(Request $request)
    {
        // Logika perhitungan SMART bisa kamu tambahkan di sini
        // Misalnya mengupdate nilai berdasarkan bobot kriteria

        // Simulasi update nilai
        $data = PerhitunganSmart::all();
        foreach ($data as $item) {
            $item->update([
                'nilai_kriteria1' => rand(70, 100),
                'nilai_kriteria2' => rand(70, 100),
                'nilai_kriteria3' => rand(70, 100),
                'nilai_kriteria4' => rand(70, 100),
            ]);
        }

        return redirect()->route('perhitungan-smart.index')->with('success', 'Perhitungan SMART berhasil dilakukan.');
    }
}
