<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penjualan', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('penjualan_id');

            $table->unsignedBigInteger('menu_id');

            $table->integer('qty');

            $table->decimal('harga', 15, 2);

            $table->decimal('subtotal', 15, 2);

            $table->timestamps();

            // RELASI PENJUALAN
            $table->foreign('penjualan_id')
                  ->references('id')
                  ->on('penjualan')
                  ->onDelete('cascade');

            // RELASI MENU
            $table->foreign('menu_id')
                  ->references('id')
                  ->on('menu')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan');
    }
};