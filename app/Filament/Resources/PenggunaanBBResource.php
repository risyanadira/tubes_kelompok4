<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenggunaanBBResource\Pages;
use App\Filament\Resources\PenggunaanBBResource\RelationManagers;
use App\Models\PenggunaanBB;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Karyawan;

// tambahan untuk tombol unduh pdf
use Filament\Tables\Actions\Action; //untuk dapat menggunakan action
use Barryvdh\DomPDF\Facade\Pdf; // pakai DomPDF
use Illuminate\Support\Facades\Storage;

class PenggunaanBBResource extends Resource
{
    protected static ?string $model = PenggunaanBB::class;
    protected static ?string $slug = 'penggunaan_bb';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Penggunaan';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\Select::make('nama_pegawai')
                    ->label('Nama Pegawai')
                    ->options(Karyawan::all()->pluck('nama_pegawai', 'nama_pegawai')) 
                    ->searchable() 
                    ->required(),
                    Forms\Components\TextInput::make('kode_penggunaan')
                        ->default(fn () => \App\Models\PenggunaanBB::getKodePenggunaan())
                        ->disabled()
                        ->dehydrated(), // Tetap dikirim ke database meski disabled
                    
                    Forms\Components\Select::make('id_bahan_baku')
                        ->relationship('BahanBaku', 'nama_bahan_baku') // Sesuai nama relasi di Model
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn ($state, $set) => 
                            $set('satuan', \App\Models\Bahan_Baku::find($state)?->satuan)), // Otomatis isi satuan
                    
                    Forms\Components\TextInput::make('nama_produk_jadi')
                        ->required(),
                    
                    Forms\Components\TextInput::make('jumlah_penggunaan')
                        ->numeric()
                        ->required(),
                    
                    Forms\Components\TextInput::make('satuan')
                        ->disabled()
                        ->dehydrated(),

                    Forms\Components\DatePicker::make('tanggal_penggunaan')
                        ->default(now())
                        ->required(),

                    Forms\Components\Textarea::make('keterangan')
                        ->columnSpanFull(),
                ])->columns(2)
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('kode_penggunaan')->sortable(),
            Tables\Columns\TextColumn::make('BahanBaku.nama_bahan_baku')->label('Bahan Baku'),
            Tables\Columns\TextColumn::make('nama_produk_jadi')->searchable(),
            Tables\Columns\TextColumn::make('jumlah_penggunaan')->label('Jumlah'),
            Tables\Columns\TextColumn::make('satuan'),
            Tables\Columns\TextColumn::make('tanggal_penggunaan')->date(),
            ])
            // tombol tambahan
            ->headerActions([
                // tombol tambahan export pdf
                // ✅ Tombol Unduh PDF
                Action::make('downloadPdf')
                ->label('Unduh PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    // Ambil semua data penggunaan
                    $data = \App\Models\PenggunaanBB::all();

                    // Pastikan nama view sesuai: pdf.penggunaanbb
                    $pdf = Pdf::loadView('pdf.penggunaanbb', ['data' => $data]);

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'laporan-penggunaan-bb.pdf'
                        );
                    })
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
        

public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenggunaanBBS::route('/'),
            'create' => Pages\CreatePenggunaanBB::route('/create'),
            'edit' => Pages\EditPenggunaanBB::route('/{record}/edit'),
        ];
    }
}
