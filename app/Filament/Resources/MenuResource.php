<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Filament\Resources\MenuResource\RelationManagers;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// tambahan
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('kode_menu')
                    ->default(fn () => Menu::getKodeMenu())
                    ->label('Kode Menu')
                    ->required()
                    ->readonly(),

                TextInput::make('nama_menu')
                    ->required()
                    ->placeholder('Masukkan nama menu'),

                TextInput::make('harga')
                    ->required()
                    ->minValue(0)
                    ->reactive()
                    ->placeholder('Masukkan harga menu')
                    ->live()
                    ->afterStateUpdated(fn ($state, callable $set) => 
                        $set('harga', number_format((int) str_replace('.', '', $state), 0, ',', '.'))
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('kode_menu')
                    ->searchable(),

                TextColumn::make('nama_menu')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('harga')
                    ->label('Harga')
                    ->formatStateUsing(fn ($state) => rupiah($state))
                    ->extraAttributes(['class' => 'text-right'])
                    ->sortable(),

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
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
