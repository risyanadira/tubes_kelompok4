<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranResource\Pages;
use App\Models\Pembayaran;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    
    // group menu sidebar
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Pembayaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                // pilih penjualan
                Forms\Components\Select::make('penjualan_id')
                    ->relationship('penjualan', 'id')
                    ->default(request()->get('penjualan_id'))
                    ->required()
                    ->label('Pilih Penjualan')
                    ->searchable(),

                // tanggal bayar
                Forms\Components\DatePicker::make('tgl_bayar')
                    ->required()
                    ->label('Tanggal Bayar'),

                // kode metode pembayaran
                Forms\Components\TextInput::make('kode_metode')
                    ->required()
                    ->label('Kode Metode'),

                // jumlah pembayaran
                Forms\Components\TextInput::make('gross_amount')
                    ->numeric()
                    ->required()
                    ->label('Jumlah Bayar'),

                // status pembayaran
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'lunas' => 'Lunas',
                    ])
                    ->required(),

                // tipe pembayaran
                Forms\Components\TextInput::make('payment_type')
                    ->label('Tipe Pembayaran'),

                // transaction id
                Forms\Components\TextInput::make('transaction_id')
                    ->label('Transaction ID'),

                // order id
                Forms\Components\TextInput::make('order_id')
                    ->label('Order ID'),

                // status code
                Forms\Components\TextInput::make('status_code')
                    ->label('Status Code'),

                // merchant id
                Forms\Components\TextInput::make('merchant_id')
                    ->label('Merchant ID'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                // id penjualan
                Tables\Columns\TextColumn::make('penjualan.id')
                    ->label('ID Penjualan'),

                // tanggal bayar
                Tables\Columns\TextColumn::make('tgl_bayar')
                    ->date()
                    ->sortable()
                    ->searchable(),

                // metode pembayaran
                Tables\Columns\TextColumn::make('kode_metode')
                    ->label('Metode')
                    ->searchable(),

                // jumlah bayar
                Tables\Columns\TextColumn::make('gross_amount')
                    ->money('IDR')
                    ->sortable(),

                // status pembayaran
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'lunas',
                    ]),

                // tipe pembayaran
                Tables\Columns\TextColumn::make('payment_type')
                    ->label('Tipe'),

                // transaction id
                Tables\Columns\TextColumn::make('transaction_id')
                    ->label('Transaction ID')
                    ->limit(15),

            ])
            ->filters([
                //
            ])
            ->actions([

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
            'index' => Pages\ListPembayarans::route('/'),
            'create' => Pages\CreatePembayaran::route('/create'),
            'edit' => Pages\EditPembayaran::route('/{record}/edit'),
        ];
    }
}