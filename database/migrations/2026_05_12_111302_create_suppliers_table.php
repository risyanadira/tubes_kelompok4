<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kosongkan fungsi ini agar Laravel melewayati pembuatan tabel suppliers
     * yang aslinya sudah ada di database kamu.
     */
    public function up(): void
    {
        // Kosong melompong, sengaja dilewati demi keamanan datamu
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kosong
    }
};