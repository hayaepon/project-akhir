<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisBeasiswa extends Model
{
    protected $fillable = ['nama']; // pastikan sesuai dengan kolom di migration

    public function kriterias()
    {
        return $this->hasMany(Kriteria::class, 'jenis_beasiswa_id');
        return $this->belongsToMany(Kriteria::class, 'jenis_beasiswa_kriteria');
    }
    public function index()
{
    $dataCalonPenerima = CalonPenerima::all();
    $jenisBeasiswas = JenisBeasiswa::all();
    return view('superadmin.calon_penerima.index', compact('dataCalonPenerima', 'jenisBeasiswas'));
}
}
