<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;

// Pastikan ada "implements FilamentUser"
class User extends Authenticatable implements FilamentUser
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// tambahan untuk membatasi akses panel user filament, hanya admin saja
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

// class User extends Authenticatable
class User extends Authenticatable implements FilamentUser // 1. Tambahkan implements
{
    // ... kode lainnya ...

    public function canAccessPanel(Panel $panel): bool
    {
        // Berikan nilai true agar bisa login
        return true; 
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // tambahan method untuk membatasi akses hanya user group admin saja
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->user_group === 'admin';
    }
}