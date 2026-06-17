<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class MenuClustering extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.menu-clustering';

    protected static ?string $navigationLabel = 'Clustering Menu';

    protected static ?string $title = 'Clustering Menu Terlaris';

    protected static ?string $navigationGroup = 'Machine Learning';

    public $chartLabels = [];
    public $chartData = [];
    public $chartColors = [];

    public function mount(): void
    {
        $dataMenu = DB::table('detail_penjualan')
            ->join('menu', 'detail_penjualan.menu_id', '=', 'menu.id')
            ->select(
                'menu.id',
                'menu.nama_menu',
                DB::raw('SUM(detail_penjualan.qty) as total_terjual')
            )
            ->groupBy('menu.id', 'menu.nama_menu')
            ->orderByDesc('total_terjual')
            ->get();

        foreach ($dataMenu as $menu) {

            $this->chartLabels[] = $menu->nama_menu;
            $this->chartData[] = (int) $menu->total_terjual;

            // Cluster berdasarkan total terjual
            if ($menu->total_terjual >= 15) {
                $this->chartColors[] = '#22c55e'; // Hijau = Laris
            } elseif ($menu->total_terjual >= 7) {
                $this->chartColors[] = '#f59e0b'; // Kuning = Cukup Laris
            } else {
                $this->chartColors[] = '#ef4444'; // Merah = Kurang Laris
            }
        }
    }
}