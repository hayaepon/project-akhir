<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $fillable = ['beasiswa', 'kriteria', 'bobot'];

    public function subkriterias()
    {
        return $this->hasMany(Subkriteria::class);
    }
}

