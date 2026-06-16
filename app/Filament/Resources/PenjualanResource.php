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
use Filament\Forms\Components\Placeholder;

use Filament\Forms\Get;
use Filament\Forms\Set;

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

    public static function form(Form $form): Form
    {
        return $form->schema([

            Wizard::make([

                // STEP 1
                Wizard\Step::make('Informasi')
                    ->schema([

                        Section::make('Data Transaksi')
                            ->schema([

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

                                Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'lunas' => 'Lunas',
                                    ])
                                    ->default('pending')
                                    ->required(),

                            ])
                            ->columns(2),

                    ]),

                // STEP 2
                Wizard\Step::make('Menu')
                    ->schema([

                        Repeater::make('detail')
                            ->relationship()
                            ->schema([

                                Select::make('menu_id')
                                    ->options(Menu::pluck('nama_menu', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {

                                        $menu = Menu::find($state);

                                        if ($menu) {

                                            $qty = (int) ($get('qty') ?? 1);

                                            $set('harga', $menu->harga);

                                            $set('subtotal', $menu->harga * $qty);

                                            $items = $get('../../detail') ?? [];

                                            $total = collect($items)->sum(function ($item) {
                                                return ($item['harga'] ?? 0) * ($item['qty'] ?? 0);
                                            });

                                            $set('../../total', $total);
                                        }
                                    }),

                                TextInput::make('harga')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readonly(),

                                TextInput::make('qty')
                                    ->default(1)
                                    ->numeric()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {

                                        $harga = (int) ($get('harga') ?? 0);

                                        $subtotal = $harga * (int) $state;

                                        $set('subtotal', $subtotal);

                                        $items = $get('../../detail') ?? [];

                                        $total = collect($items)->sum(function ($item) {
                                            return ($item['harga'] ?? 0) * ($item['qty'] ?? 0);
                                        });

                                        $set('../../total', $total);
                                    }),

                                TextInput::make('subtotal')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readonly(),

                            ])
                            ->columns(4)
                            ->defaultItems(1)
                            ->addActionLabel('Tambah Menu'),

                    ]),

                // STEP 3
                Wizard\Step::make('Pembayaran')
                    ->schema([

                        Placeholder::make('info')
                            ->content('Pilih metode pembayaran dan simpan transaksi.'),

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

                    ]),

            ])->columnSpanFull(),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([

                TextColumn::make('no_faktur')
                    ->label('No Faktur')
                    ->searchable(),

                TextColumn::make('tgl')
                    ->label('Tanggal')
                    ->date(),

                TextColumn::make('karyawan.nama_pegawai')
                    ->label('Kasir'),

                TextColumn::make('metode_pembayaran')
                    ->label('Metode'),

                TextColumn::make('total')
                    ->money('IDR'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'lunas' => 'success',
                        'pending' => 'warning',
                        default => 'gray',
                    }),

            ])

            ->actions([

                Tables\Actions\Action::make('bayar')
                    ->label('Bayar')
                    ->icon('heroicon-o-credit-card')
                    ->color('success')
                    ->url(fn ($record) => route(
                        'filament.admin.resources.pembayarans.create',
                        [
                            'penjualan_id' => $record->id,
                        ]
                    ))
                    ->visible(fn ($record) => $record->status === 'pending'),

                Tables\Actions\ViewAction::make(),

                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make(),

            ])

            ->headerActions([

                ExportAction::make()
                    ->exporter(PenjualanExporter::class)
                    ->color('success'),

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