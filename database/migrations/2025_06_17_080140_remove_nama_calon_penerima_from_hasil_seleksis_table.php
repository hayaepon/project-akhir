<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('hasil_seleksis', function (Blueprint $table) {
        $table->dropColumn('nama_calon_penerima');
    });
}

public function down()
{
    Schema::table('hasil_seleksis', function (Blueprint $table) {
        $table->string('nama_calon_penerima');
    });
}

};
