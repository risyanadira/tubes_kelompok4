<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('karyawan_id');

            $table->string('no_faktur');

            $table->dateTime('tgl');

            $table->string('kode_metode');

            $table->decimal('total', 15, 2)->default(0);

            $table->timestamps();

            // RELASI KARYAWAN
            $table->foreign('karyawan_id')
                  ->references('id')
                  ->on('karyawan');

            // RELASI METODE PEMBAYARAN
            $table->foreign('kode_metode')
                  ->references('kode_metode')
                  ->on('metode_pembayaran');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};