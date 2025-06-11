<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalonPenerimaSubkriteriaTable extends Migration
{
    public function up()
    {
        Schema::create('calon_penerima_subkriteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calon_penerima_id')->constrained('calon_penerimas')->onDelete('cascade');
            $table->foreignId('subkriteria_id')->constrained('subkriterias')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriterias')->onDelete('cascade');
            $table->decimal('nilai', 8, 2);
            $table->timestamps();

            $table->unique(['calon_penerima_id', 'subkriteria_id', 'kriteria_id'], 'unique_calon_sub_kriteria');
        });
    }

    public function down()
    {
        Schema::dropIfExists('calon_penerima_subkriteria');
    }
}
