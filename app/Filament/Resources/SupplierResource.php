<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
// Library export
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    // NAVIGATION - Konfigurasi tampilan di Sidebar
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Masterdata';
    
    // MENGHAPUS KATA "MASTER"
    protected static ?string $navigationLabel = 'Supplier'; 
    protected static ?string $pluralLabel = 'Supplier';
    protected static ?string $slug = 'suppliers';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Supplier')
                    ->description('Lengkapi informasi detail supplier di bawah ini.')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                // Fitur Auto-Numbering SUP001, SUP002, dst.
                                Forms\Components\TextInput::make('id_supplier')
                                    ->label('ID Supplier')
                                    ->required()
                                    ->readOnly() 
                                    ->default(function () {
                                        $latest = \App\Models\Supplier::orderBy('created_at', 'desc')->first();
                                        if (!$latest) return 'SUP001';
                                        
                                        $number = (int) str_replace('SUP', '', $latest->id_supplier) + 1;
                                        return 'SUP' . str_pad($number, 3, '0', STR_PAD_LEFT);
                                    }),

                                Forms\Components\TextInput::make('nama_supplier')
                                    ->label('Nama Supplier')
                                    ->required()
                                    ->placeholder('Contoh: PT. Sumber Rejeki'),
                            ]),

                        Forms\Components\TextInput::make('no_telp')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->prefix('+62')
                            ->placeholder('8123456789'),

                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->placeholder('Alamat gudang atau kantor...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_supplier')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('nama_supplier')
                    ->label('Nama Supplier')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('no_telp')
                    ->label('Telepon')
                    ->icon('heroicon-m-phone'),

                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(40),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Input')
                    ->dateTime('d M Y')
                    ->sortable()
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
                    // Fitur Export Excel
                    ExportBulkAction::make()
                        ->label('Export ke Excel')
                        ->icon('heroicon-o-arrow-down-tray'),
                    
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}