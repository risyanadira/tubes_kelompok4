<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Memastikan tabel belum ada sebelum dibuat (aman dari error)
        if (!Schema::hasTable('penggajians')) {
            Schema::create('penggajians', function (Blueprint $table) {
                $table->id();
                // Menghubungkan ke tabel karyawans dengan cascade delete
                $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('cascade');
                $table->date('tanggal_gaji');
                $table->integer('total_gaji')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('penggajians');
    }
};