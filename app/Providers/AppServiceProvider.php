<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// PENTING: Pastikan dua baris use di bawah ini ada di bagian paling atas file
use Illuminate\Support\Facades\Gate; 
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // MASUKKAN KODE INI DI DALAM METHOD BOOT:
        Gate::before(function (User $user, string $ability) {
            if ($user->user_group === 'admin') {
                return true; // Paksa akun dengan group 'admin' lolos dari semua halangan
            }
        });
    }
}