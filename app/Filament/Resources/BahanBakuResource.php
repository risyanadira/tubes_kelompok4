<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BahanBakuResource\Pages;
use App\Filament\Resources\rupiah;
use App\Filament\Resources\BahanBakuResource\RelationManagers;
use App\Models\Bahan_Baku;
use App\Models\BahanBaku;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
// tambahan
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class BahanBakuResource extends Resource
{
    protected static ?string $model = Bahan_Baku::class;

    // tambahan buat grup masterdata
    protected static ?string $navigationGroup = 'Masterdata';
    
    protected static ?string $modelLabel = 'Bahan Baku'; // Untuk judul satuan (misal: Create Bahan Baku)
    protected static ?string $pluralModelLabel = 'Bahan Baku'; // Untuk nama di sidebar menu
    protected static ?string $navigationIcon = 'heroicon-o-cube';

    public static function form(Form $form): Form

    {
        return $form
            ->schema([
                TextInput::make('id_bahan_baku')
                    ->default(fn () => Bahan_Baku::getIdBahanBaku()) // Ambil default dari method getKodeBarang
                    ->label('Id Bahan Baku')
                    ->required()
                    ->readonly() // Membuat field menjadi read-only
                ,
                TextInput::make('nama_bahan_baku')
                    ->required()
                    ->placeholder('Masukkan nama bahan baku') // Placeholder untuk membantu pengguna
                ,
                TextInput::make('harga_bahan_baku')
                    ->required()
                    ->minValue(0) // Nilai minimal 0 (opsional jika tidak ingin ada harga negatif)
                    ->reactive() // Menjadikan input reaktif terhadap perubahan
                    ->extraAttributes(['id' => 'harga-bahan-baku']) // Tambahkan ID untuk pengikatan JavaScript
                    ->placeholder('Masukkan harga bahan baku') // Placeholder untuk membantu pengguna
                    ->live()
                    ->afterStateUpdated(fn ($state, callable $set) => 
                        $set('harga_bahan_baku', number_format((int) str_replace('.', '', $state), 0, ',', '.'))
                      )
                ,
                TextInput::make('stok_bahan_baku')
                    ->required()
                    ->placeholder('Masukkan stok bahan baku') // Placeholder untuk membantu pengguna
                    ->minValue(0)
                ,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               //
                TextColumn::make('id_bahan_baku')
                    ->searchable(),
                
                // agar bisa di search
                TextColumn::make('nama_bahan_baku')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('harga_bahan_baku')
                ->label('Harga Bahan Baku')
                // Menggunakan helper currency Laravel
                ->money('IDR', locale: 'id') 
                ->extraAttributes(['class' => 'text-right'])
                ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBahanBakus::route('/'),
            'create' => Pages\CreateBahanBaku::route('/create'),
            'edit' => Pages\EditBahanBaku::route('/{record}/edit'),
        ];
    }
}
