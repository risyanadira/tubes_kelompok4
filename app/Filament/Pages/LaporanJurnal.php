<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Jurnal;

class LaporanJurnal extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Jurnal Umum'; // Nama di sidebar tetap rapi
    protected static ?string $title = 'Jurnal Umum';
    protected static ?string $navigationGroup = 'Laporan'; 

    protected static string $view = 'filament.pages.laporan-jurnal'; // Mengarah ke blade baru

    public $periode;
    public $jurnals = [];

    public function mount()
    {
        $this->periode = now()->format('Y-m');
        $this->ambilDataJurnal();
    }

    public function updatedPeriode()
    {
        $this->ambilDataJurnal();
    }

    public function ambilDataJurnal()
    {
        if (!$this->periode) {
            $this->jurnals = [];
            return;
        }

        $tahunBulan = explode('-', $this->periode);
        $tahun = $tahunBulan[0];
        $bulan = $tahunBulan[1];

        $this->jurnals = Jurnal::with(['jurnaldetail.coa'])
            ->whereYear('tgl', $tahun)
            ->whereMonth('tgl', $bulan)
            ->orderBy('tgl', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }
}