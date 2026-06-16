<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranResource\Pages;
use App\Models\Pembayaran;
use App\Models\Penjualan;
use App\Models\MetodePembayaran;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                // pilih penjualan
                Forms\Components\Select::make('penjualan_id')
                    ->relationship('penjualan', 'no_faktur')
                    ->default(request()->get('penjualan_id'))
                    ->required()
                    ->label('Pilih Penjualan')
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {

                        $penjualan = Penjualan::find($state);

                        if ($penjualan) {
                            $set('gross_amount', $penjualan->total);
                        }
                    }),

                // tanggal bayar
                Forms\Components\DatePicker::make('tgl_bayar')
                    ->required()
                    ->label('Tanggal Bayar')
                    ->default(now()),

                // metode pembayaran
                Forms\Components\Select::make('kode_metode')
                    ->label('Metode Pembayaran')
                    ->options(
                        MetodePembayaran::pluck('nama_metode', 'kode_metode')
                    )
                    ->searchable()
                    ->required(),

                // jumlah pembayaran otomatis dari total penjualan
                Forms\Components\TextInput::make('gross_amount')
                    ->label('Jumlah Bayar')
                    ->numeric()
                    ->required()
                    ->default(function () {

                        $penjualanId = request()->get('penjualan_id');

                        if ($penjualanId) {
                            $penjualan = Penjualan::find($penjualanId);

                            return $penjualan?->total;
                        }

                        return null;
                    })
                    ->readOnly(),

                // status pembayaran
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'lunas' => 'Lunas',
                    ])
                    ->required(),

                // order id otomatis
                Forms\Components\TextInput::make('order_id')
                    ->label('Order ID')
                    ->default(function () {

                        $last = Pembayaran::latest()->first();

                        if (!$last || !$last->order_id) {
                            return 'ORD001';
                        }

                        $number = (int) substr($last->order_id, 3);

                        $number++;

                        return 'ORD' . str_pad($number, 3, '0', STR_PAD_LEFT);
                    })
                    ->readOnly(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('penjualan.no_faktur')
                    ->label('No Faktur'),

                Tables\Columns\TextColumn::make('tgl_bayar')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kode_metode')
                    ->label('Metode'),

                Tables\Columns\TextColumn::make('gross_amount')
                    ->money('IDR'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'lunas',
                    ]),

                Tables\Columns\TextColumn::make('order_id')
                    ->label('Order ID'),

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