<x-filament-panels::page>
    <x-filament::card>
        <div class="flex items-center gap-4 mb-6">
            <label for="periode" class="text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Periode:</label>
            <input 
                type="month" 
                id="periode" 
                wire:model.live="periode" 
                class="rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white"
            >
        </div>

        <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden bg-white dark:bg-gray-900 p-6 shadow-sm">
            <div class="text-center mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white uppercase">Toko Bakso Japra</h2>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-400">Jurnal Umum</h3>
                <p class="text-sm text-gray-500">Periode: {{ \Carbon\Carbon::parse($periode)->translatedFormat('F Y') }}</p>
            </div>

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 font-semibold border-b border-gray-200 dark:border-gray-700">
                        <th class="p-3 border">Tanggal</th>
                        <th class="p-3 border">Akun / Keterangan</th>
                        <th class="p-3 border text-center">Reff</th>
                        <th class="p-3 border text-right">Debet</th>
                        <th class="p-3 border text-right">Kredit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-800 dark:text-gray-300">
                    @php 
                        $grandTotalDebet = 0; 
                        $grandTotalKredit = 0; 
                    @endphp

                    @forelse($jurnals as $jurnal)
                        {{-- Baris Header Setiap Induk Jurnal --}}
                        <tr class="bg-gray-50/50 dark:bg-gray-800/30 font-medium">
                            <td class="p-3 border font-semibold text-gray-600 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($jurnal->tgl)->format('Y-m-d') }}
                            </td>
                            <td class="p-3 border text-gray-500 italic text-xs" colspan="4">
                                Ref: {{ $jurnal->no_referensi }} | {{ $jurnal->deskripsi }}
                            </td>
                        </tr>

                        {{-- Loop baris transaksi debet & kredit dari relasi jurnaldetail --}}
                        @foreach($jurnal->jurnaldetail as $detail)
                            @php
                                $grandTotalDebet += $detail->debit;
                                $grandTotalKredit += $detail->credit;
                            @endphp
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-800/20">
                                <td class="p-3 border"></td>
                                <td class="p-3 border">
                                    {{-- Aturan akuntansi: Jika akun bernilai Kredit, teks digeser menjorok ke dalam --}}
                                    <span class="{{ $detail->credit > 0 ? 'pl-8 font-normal text-gray-600 dark:text-gray-400' : 'font-semibold text-gray-900 dark:text-white' }}">
                                        {{ $detail->coa->nama_akun ?? 'Akun Tidak Ditemukan' }}
                                    </span>
                                </td>
                                <td class="p-3 border text-center font-mono">{{ $detail->coa->kode_akun ?? '-' }}</td>
                                <td class="p-3 border text-right font-mono">
                                    {{ $detail->debit > 0 ? 'Rp ' . number_format($detail->debit, 0, ',', '.') : '-' }}
                                </td>
                                <td class="p-3 border text-right font-mono">
                                    {{ $detail->credit > 0 ? 'Rp ' . number_format($detail->credit, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-500 italic">
                                Belum ada transaksi tercatat di periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                {{-- Total Akhir (Harus Seimbang/Balance) --}}
                <tfoot>
                    <tr class="bg-gray-50 dark:bg-gray-800 font-bold text-gray-900 dark:text-white border-t-2 border-gray-300">
                        <td colspan="3" class="p-3 border text-center uppercase tracking-wider">Total Akhir</td>
                        <td class="p-3 border text-right font-mono text-primary-600 dark:text-primary-400">
                            Rp {{ number_format($grandTotalDebet, 0, ',', '.') }}
                        </td>
                        <td class="p-3 border text-right font-mono text-primary-600 dark:text-primary-400">
                            Rp {{ number_format($grandTotalKredit, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </x-filament::card>
</x-filament-panels::page>