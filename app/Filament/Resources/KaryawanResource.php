<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KaryawanResource\Pages;
use App\Filament\Resources\KaryawanResource\RelationManagers;
use App\Models\Karyawan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;

class KaryawanResource extends Resource
{
    protected static ?string $model = Karyawan::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    // tambahan buat grup masterdata
    protected static ?string $navigationGroup = 'Masterdata';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('kode_pegawai')
                    ->default(fn () => Karyawan::getKodePegawai()) // Ambil default dari method getKodeBarang
                    ->label('Kode Pegawai')
                    ->required()
                    ->readonly(), // Membuat field menjadi read-only

                    TextInput::make('nama_pegawai')
                    ->label('Nama Pegawai')
                    ->required(),

                    DatePicker::make('tgl_lahir')
                    ->label('Tanggal Lahir')
                    ->required(),

                     TextInput::make('no_telp')
                    ->label('No Telepon')
                    ->tel()
                    ->maxLength(15)
                    ->required(),

                    Textarea::make('alamat')
                    ->label('Alamat')
                    ->maxLength(300)
                    ->required(),

                    FileUpload::make('foto')
                    ->label('Foto')
                    ->image()
                    ->directory('images')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                            Tables\Columns\TextColumn::make('kode_pegawai')
                ->label('Kode Pegawai')
                ->searchable(),

            Tables\Columns\TextColumn::make('nama_pegawai')
                ->label('Nama Pegawai')
                ->searchable(),

            Tables\Columns\TextColumn::make('tgl_lahir')
                ->label('Tanggal Lahir')
                ->date(),

            Tables\Columns\TextColumn::make('no_telp')
                ->label('No Telepon'),

            Tables\Columns\TextColumn::make('alamat')
                ->label('Alamat')
                ->limit(30),

            Tables\Columns\ImageColumn::make('foto')
                ->label('Foto'),
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
            'index' => Pages\ListKaryawans::route('/'),
            'create' => Pages\CreateKaryawan::route('/create'),
            'edit' => Pages\EditKaryawan::route('/{record}/edit'),
        ];
    }
}
