<x-filament::page>

    <div class="bg-white rounded-xl shadow p-6">

        <h2 class="text-xl font-bold mb-4">
            Grafik Clustering Menu Terlaris
        </h2>

        <canvas id="menuChart" height="100"></canvas>

        <div class="mt-4 flex gap-6">
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded bg-green-500"></div>
                <span>Laris (≥ 15)</span>
            </div>

            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded bg-yellow-500"></div>
                <span>Cukup Laris (7–14)</span>
            </div>

            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded bg-red-500"></div>
                <span>Kurang Laris (&lt; 7)</span>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const ctx = document.getElementById('menuChart');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($this->chartLabels),
                    datasets: [{
                        label: 'Total Terjual',
                        data: @json($this->chartData),
                        backgroundColor: @json($this->chartColors),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        },
                        tooltip: {
                            callbacks: {
                                afterLabel: function(context) {
                                    const total = context.raw;

                                    if (total >= 15) {
                                        return 'Cluster: Laris';
                                    } else if (total >= 7) {
                                        return 'Cluster: Cukup Laris';
                                    } else {
                                        return 'Cluster: Kurang Laris';
                                    }
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Total Terjual'
                            }
                        }
                    }
                }
            });

        });
    </script>

</x-filament::page>