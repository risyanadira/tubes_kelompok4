<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

use App\Models\DetailPenjualan;
use App\Models\AiInsight;
use App\Filament\Widgets\PenjualanMenuChart;
use App\Filament\Widgets\InsightPenjualanWidget;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ListPenjualans extends ListRecords
{
    protected static string $resource = PenjualanResource::class;

    protected function getHeaderWidgets(): array
{
    return [
        PenjualanMenuChart::class,
        InsightPenjualanWidget::class,
    ];
}

    protected function getHeaderActions(): array
    {
        return [

            Actions\CreateAction::make(),

            Actions\Action::make('prediksiPenjualanAI')
                ->label('🤖 Prediksi Penjualan AI')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {

                    $data = DetailPenjualan::join(
                            'menu',
                            'detail_penjualan.menu_id',
                            '=',
                            'menu.id'
                        )
                        ->select(
                            'menu.nama_menu',
                            DB::raw('SUM(detail_penjualan.qty) as total_terjual')
                        )
                        ->groupBy('menu.nama_menu')
                        ->get();

                    if ($data->isEmpty()) {

                        Notification::make()
                            ->title('Belum ada data penjualan')
                            ->warning()
                            ->send();

                        return;
                    }

                    $ringkasan = '';

                    foreach ($data as $item) {

                        $ringkasan .=
                            $item->nama_menu .
                            ' : ' .
                            $item->total_terjual .
                            " porsi\n";
                    }

                    $prompt = "
Berikut data penjualan usaha bakso:

$ringkasan

Tolong berikan:

1. Menu terlaris
2. Menu kurang diminati
3. Prediksi penjualan minggu depan
4. Strategi promosi
5. Rekomendasi pengembangan menu

Gunakan bahasa Indonesia yang mudah dipahami.
";

                    $apiKey = env('GEMINI_API_KEY');

                    try {

                        $response = Http::timeout(30)->post(
                            "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}",
                            [
                                'contents' => [
                                    [
                                        'parts' => [
                                            [
                                                'text' => $prompt
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        );

                        if ($response->successful()) {

                            $hasil =
                                $response['candidates'][0]['content']['parts'][0]['text']
                                ?? 'Insight tidak tersedia';

                        } else {

                            $terlaris = $data->sortByDesc('total_terjual')->first();
                            $terendah = $data->sortBy('total_terjual')->first();

                            $hasil = "
🤖 Prediksi Penjualan

📈 Menu Terlaris:
{$terlaris->nama_menu}
({$terlaris->total_terjual} porsi)

📉 Menu Kurang Diminati:
{$terendah->nama_menu}
({$terendah->total_terjual} porsi)

💡 Rekomendasi:
• Tingkatkan stok {$terlaris->nama_menu}
• Buat promo untuk {$terendah->nama_menu}
• Bundling dengan minuman populer

🔮 Prediksi:
Penjualan menu favorit diperkirakan tetap meningkat dalam beberapa minggu ke depan.
";
                        }

                    } catch (\Exception $e) {

                        $terlaris = $data->sortByDesc('total_terjual')->first();
                        $terendah = $data->sortBy('total_terjual')->first();

                        $hasil = "
🤖 Prediksi Penjualan

📈 Menu Terlaris:
{$terlaris->nama_menu}
({$terlaris->total_terjual} porsi)

📉 Menu Kurang Diminati:
{$terendah->nama_menu}
({$terendah->total_terjual} porsi)

💡 Rekomendasi:
• Tingkatkan stok {$terlaris->nama_menu}
• Buat promo untuk {$terendah->nama_menu}
• Bundling dengan minuman populer

🔮 Prediksi:
Penjualan menu favorit diperkirakan tetap meningkat dalam beberapa minggu ke depan.
";
                    }

                    AiInsight::create([
                        'tipe' => 'penjualan',
                        'insight' => $hasil,
                    ]);

                    Notification::make()
                        ->title('Prediksi Penjualan AI berhasil dibuat')
                        ->success()
                        ->send();
                }),
        ];
    }
}