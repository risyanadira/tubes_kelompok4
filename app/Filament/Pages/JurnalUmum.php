<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Jurnal;

class JurnalUmum extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Jurnal Umum';
    protected static ?string $title = 'Jurnal Umum';
    protected static ?string $navigationGroup = 'Laporan'; // Biar rapi mengelompok ke grup Laporan

    protected static string $view = 'filament.pages.jurnal-umum';

    public $periode;
    public $jurnals = [];

    public function mount()
    {
        // Set default periode ke bulan berjalan saat ini (Format: YYYY-MM)
        $this->periode = now()->format('Y-m');
        $this->ambilDataJurnal();
    }

    // Fungsi otomatis berjalan saat user mengubah input filter bulan/periode
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

        // Memecah string '2026-06' menjadi tahun dan bulan
        $tahunBulan = explode('-', $this->periode);
        $tahun = $tahunBulan[0];
        $bulan = $tahunBulan[1];

        // Mengambil data dari tabel jurnals berserta relasi detail dan coas-nya
        $this->jurnals = Jurnal::with(['jurnaldetail.coa'])
            ->whereYear('tgl', $tahun)
            ->whereMonth('tgl', $bulan)
            ->orderBy('tgl', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }
}