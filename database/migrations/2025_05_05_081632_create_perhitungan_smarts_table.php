<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('perhitungan_smarts', function (Blueprint $table) {
            $table->id();
            $table->string('nama_calon_penerima');
            $table->foreignId('jenis_beasiswa_id')->constrained('jenis_beasiswas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perhitungan_smarts');
    }
};
