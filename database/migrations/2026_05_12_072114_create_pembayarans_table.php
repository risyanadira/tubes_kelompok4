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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();

            // relasi ke penjualan
            $table->foreignId('penjualan_id')
                ->constrained('penjualans')
                ->onDelete('cascade');

            // metode pembayaran
            $table->string('kode_metode');

            // tanggal pembayaran
            $table->date('tgl_bayar');

            // total bayar
            $table->decimal('gross_amount',10,2);

            // status pembayaran
            $table->enum('status',['pending','lunas'])
                ->default('pending');

            // field midtrans
            $table->string('order_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('status_code')->nullable();
            $table->string('merchant_id')->nullable();
            $table->string('status_message')->nullable();
            $table->dateTime('transaction_time')->nullable();
            $table->dateTime('settlement_time')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};