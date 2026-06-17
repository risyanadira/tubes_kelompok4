<x-filament::section>
    <x-slot name="heading">
        🤖 Insight Penjualan AI
    </x-slot>

    @if($insight)

        <div style="white-space: pre-line; line-height:1.8">
            {{ $insight->insight }}
        </div>

    @else

        <p>Belum ada insight penjualan.</p>

    @endif

</x-filament::section>