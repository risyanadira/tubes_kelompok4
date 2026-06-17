<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JurnalResource\Pages;
use App\Models\Jurnal;
use App\Models\Coa;
use App\Models\JurnalDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;

class JurnalResource extends Resource
{
    protected static ?string $model = Jurnal::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Jurnal Umum';
    protected static ?string $navigationGroup = 'Laporan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Jurnal')
                    ->schema([
                        DatePicker::make('tgl')->required()->default(now()),
                        TextInput::make('no_referensi'),
                        Textarea::make('deskripsi'),
                    ])->columns(3),

                Section::make('Detail Transaksi')
                    ->schema([
                        Repeater::make('jurnaldetail')
                            ->relationship('jurnaldetail')
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        Select::make('coa_id')
                                            ->label('Akun')
                                            ->options(Coa::all()->pluck('nama_akun', 'id'))
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                        TextInput::make('debit')->numeric()->default(0),
                                        TextInput::make('credit')->numeric()->default(0),
                                        TextInput::make('deskripsi')->label('Keterangan'),
                                    ]),
                            ])
                            ->defaultItems(2)
                            ->createItemButtonLabel('Tambah Baris')
                            ->columns(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tgl')->date()->sortable(),
                TextColumn::make('no_referensi')->searchable(),
                TextColumn::make('deskripsi')->limit(30),
                TextColumn::make('total_debit')
                    ->getStateUsing(fn($record) => $record->jurnaldetail()->sum('debit'))
                    ->money('IDR'),
                TextColumn::make('total_kredit')
                    ->getStateUsing(fn($record) => $record->jurnaldetail()->sum('credit'))
                    ->money('IDR'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJurnals::route('/'),
            'create' => Pages\CreateJurnal::route('/create'),
            'edit' => Pages\EditJurnal::route('/{record}/edit'),
        ];
    }
}