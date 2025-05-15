<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CalonPenerima;
class CalonPenerimaAdminController extends Controller
{
    public function index()
{
    $calonPenerimas = CalonPenerima::all();
    return view('admin.calon_penerima.index', compact('calonPenerimas'));
}

}
