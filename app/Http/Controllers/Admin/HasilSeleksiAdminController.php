<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilSeleksi;
use Illuminate\Http\Request;

class HasilSeleksiAdminController extends Controller
{
    public function index(Request $request)
    {
        // Filter berdasarkan jenis beasiswa jika ada
        $beasiswa = $request->get('beasiswa');
        $query = HasilSeleksi::query();

        if ($beasiswa) {
            $query->where('beasiswa', $beasiswa);
        }

        $hasilSeleksi = $query->get();

        return view('admin.Hasil_Seleksi.index', compact('hasilSeleksi'));
    }

   
}

