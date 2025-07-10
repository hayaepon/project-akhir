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
        'NIPN',
        'jenis_beasiswa_id',
    ];

    public function jenisBeasiswa()
    {
        return $this->belongsTo(JenisBeasiswa::class, 'jenis_beasiswa_id');
    }

    public function subkriteria()
    {
        return $this->belongsTo(Subkriteria::class);
    }

    public function subkriteriasTerpilih()
    {
        return $this->hasMany(CalonPenerimaSubkriteria::class);
    }



}

    //public function nilaiKriterias()
    //{
    //    return $this->hasMany(NilaiKriteria::class, 'calon_penerima_id');
    //}



