<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penggajians', function (Blueprint $table) {
            $table->id();
            // Menghubungkan detail ini ke id di tabel penggajians di atas
            $table->foreignId('penggajian_id')->constrained('penggajians')->onDelete('cascade');
            
            // Kolom yang sempat error/hilang kemarin:
            $table->string('komponen_gaji'); 
            
            $table->integer('nominal')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penggajians');
    }
};