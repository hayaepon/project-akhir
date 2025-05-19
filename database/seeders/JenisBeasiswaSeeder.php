<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisBeasiswa;

class JenisBeasiswaSeeder extends Seeder
{
    public function run()
    {
        JenisBeasiswa::firstOrCreate(['nama' => 'KIP-K']);
        JenisBeasiswa::firstOrCreate(['nama' => 'Tahfidz']);
    }
}
