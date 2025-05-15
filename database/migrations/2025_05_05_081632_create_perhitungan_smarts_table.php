<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('perhitungan_smarts', function (Blueprint $table) {
        $table->id();
        $table->string('nama_calon_penerima');
        $table->string('pilihan_beasiswa');
        $table->double('nilai_kriteria1')->nullable();
        $table->double('nilai_kriteria2')->nullable();
        $table->double('nilai_kriteria3')->nullable();
        $table->double('nilai_kriteria4')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perhitungan_smarts');
    }
};
