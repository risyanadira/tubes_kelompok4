<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    // Kode navigasi yang sudah dibersihkan dari conflict markers
    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Masterdata';

    protected static ?string $navigationLabel = 'Master Supplier';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Supplier')
                    ->description('Silakan isi detail supplier di bawah ini.')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('id_supplier')
                                    ->label('ID Supplier')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('Contoh: SUP001'),

                                Forms\Components\TextInput::make('nama_supplier')
                                    ->label('Nama Supplier')
                                    ->required()
                                    ->placeholder('Contoh: Rizki'),
                            ]),

                        Forms\Components\TextInput::make('no_telp')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->prefix('+62')
                            ->placeholder('8123456789'),

                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
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
                    ->dateTime()
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}