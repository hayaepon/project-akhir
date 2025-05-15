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
        Schema::create('hasil_seleksis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_calon_penerima');
            $table->decimal('nilai_kriteria1', 5, 2);
            $table->decimal('nilai_kriteria2', 5, 2);
            $table->decimal('nilai_kriteria3', 5, 2);
            $table->decimal('nilai_kriteria4', 5, 2);
            $table->decimal('hasil', 6, 3);
            $table->string('keterangan')->nullable(); // Lulus / Tidak Lulus
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('hasil_seleksis');
    }
};
