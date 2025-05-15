<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kriteria;

class KriteriaAdminController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::all();
        return view('admin.kriteria.index', compact('kriterias'));
    }
}
