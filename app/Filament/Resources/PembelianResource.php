<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembelianResource\Pages;
use App\Models\Pembelian;
use App\Models\Bahan_Baku;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;

class PembelianResource extends Resource
{
    protected static ?string $model = Pembelian::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Pembelian Bahan Baku';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // SECTION 1: HEADER
                Section::make('Header Pembelian')
                    ->schema([
                        TextInput::make('no_faktur')
                            ->label('No. Faktur')
                            ->required()
                            ->readOnly()
                            ->default(function () {
                                $formatTanggal = date('Ymd');
                                $terakhir = Pembelian::whereDate('tanggal', date('Y-m-d'))
                                    ->latest()
                                    ->first();
                                
                                if ($terakhir && $terakhir->no_faktur) {
                                    $parts = explode('-', $terakhir->no_faktur);
                                    $nomorUrut = intval(end($parts)) + 1;
                                } else {
                                    $nomorUrut = 1;
                                }

                                return 'INV-' . $formatTanggal . '-' . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);
                            }),

                        DatePicker::make('tanggal')
                            ->required()
                            ->default(now())
                            ->live(),

                        Select::make('supplier_id')
                            ->label('Supplier')
                            ->options(Supplier::all()->pluck('nama_supplier', 'id_supplier'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live(),

                        // FIELD UTAMA YANG MAU DIISI (Dibuat Pasif / Menunggu Kiriman)
                        TextInput::make('total_harga')
                            ->numeric()
                            ->prefix('IDR')
                            ->readOnly()
                            ->placeholder('Otomatis terhitung dari detail'),

                        TextInput::make('keterangan')
                            ->nullable(),
                    ])->columns(2),

                // SECTION 2: DETAIL (Pusat Kendali Hitung Otomatis)
                Section::make('Detail Bahan Baku yang Dibeli')
                    ->schema([
                        Repeater::make('details') 
                            ->relationship('details') 
                            ->schema([
                                Select::make('nama_barang')
                                    ->label('Bahan Baku')
                                    ->options(Bahan_Baku::all()->pluck('nama_bahan_baku', 'nama_bahan_baku'))
                                    ->searchable()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $bahan = Bahan_Baku::where('nama_bahan_baku', $state)->first();
                                        if ($bahan) {
                                            $set('harga', $bahan->harga_bahan_baku ?? 0);
                                        }
                                    }),

                                TextInput::make('qty')
                                    ->label('Jumlah (Qty)')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->live(onBlur: true) // Supaya langsung merespon pas kamu ganti angka
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        $harga = floatval($get('harga') ?? 0);
                                        $qty = floatval($state ?? 0);
                                        $set('subtotal', $qty * $harga);
                                    }),

                                TextInput::make('harga')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->prefix('IDR')
                                    ->required()
                                    ->live(onBlur: true) // Supaya langsung merespon pas kamu ganti angka
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        $harga = floatval($state ?? 0);
                                        $qty = floatval($get('qty') ?? 0);
                                        $set('subtotal', $qty * $harga);
                                    }),

                                TextInput::make('subtotal')
                                    ->numeric()
                                    ->prefix('IDR')
                                    ->readOnly()
                                    ->required(),
                            ])
                            ->columns(4)
                            ->live() // KUNCI UTAMA 1: Membuat repeater jadi reaktif global
                            
                            // KUNCI UTAMA 2: Fungsi penembak total_harga ke atas
                            ->afterStateUpdated(function (callable $get, callable $set) {
                                $repeaterItems = $get('details') ?? [];
                                $grandTotal = 0;
                                foreach ($repeaterItems as $item) {
                                    $grandTotal += floatval($item['subtotal'] ?? 0);
                                }
                                // Mengisi field total_harga di section atas secara paksa
                                $set('total_harga', $grandTotal);
                            })
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_faktur')
                    ->label('No. Faktur')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('supplier_id')
                    ->label('ID Supplier')
                    ->searchable(),
                TextColumn::make('total_harga')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(), // Tombol View
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembelians::route('/'),
            'create' => Pages\CreatePembelian::route('/create'),
            'edit' => Pages\EditPembelian::route('/{record}/edit'),
        ];
    }
}