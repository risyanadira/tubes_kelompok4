<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Induk: Pembelian (Menampung info supplier & tanggal)
        Schema::create('pembelian', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            // Menghubungkan ke id_supplier (String: SUP001) di tabel supplier
            $table->string('supplier_id'); 
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Definisi Foreign Key ke tabel supplier
            $table->foreign('supplier_id')->references('id_supplier')->on('supplier')->onDelete('cascade');
        });

        // 2. Tabel Anak: Detail Pembelian (Menampung daftar barang yang dibeli)
        Schema::create('detail_pembelian', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke id di tabel pembelian
            $table->foreignId('pembelian_id')->constrained('pembelian')->onDelete('cascade');
            $table->string('nama_barang');
            $table->integer('qty');
            $table->decimal('harga', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Hapus detail dulu baru induknya agar tidak error foreign key
        Schema::dropIfExists('detail_pembelian');
        Schema::dropIfExists('pembelian');
    }
};