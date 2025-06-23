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
        if (!Schema::hasColumn('hasil_seleksis', 'calon_penerima_id')) {
            $table->unsignedBigInteger('calon_penerima_id')->nullable();
        }
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
