<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembelianResource\Pages;
use App\Models\Pembelian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Repeater;
// Import fitur Export
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class PembelianResource extends Resource
{
    protected static ?string $model = Pembelian::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Transaksi Pembelian';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // --- SECTION ATAS (Induk Pembelian) ---
                Forms\Components\Section::make('Informasi Transaksi')
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal Pembelian')
                            ->required()
                            ->default(now()),

                        Forms\Components\Select::make('supplier_id')
                            ->label('Pilih Supplier')
                            ->relationship('supplier', 'nama_supplier')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        // Field Total Harga Otomatis
                        Forms\Components\TextInput::make('total_harga')
                            ->label('Total Bayar Keseluruhan')
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly()
                            ->id('total_harga_input')
                            ->helperText('Otomatis terjumlah dari rincian barang di bawah.'),

                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan Nota')
                            ->placeholder('Contoh: Belanja mingguan bahan baku bakso')
                            ->columnSpanFull(),
                    ])->columns(3),

                // --- SECTION BAWAH (Detail Barang - Repeater) ---
                Forms\Components\Section::make('Rincian Barang / Bahan Baku')
                    ->schema([
                        Forms\Components\Repeater::make('details')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('nama_barang')
                                    ->label('Nama Barang')
                                    ->required(),

                                Forms\Components\TextInput::make('qty')
                                    ->label('Jumlah (Qty)')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $subtotal = (int)$state * (int)$get('harga');
                                        $set('subtotal', $subtotal);
                                        static::updateTotalHarga($get, $set);
                                    }),

                                Forms\Components\TextInput::make('harga')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->required()
                                    ->prefix('Rp')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $subtotal = (int)$state * (int)$get('qty');
                                        $set('subtotal', $subtotal);
                                        static::updateTotalHarga($get, $set);
                                    }),

                                Forms\Components\TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->readOnly()
                                    ->prefix('Rp')
                                    ->dehydrated(),
                            ])
                            ->columns(4)
                            ->itemLabel(fn (array $state): ?string => $state['nama_barang'] ?? 'Barang Baru')
                            ->collapsible()
                            // Update total harga jika item dihapus
                            ->afterStateUpdated(fn ($get, $set) => static::updateTotalHarga($get, $set))
                            ->defaultItems(1),
                    ]),
            ]);
    }

    // FUNGSI LOGIKA: Menjumlahkan semua subtotal di dalam repeater ke total_harga utama
    public static function updateTotalHarga($get, $set)
    {
        $selectedDetails = collect($get('details'))->filter(fn($item) => !empty($item['subtotal']));
        $total = $selectedDetails->reduce(fn($total, $item) => $total + (int)$item['subtotal'], 0);
        
        $set('total_harga', $total);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('supplier.nama_supplier')
                    ->label('Supplier')
                    ->searchable(),

                Tables\Columns\TextColumn::make('details_count')
                    ->label('Item')
                    ->counts('details')
                    ->suffix(' Jenis'),

                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total Bayar')
                    ->money('IDR')
                    ->sortable()
                    ->color('success')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Input Pada')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tambahkan Tombol Export Excel di sini
                    ExportBulkAction::make()
                        ->label('Export ke Excel')
                        ->icon('heroicon-o-arrow-down-tray'),
                    
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