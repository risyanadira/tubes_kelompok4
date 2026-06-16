<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kosongkan fungsi ini agar Laravel melewati pembuatan tabel pembelian 
     * dan detail_pembelian yang aslinya sudah ada di database kamu.
     */
    public function up(): void
    {
        // Sengaja dikosongkan demi keamanan data lama kamu
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kosong
    }
};