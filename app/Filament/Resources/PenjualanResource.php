<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Models\Penjualan;
use App\Models\Menu;
use App\Models\Karyawan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;

use Filament\Tables\Columns\TextColumn;

use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Actions\Action;

use App\Filament\Exports\PenjualanExporter;
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Penjualan POS';

    /* -------------------------------------------------------------------------- */
    /* FORM                                                                       */
    /* -------------------------------------------------------------------------- */

    public static function form(Form $form): Form
    {
        return $form->schema([
            Wizard::make([

                Wizard\Step::make('Informasi')->schema([
                    Section::make('Data Transaksi')->schema([
                        TextInput::make('no_faktur')
                            ->default(fn () => 'TRX-' . now()->format('YmdHis'))
                            ->readonly()
                            ->required(),

                        DatePicker::make('tgl')
                            ->default(now())
                            ->required(),

                        Select::make('karyawan_id')
                            ->label('Kasir')
                            ->options(Karyawan::pluck('nama_pegawai', 'id'))
                            ->searchable()
                            ->required(),
                    ])->columns(2),
                ]),

                Wizard\Step::make('Menu')->schema([
                    Repeater::make('detail')
                        ->relationship()
                        ->schema([
                            Select::make('menu_id')
                                ->options(Menu::pluck('nama_menu', 'id'))
                                ->searchable()
                                ->required()
                                ->reactive(),

                            TextInput::make('harga')
                                ->numeric()
                                ->prefix('Rp')
                                ->readonly(),

                            TextInput::make('qty')
                                ->numeric()
                                ->default(1),

                            TextInput::make('subtotal')
                                ->numeric()
                                ->prefix('Rp')
                                ->readonly(),
                        ])
                        ->columns(3)
                        ->defaultItems(1)
                        ->addActionLabel('Tambah Menu'),
                ]),

                Wizard\Step::make('Pembayaran')->schema([
                    TextInput::make('total')
                        ->numeric()
                        ->prefix('Rp')
                        ->readonly(),

                    Select::make('metode_pembayaran')
                        ->options([
                            'cash' => 'Cash',
                            'transfer' => 'Transfer',
                            'qris' => 'QRIS',
                        ])
                        ->required(),

                    Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'lunas' => 'Lunas',
                        ])
                        ->default('lunas')
                        ->required(),
                ]),
            ])->columnSpanFull(),
        ]);
    }

    /* -------------------------------------------------------------------------- */
    /* TABLE                                                                      */
    /* -------------------------------------------------------------------------- */

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('no_faktur')->searchable(),
            TextColumn::make('tgl')->date(),
            TextColumn::make('karyawan.nama_pegawai')->label('Kasir'),
            TextColumn::make('total')->money('IDR'),

            TextColumn::make('status')
                ->badge()
                ->color(fn ($state) => match ($state) {
                    'lunas' => 'success',
                    'pending' => 'warning',
                    default => 'gray',
                }),
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])

        ->headerActions([

            // EXPORT EXCEL
            ExportAction::make()
                ->exporter(PenjualanExporter::class)
                ->color('success'),

            // PDF ADMIN REPORT (SEMUA TRANSAKSI + DETAIL MENU)
            Action::make('downloadPdf')
                ->label('Unduh PDF Laporan')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->action(function () {

                    $penjualan = Penjualan::with('karyawan', 'detail.menu')->get();

                    $pdf = Pdf::loadView('pdf.penjualan', [
                        'penjualan' => $penjualan
                    ]);

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'laporan-penjualan.pdf'
                    );
                }),
        ])

        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),

            ExportBulkAction::make()
                ->exporter(PenjualanExporter::class),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}