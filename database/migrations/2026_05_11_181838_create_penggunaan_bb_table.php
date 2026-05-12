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
            $table->foreignId('id_bahan_baku')->constrained('bahan_baku')->cascadeOnDelete();
            $table->string('kode_penggunaan');
            $table->string('nama_produk_jadi');
            $table->decimal('jumlah_penggunaan', 15, 2)->default(0);
            $table->string('satuan'); // Contoh: kg, gram, pcs, liter
            $table->date('tanggal_penggunaan')->useCurrent();
            $table->text('keterangan')->nullable(); // Opsional untuk catatan tambahan
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
