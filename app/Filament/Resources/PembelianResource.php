<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembelianResource\Pages;
use App\Models\Pembelian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PembelianResource extends Resource
{
    protected static ?string $model = Pembelian::class;

    protected static ?string $navigationGroup = 'Transaksi';

    // Ikon diubah agar seragam dengan Bahan Baku dan Master Supplier
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Pembelian';

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
                        
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan Nota')
                            ->placeholder('Contoh: Pembelian bahan baku daging')
                            ->columnSpanFull(),
                    ])->columns(2),

                // --- SECTION BAWAH (Detail Barang - Repeater) ---
                Forms\Components\Section::make('Rincian Barang / Bahan Baku')
                    ->schema([
                        Forms\Components\Repeater::make('details')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('nama_barang')
                                    ->label('Nama Bahan Baku')
                                    ->required()
                                    ->placeholder('Contoh: Daging Sapi / Ayam'),

                                Forms\Components\TextInput::make('qty')
                                    ->label('Jumlah (Qty)')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, $set, $get) => 
                                        $set('subtotal', (int)$state * (int)$get('harga'))),

                                Forms\Components\TextInput::make('harga')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->required()
                                    ->prefix('Rp')
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, $set, $get) => 
                                        $set('subtotal', (int)$state * (int)$get('qty'))),

                                Forms\Components\TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->readOnly()
                                    ->prefix('Rp')
                                    ->dehydrated(),
                            ])
                            ->columns(4)
                            ->itemLabel(fn (array $state): ?string => $state['nama_barang'] ?? null)
                            ->collapsible()
                            ->defaultItems(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('supplier.nama_supplier')
                    ->label('Supplier')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('details_count')
                    ->label('Jenis Barang')
                    ->counts('details'),

                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total Bayar')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Input Pada')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListPembelians::route('/'),
            'create' => Pages\CreatePembelian::route('/create'),
            'edit' => Pages\EditPembelian::route('/{record}/edit'),
        ];
    }
}