<?php

namespace App\Filament\Pages;

use App\Models\JurnalDetail;
use Filament\Pages\Page;

class LabaRugi extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Laba Rugi';

    protected static ?string $navigationGroup = 'Laporan';

    protected static string $view = 'filament.pages.laba-rugi';

    public $pendapatan = 0;
    public $beban = 0;
    public $labaBersih = 0;

    public function mount(): void
    {
        $this->pendapatan = JurnalDetail::whereHas('coa', function ($query) {
            $query->where('header_akun', 'Pendapatan');
        })->sum('credit');

        $this->beban = JurnalDetail::whereHas('coa', function ($query) {
            $query->where('header_akun', 'Beban');
        })->sum('debit');

        $this->labaBersih = $this->pendapatan - $this->beban;
    }
}