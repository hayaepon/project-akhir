<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\JenisBeasiswa;
use Illuminate\Http\Request;

class BeasiswaController extends Controller
{
     public function create()
    {
        // Ambil semua data jenis beasiswa dari database
        $jenisBeasiswas = JenisBeasiswa::all();

        // Kirim data ke view 'form-beasiswa'
        return view('superadmin.calon_penerima.index', compact('jenisBeasiswas'));
    }
}
