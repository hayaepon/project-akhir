<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kriteria;

class JenisBeasiswa extends Model
{
    protected $fillable = ['nama']; // pastikan sesuai dengan kolom di migration

    public function kriterias()
    {
        return $this->hasMany(Kriteria::class, 'jenis_beasiswa_id');
        
    }
    public function index()
    {
        $dataCalonPenerima = CalonPenerima::all();
        $jenisBeasiswas = JenisBeasiswa::all();
        return view('superadmin.calon_penerima.index', compact('dataCalonPenerima', 'jenisBeasiswas'));
    }

    public function kriteria()
{
    return $this->hasMany(Kriteria::class);
}

}
