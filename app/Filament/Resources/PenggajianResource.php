<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenggajianResource\Pages;
use App\Models\Penggajian;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;

class PenggajianResource extends Resource
{
    protected static ?string $model = Penggajian::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Penggajian';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Wizard::make([
                // STEP 1: Data Dasar
                Wizard\Step::make('Data Penggajian')
                    ->schema([
                        Select::make('karyawan_id')
                            ->relationship('karyawan', 'nama_pegawai')
                            ->searchable()
                            ->preload()
                            ->required(),
                        DatePicker::make('tanggal_gaji')
                            ->default(now())
                            ->required(),
                    ]),

                // STEP 2: Detail Komponen Gaji
                Wizard\Step::make('Detail Gaji')
                    ->schema([
                        Repeater::make('detailPenggajian')
                            ->relationship('detailPenggajian')
                            ->schema([
                                TextInput::make('komponen_gaji')
                                    ->placeholder('Gaji Pokok / Bonus / Potongan')
                                    ->required(),
                                TextInput::make('nominal')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->live(onBlur: true) 
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        // Mengambil semua data nominal dari repeater dan menjumlahkannya
                                        $details = $get('../../detailPenggajian');
                                        $total = collect($details)->sum('nominal');
                                        // Set nilai ke field total_gaji di step berikutnya
                                        $set('../../total_gaji', $total);
                                    }),
                            ])
                            ->columns(2)
                            ->defaultItems(1),
                    ]),

                // STEP 3: Konfirmasi Total
                Wizard\Step::make('Konfirmasi')
                    ->schema([
                        TextInput::make('total_gaji')
                            ->label('Total Gaji Diterima')
                            ->numeric()
                            ->readOnly()
                            ->prefix('Rp')
                            ->helperText('Otomatis terhitung dari detail gaji.'),
                    ]),
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('karyawan.nama_pegawai')
                    ->label('Nama Karyawan')
                    ->searchable(),
                TextColumn::make('tanggal_gaji')
                    ->date('d M Y'),
                TextColumn::make('total_gaji')
                    ->money('IDR')
                    ->label('Total Gaji'),
            ])
            ->filters([])
            ->actions([
                // Tombol Unduh Slip Gaji (Per Baris)
                Action::make('downloadSlip')
                    ->label('Slip')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function (Penggajian $record) {
                        $pdf = Pdf::loadView('pdf.slip_gaji', ['record' => $record]);
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            "slip-gaji-{$record->karyawan->nama_pegawai}.pdf"
                        );
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            // Tombol Unduh Laporan Seluruhnya (Di Pojok Atas)
            ->headerActions([
                Action::make('downloadLaporan')
                    ->label('Laporan PDF')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->action(function () {
                        $penggajian = Penggajian::with('karyawan')->get();
                        $pdf = Pdf::loadView('pdf.laporan_penggajian', ['penggajian' => $penggajian]);
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'laporan-seluruh-penggajian.pdf'
                        );
                    })
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenggajians::route('/'),
            'create' => Pages\CreatePenggajian::route('/create'),
            'edit' => Pages\EditPenggajian::route('/{record}/edit'),
        ];
    }
}