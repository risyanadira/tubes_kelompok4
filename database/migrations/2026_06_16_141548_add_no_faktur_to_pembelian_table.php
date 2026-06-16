<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembelian', function (Blueprint $table) {
            // Kita tambahkan setelah kolom 'id' dan dibuat nullable dulu 
            // agar data pembelian yang lama tidak error karena kolom kosong
            $table->string('no_faktur')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('pembelian', function (Blueprint $table) {
            $table->dropColumn('no_faktur');
        });
    }
};