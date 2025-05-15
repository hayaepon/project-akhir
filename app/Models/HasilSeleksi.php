<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilSeleksi extends Model
{
    use HasFactory;

    protected $table = 'hasil_seleksis';

    protected $fillable = [
        'nama_calon_penerima',
        'nilai_kriteria1',
        'nilai_kriteria2',
        'nilai_kriteria3',
        'nilai_kriteria4',
        'hasil',
        'keterangan',
    ];
}

