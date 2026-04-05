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
        Schema::create('Bahan_Baku', function (Blueprint $table) {
            $table->id();
            $table->string('id_bahan_baku');
            $table->string('nama_bahan_baku');
            $table->string('harga_bahan_baku');
            $table->string('stok_bahan_baku');
            $table->foreignId('id_suplier')->nullable()->constrained('supliers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Bahan_Baku');
    }
};
