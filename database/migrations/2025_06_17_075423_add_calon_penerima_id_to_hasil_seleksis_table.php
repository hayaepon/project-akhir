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
    Schema::table('hasil_seleksis', function (Blueprint $table) {
        $table->unsignedBigInteger('calon_penerima_id')->nullable();

        // Kalau ingin tambahkan foreign key juga:
        // $table->foreign('calon_penerima_id')->references('id')->on('calon_penerimas')->onDelete('cascade');
    });
}
    /**
     * Reverse the migrations.
     */
public function down()
{
    Schema::table('hasil_seleksis', function (Blueprint $table) {
        $table->dropColumn('calon_penerima_id');
        // atau jika pakai foreign key:
        // $table->dropForeign(['calon_penerima_id']);
        // $table->dropColumn('calon_penerima_id');
    });
}
};
