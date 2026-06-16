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
        Schema::create('penggunaan_bb', function (Blueprint $table) {

            $table->id();

            // GANTI INI
            $table->string('id_bahan_baku');

            $table->string('kode_penggunaan');

            $table->string('nama_produk_jadi');

            $table->decimal('jumlah_penggunaan', 15, 2)
                  ->default(0);

            $table->string('satuan');

            $table->date('tanggal_penggunaan');

            $table->text('keterangan')
                  ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggunaan_bb');
    }
};