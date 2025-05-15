<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalonPenerima extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_pendaftaran',
        'nama_calon_penerima',
        'asal_sekolah',
        'pilihan_beasiswa',
    ];
}

