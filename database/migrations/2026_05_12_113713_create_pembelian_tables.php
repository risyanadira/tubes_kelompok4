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
            // Menghubungkan ke id_supplier di tabel supplier
            $table->string('supplier_id'); 
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Definisi Foreign Key ke tabel supplier
            $table->foreign('supplier_id')->references('id_supplier')->on('supplier')->onDelete('cascade');
        });

        // 2. Tabel Detail: Detail Pembelian
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus detail dulu baru induknya agar tidak error foreign key
        Schema::dropIfExists('detail_pembelian');
        Schema::dropIfExists('pembelian');
    }
};