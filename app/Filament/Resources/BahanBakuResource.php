<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BahanBakuResource\Pages;
use App\Models\Bahan_Baku;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// FORM COMPONENTS
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

// TABLE COMPONENTS
use Filament\Tables\Columns\TextColumn;

class BahanBakuResource extends Resource
{
    protected static ?string $model = Bahan_Baku::class;

    // NAVIGATION
    protected static ?string $navigationGroup = 'Masterdata';

    protected static ?string $modelLabel = 'Bahan Baku';

    protected static ?string $pluralModelLabel = 'Bahan Baku';

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    /* -------------------------------------------------------------------------- */
    /* FORM                                                                        */
    /* -------------------------------------------------------------------------- */

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('id_bahan_baku')
                    ->default(fn () => Bahan_Baku::getIdBahanBaku())
                    ->label('ID Bahan Baku')
                    ->required()
                    ->readonly(),

                TextInput::make('nama_bahan_baku')
                    ->label('Nama Bahan Baku')
                    ->required()
                    ->placeholder('Contoh: Tepung Terigu'),

                TextInput::make('harga_bahan_baku')
                    ->label('Harga')
                    ->required()
                    ->prefix('Rp')
                    ->live(onBlur: true)

                    // hapus titik sebelum simpan database
                    ->dehydrateStateUsing(
                        fn ($state) => (int) str_replace('.', '', $state)
                    )

                    // format rupiah otomatis
                    ->afterStateUpdated(
                        fn ($state, callable $set) =>
                        $set(
                            'harga_bahan_baku',
                            $state
                                ? number_format(
                                    (int) str_replace('.', '', $state),
                                    0,
                                    ',',
                                    '.'
                                )
                                : 0
                        )
                    ),

                TextInput::make('stok_bahan_baku')
                    ->label('Stok Awal')
                    ->numeric()
                    ->required(),

                TextInput::make('stok_minimum')
                    ->label('Minimum Stok')
                    ->numeric()
                    ->default(0),

                TextInput::make('satuan')
                    ->label('Satuan')
                    ->placeholder('kg, gr, pcs, dll')
                    ->required(),

                DatePicker::make('tanggal_expired')
                    ->label('Tanggal Expired'),

            ]);
    }

    /* -------------------------------------------------------------------------- */
    /* TABLE                                                                       */
    /* -------------------------------------------------------------------------- */

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('id_bahan_baku')
                    ->label('ID')
                    ->searchable(),

                TextColumn::make('nama_bahan_baku')
                    ->label('Nama Bahan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('harga_bahan_baku')
                    ->label('Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('stok_bahan_baku')
                    ->label('Stok')
                    ->sortable(),

                TextColumn::make('stok_minimum')
                    ->label('Min. Stok'),

                TextColumn::make('satuan')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                TextColumn::make('tanggal_expired')
                    ->label('Expired')
                    ->date(),

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

    /* -------------------------------------------------------------------------- */
    /* RELATIONS                                                                   */
    /* -------------------------------------------------------------------------- */

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /* -------------------------------------------------------------------------- */
    /* PAGES                                                                       */
    /* -------------------------------------------------------------------------- */

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBahanBakus::route('/'),
            'create' => Pages\CreateBahanBaku::route('/create'),
            'edit' => Pages\EditBahanBaku::route('/{record}/edit'),
        ];
    }
}