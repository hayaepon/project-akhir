<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    // Sesuaikan fillable dengan nama kolom di database
    protected $fillable = ['jenis_beasiswa_id', 'kriteria', 'bobot'];

    public function jenisBeasiswa()
    {
        return $this->belongsTo(JenisBeasiswa::class, 'jenis_beasiswa_id');
    }

    public function subkriterias()
    {
        return $this->hasMany(Subkriteria::class);
    }
}
