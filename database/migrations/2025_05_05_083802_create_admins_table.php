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
    Schema::create('admins', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('email')->unique();
        $table->string('username')->unique();
        $table->enum('role', ['SuperAdmin', 'Admin']);
        $table->enum('status', ['Aktif', 'Non-Aktif']);
        $table->timestamps();

    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
