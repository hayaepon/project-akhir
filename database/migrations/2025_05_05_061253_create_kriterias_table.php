<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kriterias', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('jenis_beasiswa_id');
            $table->string('kriteria');
            $table->float('bobot');
            $table->timestamps();

            $table->foreign('jenis_beasiswa_id')
                ->references('id')
                ->on('jenis_beasiswas')
                ->onDelete('cascade');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('kriterias');
    }
};
