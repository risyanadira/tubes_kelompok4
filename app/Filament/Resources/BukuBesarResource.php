<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BukuBesarResource\Pages;
use App\Filament\Resources\BukuBesarResource\RelationManagers;
use App\Models\BukuBesar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// Tambahan komponen Filament
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Section;

class BukuBesarResource extends Resource
{
    protected static ?string $model = BukuBesar::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Laporan';

    /**
     * HAK AKSES GLOBAL UNTUK RESOURCE INI
     * Fungsi-fungsi di bawah ini memaksa Filament meloloskan akses halaman
     */
    public static function canViewAny(): bool
    {
        return true; 
    }

    public static function canCreate(): bool
    {
        return true;
    }

    public static function canEdit($record): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ])
            ->paginated(false);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            \App\Filament\Resources\BukuBesarResource\Widgets\BukuBesar::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBukuBesars::route('/'),
            'create' => Pages\CreateBukuBesar::route('/create'),
            'edit' => Pages\EditBukuBesar::route('/{record}/edit'),
        ];
    }
}