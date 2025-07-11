<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameNisnToNpsnInCalonPenerimasTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('calon_penerimas', function (Blueprint $table) {
            $table->renameColumn('NISN', 'NPSN');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calon_penerimas', function (Blueprint $table) {
            $table->renameColumn('NPSN', 'NISN');
        });
    }
}
