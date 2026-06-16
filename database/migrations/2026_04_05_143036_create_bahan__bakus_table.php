<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahan_baku', function (Blueprint $table) {
            $table->id();
            $table->string('id_bahan_baku');
            $table->string('nama_bahan_baku');
<<<<<<< HEAD
            $table->integer('harga_bahan_baku');
            $table->integer('stok_bahan_baku')->default(0);
            $table->integer('stok_minimum')->default(0);
            $table->string('satuan'); // Contoh: kg, gram, pcs, liter
            $table->date('tanggal_expired')->nullable();
    $table->timestamps();
=======
            $table->string('harga_bahan_baku');
            $table->string('stok_bahan_baku');

            // 🔥 WAJIB INI (jangan diganti!)
            $table->foreignId('supplier_id')
                  ->constrained('suppliers')
                  ->cascadeOnDelete();

            $table->timestamps();
>>>>>>> origin/indy
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bahan_baku');
    }
};