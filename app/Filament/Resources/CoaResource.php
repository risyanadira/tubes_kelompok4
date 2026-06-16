<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoaResource\Pages;
use App\Models\Coa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Card;

class CoaResource extends Resource
{
    protected static ?string $model = Coa::class;

    // Kode sudah dibersihkan dari conflict markers
    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $navigationGroup = 'Masterdata';
    protected static ?string $navigationLabel = 'COA';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('kode_akun')
                            ->label('Kode Akun')
                            ->required()
                            ->unique(ignoreRecord: true),

                        TextInput::make('nama_akun')
                            ->label('Nama Akun')
                            ->required(),

                        Select::make('header_akun')
                            ->label('Header Akun')
                            ->options([
                                'Aktiva' => 'Aktiva',
                                'Kewajiban' => 'Kewajiban',
                                'Modal' => 'Modal',
                                'Pendapatan' => 'Pendapatan',
                                'Beban' => 'Beban',
                            ])
                            ->required()
                            ->native(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_akun')
                    ->label('Kode')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('nama_akun')
                    ->label('Nama Akun')
                    ->searchable(),

                TextColumn::make('header_akun')
                    ->label('Header')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aktiva' => 'success',
                        'Kewajiban' => 'danger',
                        'Modal' => 'warning',
                        'Pendapatan' => 'info',
                        'Beban' => 'gray',
                        default => 'gray',
                    }),
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
            'index' => Pages\ListCoas::route('/'),
            'create' => Pages\CreateCoa::route('/create'),
            'edit' => Pages\EditCoa::route('/{record}/edit'),
        ];
    }
}