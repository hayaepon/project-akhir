<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hasil_seleksi_nilai_kriteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hasil_seleksi_id')->constrained('hasil_seleksis')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriterias')->onDelete('cascade');
            $table->decimal('nilai', 5, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_seleksi_nilai_kriteria');
    }
};
