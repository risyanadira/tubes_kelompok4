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
        if (!Schema::hasColumn('users', 'user_group')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('user_group', 20)
                    ->default('customer');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'user_group')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('user_group');
            });
        }
    }
};