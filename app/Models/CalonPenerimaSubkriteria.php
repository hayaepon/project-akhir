<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalonPenerimaSubkriteria extends Model
{
    protected $table = 'calon_penerima_subkriteria';

    protected $fillable = [
        'calon_penerima_id',
        'subkriteria_id',
        'kriteria_id',
        'nilai',
    ];

    public function calonPenerima()
    {
        return $this->belongsTo(CalonPenerima::class);
    }

    public function subkriteria()
    {
        return $this->belongsTo(Subkriteria::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}
