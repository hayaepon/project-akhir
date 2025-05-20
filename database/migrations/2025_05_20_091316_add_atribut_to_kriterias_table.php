<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kriterias', function (Blueprint $table) {
            $table->enum('atribut', ['benefit', 'cost'])->after('bobot')->default('benefit');
        });
    }

    public function down(): void
    {
        Schema::table('kriterias', function (Blueprint $table) {
            $table->dropColumn('atribut');
        });
    }
};
