<?php

// PerhitunganSmart.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerhitunganSmart extends Model
{
    use HasFactory;

    protected $fillable = [
        'calon_penerima_id',
        'jenis_beasiswa_id',
        'nilai_kriteria',
    ];

    protected $casts = [
        'nilai_kriteria' => 'array',
    ];

    public function calonPenerima()
    {
        return $this->belongsTo(CalonPenerima::class, 'calon_penerima_id');
    }

    public function beasiswa()
    {
        return $this->belongsTo(JenisBeasiswa::class, 'jenis_beasiswa_id');
    }

    public function nilaiKriterias()
    {
        return $this->hasMany(NilaiKriteria::class);
    }
}
