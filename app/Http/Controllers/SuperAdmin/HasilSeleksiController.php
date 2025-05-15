<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\HasilSeleksi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HasilSeleksiController extends Controller
{
    public function index()
    {
        $hasilSeleksi = HasilSeleksi::orderByDesc('hasil')->get();
        return view('superadmin.hasil_seleksi.index', compact('hasilSeleksi'));
    }
}
