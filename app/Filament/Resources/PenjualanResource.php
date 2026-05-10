<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Models\Penjualan;
use App\Models\Karyawan;
use App\Models\MetodePembayaran;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

// FORM
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;

// TABLE
use Filament\Tables\Columns\TextColumn;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('no_faktur')
                    ->default(fn () => 'TRX-' . now()->format('YmdHis'))
                    ->required()
                    ->readonly(),

                DatePicker::make('tgl')
                    ->label('Tanggal')
                    ->default(now())
                    ->required(),

                Select::make('pegawai_id')
                    ->label('Karyawan')
                    ->options(Karyawan::all()->pluck('nama_pegawai', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('kode_metode')
                    ->label('Metode Pembayaran')
                    ->options(MetodePembayaran::all()->pluck('nama_metode', 'kode_metode'))
                    ->searchable()
                    ->required(),

                TextInput::make('total')
                    ->label('Total')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('no_faktur')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tgl')
                    ->date()
                    ->sortable(),

                TextColumn::make('karyawan.nama_pegawai')
                    ->label('Karyawan'),

                TextColumn::make('metodePembayaran.nama_metode')
                    ->label('Metode'),

                TextColumn::make('total')
                    ->money('IDR')
                    ->sortable(),

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}