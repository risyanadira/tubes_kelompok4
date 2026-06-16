<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Models\Penjualan; // Pastikan modelnya adalah Penjualan
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class; // Harus Penjualan, bukan Jurnal

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart'; // Sesuaikan icon-nya
    
    protected static ?string $navigationLabel = 'Penjualan'; // Labelnya Penjualan

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Isi form penjualan kamu di sini...
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Isi kolom tabel penjualan kamu di sini...
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}