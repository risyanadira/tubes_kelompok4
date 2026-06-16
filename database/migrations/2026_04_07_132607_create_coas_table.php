<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
<<<<<<< HEAD
    // Tambahkan kondisi if ini
    if (!Schema::hasTable('coas')) {
        Schema::create('coas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_akun');
            $table->string('nama_akun');
            $table->string('header_akun');
            $table->timestamps();
        });
    }
=======
    Schema::create('coas', function (Blueprint $table) {
        $table->id();
        $table->string('kode_akun')->unique();
        $table->string('nama_akun');
        $table->string('header_akun');
        $table->timestamps();
    });
>>>>>>> origin/indy
}
   
};
