<?php

namespace App\Filament\Resources\BahanBakuResource\Pages;

use App\Filament\Resources\BahanBakuResource;
use App\Filament\Widgets\InsightStokWidget;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

use App\Models\PenggunaanBB;
use App\Models\AiInsight;
use App\Filament\Widgets\PenggunaanBahanBakuChart;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ListBahanBakus extends ListRecords
{
    protected static string $resource = BahanBakuResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            InsightStokWidget::class,
            PenggunaanBahanBakuChart::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [

            Actions\CreateAction::make(),

            Actions\Action::make('prediksiStokAI')
                ->label('🤖 Prediksi Stok AI')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {

                    $data = PenggunaanBB::join(
                            'bahan_baku',
                            'penggunaan_bb.id_bahan_baku',
                            '=',
                            'bahan_baku.id'
                        )
                        ->select(
                            'bahan_baku.nama_bahan_baku',
                            DB::raw('SUM(penggunaan_bb.jumlah_penggunaan) as total_pakai')
                        )
                        ->groupBy('bahan_baku.nama_bahan_baku')
                        ->get();

                    if ($data->isEmpty()) {

                        Notification::make()
                            ->title('Belum ada data penggunaan bahan baku')
                            ->warning()
                            ->send();

                        return;
                    }

                    $ringkasan = '';

                    foreach ($data as $item) {
                        $ringkasan .=
                            $item->nama_bahan_baku .
                            ' : ' .
                            $item->total_pakai .
                            "\n";
                    }

                    $prompt = "
Berikut data penggunaan bahan baku usaha bakso:

$ringkasan

Tolong berikan:

1. Bahan baku yang paling sering digunakan
2. Bahan baku yang paling jarang digunakan
3. Prediksi kebutuhan stok minggu depan
4. Risiko kehabisan stok
5. Rekomendasi pembelian bahan baku

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
                                                'text' => $prompt,
                                            ],
                                        ],
                                    ],
                                ],
                            ]
                        );

                        if ($response->successful()) {

                            $hasil =
                                $response['candidates'][0]['content']['parts'][0]['text']
                                ?? 'Insight tidak tersedia';

                        } else {

                            $tertinggi = $data->sortByDesc('total_pakai')->first();
                            $terendah = $data->sortBy('total_pakai')->first();

                            $hasil = "
🤖 Prediksi Stok Bahan Baku

📈 Bahan Paling Sering Digunakan:
{$tertinggi->nama_bahan_baku} ({$tertinggi->total_pakai})

📉 Bahan Paling Jarang Digunakan:
{$terendah->nama_bahan_baku} ({$terendah->total_pakai})

💡 Rekomendasi:
• Tingkatkan stok {$tertinggi->nama_bahan_baku}
• Lakukan pembelian ulang minggu ini
• Evaluasi penggunaan {$terendah->nama_bahan_baku}

⚠ Risiko:
{$tertinggi->nama_bahan_baku} berpotensi habis lebih cepat dibanding bahan lainnya.
";
                        }

                    } catch (\Exception $e) {

                        $tertinggi = $data->sortByDesc('total_pakai')->first();
                        $terendah = $data->sortBy('total_pakai')->first();

                        $hasil = "
🤖 Prediksi Stok Bahan Baku

📈 Bahan Paling Sering Digunakan:
{$tertinggi->nama_bahan_baku} ({$tertinggi->total_pakai})

📉 Bahan Paling Jarang Digunakan:
{$terendah->nama_bahan_baku} ({$terendah->total_pakai})

💡 Rekomendasi:
• Tingkatkan stok {$tertinggi->nama_bahan_baku}
• Lakukan pembelian ulang minggu ini
• Evaluasi penggunaan {$terendah->nama_bahan_baku}

⚠ Risiko:
{$tertinggi->nama_bahan_baku} berpotensi habis lebih cepat dibanding bahan lainnya.
";
                    }

                    AiInsight::create([
                        'tipe' => 'stok',
                        'insight' => $hasil,
                    ]);

                    Notification::make()
                        ->title('Prediksi Stok AI berhasil dibuat')
                        ->success()
                        ->send();
                }),
        ];
    }
}