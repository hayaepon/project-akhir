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
        Schema::table('calon_penerimas', function (Blueprint $table) {
            $table->unsignedBigInteger('jenis_beasiswa_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calon_penerimas', function (Blueprint $table) {
            $table->dropColumn('jenis_beasiswa_id');
        });
    }
};
