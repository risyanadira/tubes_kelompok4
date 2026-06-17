<x-filament::page>

    <div class="bg-white p-6 rounded-lg shadow">

        <h2 class="text-2xl font-bold mb-6">
            Laporan Laba Rugi
        </h2>

        <table class="w-full border">
            <tr class="border-b">
                <td class="p-3">Total Pendapatan</td>
                <td class="p-3 text-right">
                    Rp {{ number_format($pendapatan,0,',','.') }}
                </td>
            </tr>

            <tr class="border-b">
                <td class="p-3">Total Beban</td>
                <td class="p-3 text-right">
                    Rp {{ number_format($beban,0,',','.') }}
                </td>
            </tr>

            <tr class="font-bold">
                <td class="p-3">Laba Bersih</td>
                <td class="p-3 text-right">
                    Rp {{ number_format($labaBersih,0,',','.') }}
                </td>
            </tr>
        </table>

    </div>

</x-filament::page>