<?php

// NilaiKriteria.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiKriteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'perhitungan_smart_id',
        'kriteria_id',
        'nilai',
    ];

    public function perhitunganSmart()
    {
        return $this->belongsTo(PerhitunganSmart::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}
