<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilSeleksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'calon_penerima_id',
        'jenis_beasiswa_id',
        'hasil',
        'nilai_kriteria',
        'keterangan',
    ];

    public function calonPenerima()
    {
        return $this->belongsTo(CalonPenerima::class);
    }

    public function jenisBeasiswa()
    {
        return $this->belongsTo(\App\Models\JenisBeasiswa::class);
    }

}

