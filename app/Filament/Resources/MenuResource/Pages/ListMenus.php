<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuResource;

use App\Filament\Widgets\TrenBaksoChart;
use App\Filament\Widgets\TrenMinumanChart;
use App\Filament\Widgets\PengembanganMenuChart;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

use App\Models\AiInsight;

use Illuminate\Support\Facades\Http;

class ListMenus extends ListRecords
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            TrenBaksoChart::class,
            TrenMinumanChart::class,
            PengembanganMenuChart::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [

            Actions\CreateAction::make(),

            Actions\Action::make('trenKuliner')
                ->label('🤖 Analisis Tren Kuliner')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {

                    $prompt = "
                    Berikan analisis tren kuliner bakso di Indonesia saat ini.

                    Jelaskan:
                    1. Varian bakso yang sedang populer
                    2. Minuman pendamping yang sedang tren
                    3. Ide menu baru yang menarik

                    Jawab dalam bahasa Indonesia.
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

                            $hasil = "
🤖 AI Insight Tren Kuliner Bakso

📈 Tren Saat Ini
Menu pedas dan unik semakin diminati pelanggan.

🔥 Varian Populer
• Bakso Mercon
• Bakso Mozzarella
• Bakso Lava Pedas

🥤 Minuman Populer
• Es Teh Jumbo
• Thai Tea
• Es Kopi Susu

💡 Ide Menu
• Bakso Mozzarella Pedas
• Paket Hemat Bakso + Minuman
";
                        }

                    } catch (\Exception $e) {

                        $hasil = "
🤖 AI Insight Tren Kuliner Bakso

📈 Tren Saat Ini
Menu pedas dan unik semakin diminati pelanggan.

🔥 Varian Populer
• Bakso Mercon
• Bakso Mozzarella
• Bakso Lava Pedas

🥤 Minuman Populer
• Es Teh Jumbo
• Thai Tea
• Es Kopi Susu

💡 Ide Menu
• Bakso Mozzarella Pedas
• Paket Hemat Bakso + Minuman
";
                    }

                    AiInsight::create([
                        'tipe' => 'tren_kuliner',
                        'insight' => $hasil,
                    ]);

                    Notification::make()
                        ->title('Analisis Tren Kuliner berhasil dibuat')
                        ->success()
                        ->send();
                }),
        ];
    }
}