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
        // 1. Tabel Utama: Pembelian
        Schema::create('pembelian', function (Blueprint $table) {

            $table->id();

            $table->date('tanggal');

            // relasi ke suppliers
            $table->string('supplier_id');

            $table->decimal('total_harga', 15, 2)
                  ->default(0);

            $table->text('keterangan')
                  ->nullable();

            $table->timestamps();

            // foreign key
            $table->foreign('supplier_id')
                  ->references('id')
                  ->on('suppliers')
                  ->onDelete('cascade');
        });

        // 2. Detail Pembelian
        Schema::create('detail_pembelian', function (Blueprint $table) {

            $table->id();

            $table->foreignId('pembelian_id')
                  ->constrained('pembelian')
                  ->onDelete('cascade');

            $table->string('nama_barang');

            $table->integer('qty');

            $table->decimal('harga', 15, 2);

            $table->decimal('subtotal', 15, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pembelian');

        Schema::dropIfExists('pembelian');
    }
};