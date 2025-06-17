<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJenisBeasiswaIdToHasilSeleksisTable extends Migration
{
    public function up()
    {
        Schema::table('hasil_seleksis', function (Blueprint $table) {
            if (!Schema::hasColumn('hasil_seleksis', 'jenis_beasiswa_id')) {
                $table->unsignedBigInteger('jenis_beasiswa_id')->nullable();

                // (Opsional) Foreign key:
                // $table->foreign('jenis_beasiswa_id')->references('id')->on('jenis_beasiswas')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('hasil_seleksis', function (Blueprint $table) {
            $table->dropColumn('jenis_beasiswa_id');
        });
    }
};

