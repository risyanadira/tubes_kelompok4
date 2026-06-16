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
        Schema::create('detail_penggajian', function (Blueprint $table) {

            $table->id();

            $table->foreignId('penggajian_id')
                ->constrained('penggajian')
                ->cascadeOnDelete();

            $table->string('keterangan');

            $table->integer('nominal');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penggajian');
    }
};