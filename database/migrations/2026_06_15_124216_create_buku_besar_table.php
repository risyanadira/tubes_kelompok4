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
        Schema::create('buku_besar', function (Blueprint $blueprint) {
            $blueprint->id(); // Membuat kolom 'id' otomatis (Primary Key)
            $blueprint->date('tanggal'); // Tanggal transaksi
            $blueprint->string('kode_akun'); // Kode akun (misal: 111, 112)
            $blueprint->string('keterangan')->nullable(); // Keterangan transaksi (boleh kosong)
            
            // Menggunakan bigint karena nominal uang bisa besar (default 0)
            $blueprint->bigInteger('debit')->default(0); 
            $blueprint->bigInteger('kredit')->default(0);
            $blueprint->bigInteger('saldo')->default(0);
            
            $blueprint->timestamps(); // Membuat kolom 'created_at' dan 'updated_at' otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_besar');
    }
};