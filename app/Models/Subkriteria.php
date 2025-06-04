<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subkriteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'kriteria_id',
        'jenis_beasiswa_id',
        'sub_kriteria',
        'nilai',
    ];



    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    public function calonPenerimas()
    {
        return $this->belongsToMany(CalonPenerima::class, 'calon_penerima_subkriteria')
            ->withPivot('nilai', 'kriteria_id')
            ->withTimestamps();
    }

}
