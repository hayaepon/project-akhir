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
        Schema::create('hitungan_smarts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calon_penerima_id')->constrained('calon_penerimas')->onDelete('cascade');
            $table->foreignId('jenis_beasiswa_id')->constrained('jenis_beasiswas')->onDelete('cascade');
            $table->json('nilai_kriteria'); // Simpan nilai kriteria dalam format JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hitungan_smarts');
    }
};
