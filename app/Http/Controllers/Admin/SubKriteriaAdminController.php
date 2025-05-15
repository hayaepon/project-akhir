<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\SubKriteria;

class SubKriteriaAdminController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::with('subKriterias')->get();
        $subkriterias = SubKriteria::with('kriteria')->get(); // ambil semua subkriteria dengan relasi kriteria
        return view('admin.subkriteria.index', compact('kriterias', 'subkriterias'));
    }
}
