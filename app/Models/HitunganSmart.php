<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HitunganSmart extends Model
{
    use HasFactory;

    protected $table = 'hitungan_smarts';

    protected $fillable = [
        'calon_penerima_id',
        'jenis_beasiswa_id',
        'nilai_kriteria',
    ];

    protected $casts = [
        'nilai_kriteria' => 'array',
    ];

    // PerhitunganSmart.php (Model)
    public function calonPenerima()
    {
        return $this->belongsTo(CalonPenerima::class, 'calon_penerima_id');
    }

    public function jenisBeasiswa()
    {
        return $this->belongsTo(JenisBeasiswa::class, 'jenis_beasiswa_id');
    }

}

