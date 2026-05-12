<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MetodePembayaranResource\Pages;
use App\Filament\Resources\MetodePembayaranResource\RelationManagers;
use App\Models\MetodePembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MetodePembayaranResource extends Resource
{
    protected static ?string $model = MetodePembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    // tambahan buat grup masterdata
    protected static ?string $navigationGroup = 'Masterdata';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode_metode')
                    ->label('Kode Metode')
                    ->disabled()
                    ->dehydrated(false)
                    ->default(function () {
                        $last = MetodePembayaran::orderBy('kode_metode', 'desc')->first();

                        if ($last) {
                            $number = (int) substr($last->kode_metode, 2) + 1;
                        } else {
                            $number = 1;
                        }

                        return 'MP' . str_pad($number, 3, '0', STR_PAD_LEFT);
                    }),
                Forms\Components\TextInput::make('nama_metode')
                    ->required(),

                Forms\Components\TextInput::make('nomor_rekening')
                    ->label('Nomor Pembayaran')
                    ->numeric(),

                Forms\Components\TextInput::make('atas_nama')
                    ->label('Atas Nama'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_metode')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_metode')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nomor_rekening')
                    ->searchable(),

                Tables\Columns\TextColumn::make('atas_nama'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListMetodePembayarans::route('/'),
            'create' => Pages\CreateMetodePembayaran::route('/create'),
            'edit' => Pages\EditMetodePembayaran::route('/{record}/edit'),
        ];
    }
}
