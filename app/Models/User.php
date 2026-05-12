<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;

// Pastikan ada "implements FilamentUser"
class User extends Authenticatable implements FilamentUser
{
    // ... kode lainnya ...

    public function canAccessPanel(Panel $panel): bool
    {
        // Berikan nilai true agar bisa login
        return true; 
    }
}