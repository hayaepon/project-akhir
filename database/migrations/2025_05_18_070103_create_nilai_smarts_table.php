<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('nilai_smarts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perhitungan_smart_id')->constrained('perhitungan_smarts')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriterias')->onDelete('cascade');
            $table->foreignId('subkriteria_id')->constrained('subkriterias')->onDelete('cascade');
            $table->double('nilai')->nullable(); // jika nilai dipilih manual
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_smarts');
    }
};
