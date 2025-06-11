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
        Schema::create('nilai_calon_penerima', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calon_penerima_id')->constrained()->onDelete('cascade');
            $table->foreignId('subkriteria_id')->constrained()->onDelete('cascade');
            $table->float('nilai');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_calon_penerima');
    }
};
