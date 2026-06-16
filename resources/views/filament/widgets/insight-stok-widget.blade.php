<x-filament::section>
    <x-slot name="heading">
        🤖 AI Insight Stok
    </x-slot>

    @if($insight)

        <div class="space-y-2 text-sm">

            <div>
                <strong>📈 Analisis Terbaru</strong>
            </div>

            <div class="text-gray-700 dark:text-gray-300"
                 style="white-space: pre-line;">
                {{ $insight->insight }}
            </div>

        </div>

    @else

        <div class="text-gray-500">
            Belum ada hasil analisis AI.
        </div>

    @endif
</x-filament::section>