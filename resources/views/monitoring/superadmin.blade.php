@extends('layouts.app')

@section('content')

<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.55);
        backdrop-filter: blur(14px);
        border-radius: 18px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.07);
        transition: .25s ease;
    }
    .glass-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 32px rgba(0,0,0,0.12);
    }

    /* Warning card - same shape, different color */
    .glass-card.warning-card {
        background: rgba(254, 242, 242, 0.95) !important;
        backdrop-filter: blur(14px);
        border-radius: 18px;
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.15);
    }
    .glass-card.warning-card:hover {
        box-shadow: 0 14px 32px rgba(239, 68, 68, 0.25);
    }
    
    /* Blinking animation for warning */
    @keyframes blink-red {
        0%, 100% {
            background: rgba(254, 242, 242, 0.95);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.15);
        }
        50% {
            background: rgba(254, 226, 226, 0.98);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.35);
        }
    }
    
    .glass-card.warning-card.blink {
        animation: blink-red 1s ease-in-out infinite;
    }

    .dash-section-title {
        font-size: 20px;
        font-weight: 600;
        color: #3b3f4a;
        margin-bottom: 12px;
    }

    .stat-icon {
        font-size: 42px;
        opacity: 0.9;
    }

    .chart-card {
        border-radius: 20px !important;
        padding: 22px !important;
        background: white;
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
        transition: .2s ease;
    }
    .chart-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 26px rgba(0,0,0,0.12);
    }

</style>

<div class="container mx-auto fade-in">


    {{-- REALTIME CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">

        {{-- SUHU --}}
        <div class="glass-card p-6 flex justify-between items-center cursor-pointer {{ ($currentTemperature['isWarning'] ?? false) ? 'warning-card blink' : '' }}" 
             data-live-card="temperature" 
             data-index="0"
             data-sequence="{{ json_encode($temperatureSequence ?? []) }}">
            <div>
                <p class="text-lg font-medium {{ ($currentTemperature['isWarning'] ?? false) ? 'text-red-700' : 'text-gray-700' }} mb-1">Suhu Realtime</p>
                <p class="text-4xl font-extrabold {{ ($currentTemperature['isWarning'] ?? false) ? 'text-red-600' : 'text-primary-dark' }}" data-live-value>
                    {{ isset($currentTemperature['value']) ? number_format($currentTemperature['value'], 1) . '°C' : '-' }}
                </p>
                <p class="text-sm {{ ($currentTemperature['isWarning'] ?? false) ? 'text-red-600 font-semibold' : 'text-gray-500' }} mt-1" data-live-meta>
                    {{ $currentTemperature['status'] ?? 'Belum ada data' }}
                </p>
            </div>
            <div class="{{ ($currentTemperature['isWarning'] ?? false) ? 'text-red-600' : 'text-primary-dark' }}">
                <i class="stat-icon fa-solid fa-temperature-half"></i>
            </div>
        </div>

        {{-- KELEMBABAN --}}
        <div class="glass-card p-6 flex justify-between items-center cursor-pointer {{ ($currentHumidity['isWarning'] ?? false) ? 'warning-card blink' : '' }}" 
             data-live-card="humidity" 
             data-index="0"
             data-sequence="{{ json_encode($humiditySequence ?? []) }}">
            <div>
                <p class="text-lg font-medium {{ ($currentHumidity['isWarning'] ?? false) ? 'text-red-700' : 'text-gray-700' }} mb-1">Kelembaban Realtime</p>
                <p class="text-4xl font-extrabold {{ ($currentHumidity['isWarning'] ?? false) ? 'text-red-600' : 'text-primary' }}" data-live-value>
                    {{ isset($currentHumidity['value']) ? number_format($currentHumidity['value'], 1) . '%' : '-' }}
                </p>
                <p class="text-sm {{ ($currentHumidity['isWarning'] ?? false) ? 'text-red-600 font-semibold' : 'text-gray-500' }} mt-1" data-live-meta>
                    {{ $currentHumidity['status'] ?? 'Belum ada data' }}
                </p>
            </div>
            <div class="{{ ($currentHumidity['isWarning'] ?? false) ? 'text-red-600' : 'text-primary' }}">
                <i class="stat-icon fa-solid fa-droplet"></i>
            </div>
        </div>

    </div>


    {{-- CHART SECTION --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- CHART SUHU --}}
        <div class="chart-card">
            <div class="flex items-center justify-between mb-4">
                <h5 class="dash-section-title mb-0">Grafik Suhu</h5>
                <form method="GET" action="{{ route('monitoring') }}" class="flex items-center gap-2">
                    <input type="hidden" name="filter_humidity" value="{{ $filterHumidity }}">
                    <select name="filter_temp" onchange="this.form.submit()"
                        class="text-sm px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="day" {{ $filterTemp=='day' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="week" {{ $filterTemp=='week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ $filterTemp=='month' ? 'selected' : '' }}>Bulan Ini</option>
                    </select>
                </form>
            </div>
            <canvas id="tempChart" height="110"></canvas>
        </div>

        {{-- CHART KELEMBABAN --}}
        <div class="chart-card">
            <div class="flex items-center justify-between mb-4">
                <h5 class="dash-section-title mb-0">Grafik Kelembaban</h5>
                <form method="GET" action="{{ route('monitoring') }}" class="flex items-center gap-2">
                    <input type="hidden" name="filter_temp" value="{{ $filterTemp }}">
                    <select name="filter_humidity" onchange="this.form.submit()"
                        class="text-sm px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="day" {{ $filterHumidity=='day' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="week" {{ $filterHumidity=='week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ $filterHumidity=='month' ? 'selected' : '' }}>Bulan Ini</option>
                    </select>
                </form>
            </div>
            <canvas id="humidityChart" height="110"></canvas>
        </div>

    </div>

    {{-- HISTORI PAKAN - FULL WIDTH --}}
    <div class="chart-card mt-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h5 class="dash-section-title mb-0">Histori Pakan</h5>
                <p class="text-sm text-gray-500 mt-1">Data bobot pakan yang sudah diatur di halaman Atur Bobot Pakan (maksimal 3 bulan terakhir)</p>
            </div>
        </div>
        <canvas id="feedingChart" height="140"></canvas>
    </div>

    {{-- GRAFIK BOBOT SAPI - FULL WIDTH --}}
    <div class="chart-card mt-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h5 class="dash-section-title mb-0">Grafik Rata-rata Bobot Sapi</h5>
                <p class="text-sm text-gray-500 mt-1">Data maksimal 3 bulan terakhir · sumber dari halaman Atur Bobot Pakan</p>
            </div>
        </div>
        <p class="text-sm text-gray-500 {{ $weightHistory->count() > 0 ? 'hidden' : '' }}" data-weight-empty-message>
            Belum ada data bobot sapi untuk ditampilkan. Input terlebih dahulu melalui halaman <strong>Atur Bobot Pakan</strong>.
        </p>
        <canvas id="weightChart" height="140" class="{{ $weightHistory->count() === 0 ? 'hidden' : '' }}"></canvas>
    </div>

</div>


{{-- CHART CONFIG --}}
<script>
    function buildTimeLabels(filter) {
        if (filter === 'day') {
            return Array.from({ length: 24 }, (_, i) => String(i).padStart(2, '0') + ':00');
        }
        if (filter === 'week') {
            return ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        }
        return ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
    }

    function determineBucketIndex(filter, date) {
        if (filter === 'day') {
            return date.getHours();
        }
        if (filter === 'week') {
            let index = date.getDay() - 1;
            return index < 0 ? 6 : index;
        }
        return Math.min(Math.floor((date.getDate() - 1) / 7), 3);
    }

    function generateFallbackValue(type) {
        const base = type === 'temperature' ? 27 : 70;
        const variance = type === 'temperature' ? 2.5 : 6;
        return Number((base + (Math.random() * variance * 2 - variance)).toFixed(1));
    }

    function buildSeries(series, filter, type) {
        const labels = buildTimeLabels(filter);
        const bucketCount = labels.length;
        const buckets = Array.from({ length: bucketCount }, () => []);

        series.forEach(item => {
            if (!item.recorded_at || item.value === undefined || item.value === null) return;
            const date = new Date(item.recorded_at);
            if (Number.isNaN(date.getTime())) return;
            const index = determineBucketIndex(filter, date);
            if (index < 0 || index >= bucketCount) return;
            buckets[index].push(Number(item.value));
        });

        const values = buckets.map(bucket => {
            if (bucket.length === 0) {
                return generateFallbackValue(type);
            }
            const avg = bucket.reduce((acc, val) => acc + val, 0) / bucket.length;
            return Number(avg.toFixed(1));
        });

        return { labels, values };
    }

    // Data suhu (sudah difilter di controller)
    const temperatureSeries = {!! json_encode($temperatureSeries->sortBy('recorded_at')->values()) !!};
    const tempSeriesData = buildSeries(temperatureSeries, '{{ $filterTemp }}', 'temperature');
    const tempLabels = tempSeriesData.labels;
    const temperatures = tempSeriesData.values;

    // Data kelembaban (sudah difilter di controller)
    const humiditySeries = {!! json_encode($humiditySeries->sortBy('recorded_at')->values()) !!};
    const humiditySeriesData = buildSeries(humiditySeries, '{{ $filterHumidity }}', 'humidity');
    const humidityLabels = humiditySeriesData.labels;
    const humidity = humiditySeriesData.values;

    const timelineSeries = {!! json_encode(($timelineSeries ?? collect())->values()) !!};
    const timelineLabels = timelineSeries.map(item => formatChartDate(item.date));

    // Feeding history - MENGGUNAKAN DATA PAKAN DARI NOTES (bobot pakan yang sudah diatur)
    const feedAvgSeries = timelineSeries.map(item => {
        const val = item.feed_avg !== null && item.feed_avg !== undefined ? Number(item.feed_avg) : 0;
        return isNaN(val) ? 0 : val;
    });
    const feedMinSeries = timelineSeries.map(item => {
        const val = item.feed_min !== null && item.feed_min !== undefined ? Number(item.feed_min) : 0;
        return isNaN(val) ? 0 : val;
    });
    const feedMaxSeries = timelineSeries.map(item => {
        const val = item.feed_max !== null && item.feed_max !== undefined ? Number(item.feed_max) : 0;
        return isNaN(val) ? 0 : val;
    });
    
    // Keep weight series for other charts
    const weightAvgSeries = timelineSeries.map(item => Number(item.weight_avg || 0));
    const weightMinSeries = timelineSeries.map(item => Number(item.weight_min || 0));
    const weightMaxSeries = timelineSeries.map(item => Number(item.weight_max || 0));

    // Line Chart generator dengan konfigurasi yang lebih baik
    function lineChart(ctx, chartLabels, label, data, color, yLabel) {
        return new Chart(ctx, {
            type: "line",
            data: {
                labels: chartLabels,
                datasets: [{
                    label: label,
                    data: data,
                    borderColor: color,
                    backgroundColor: color + "33",
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: color,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return yLabel + ': ' + context.parsed.y.toFixed(1);
                            }
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: false,
                        ticks: { 
                            font: { size: 12 },
                            callback: function(value) {
                                return value.toFixed(1);
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: { 
                        ticks: { 
                            font: { size: 11 },
                            maxRotation: 45,
                            minRotation: 0
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    lineChart(document.getElementById("tempChart"), tempLabels, "Suhu", temperatures, "#ff6b6b", "°C");
    lineChart(document.getElementById("humidityChart"), humidityLabels, "Kelembaban", humidity, "#4dabf7", "%");

    // Feeding chart - MENAMPILKAN BOBOT PAKAN YANG SUDAH DIATUR
    new Chart(document.getElementById("feedingChart"), {
        type: "bar",
        data: {
            labels: timelineLabels,
            datasets: [
                {
                    label: "Rata-rata Bobot Pakan (kg)",
                    data: feedAvgSeries,
                    backgroundColor: "#5DB996",
                    borderRadius: 10,
                    barPercentage: 0.6,
                    categoryPercentage: 0.7,
                },
                {
                    label: "Bobot Pakan Minimum (kg)",
                    data: feedMinSeries,
                    backgroundColor: "#ff6b6b",
                    borderRadius: 10,
                    barPercentage: 0.45,
                    categoryPercentage: 0.7,
                },
                {
                    label: "Bobot Pakan Maksimum (kg)",
                    data: feedMaxSeries,
                    backgroundColor: "#4dabf7",
                    borderRadius: 10,
                    barPercentage: 0.45,
                    categoryPercentage: 0.7,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { 
                legend: { display: true },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y.toFixed(1) + ' kg';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        font: { size: 12 },
                        callback: function(value) {
                            return value.toFixed(1) + ' kg';
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        font: { size: 11 },
                        maxRotation: 45,
                        minRotation: 0
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Weight Chart (data diambil dari server + endpoint /weight/data)
    const weightChartElement = document.getElementById("weightChart");
    const weightChartEmptyMessage = document.querySelector("[data-weight-empty-message]");
    let weightChartInstance = null;

    const initialWeightHistory = timelineSeries.map(item => ({
        date: item.date,
        avg: item.weight_avg,
        min: item.weight_min,
        max: item.weight_max,
    }));

    function formatChartDate(dateString) {
        const date = new Date(dateString);
        if (Number.isNaN(date.getTime())) {
            return dateString;
        }
        return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
    }

    function buildWeightDataset(records) {
        const labels = records.map(item => formatChartDate(item.date));
        const avgData = records.map(item => Number(item.avg ?? item.avg_weight ?? 0));
        const minData = records.map(item => Number(item.min ?? item.min_weight ?? 0));
        const maxData = records.map(item => Number(item.max ?? item.max_weight ?? 0));

        return { labels, avgData, minData, maxData };
    }

    function toggleWeightEmptyState(hasData) {
        if (weightChartEmptyMessage) {
            weightChartEmptyMessage.classList.toggle('hidden', hasData);
        }
        if (weightChartElement) {
            weightChartElement.classList.toggle('hidden', !hasData);
        }
    }

    function renderWeightChart(records) {
        if (!weightChartElement || typeof Chart === 'undefined') {
            console.warn('Weight chart element atau Chart.js tidak tersedia');
            return;
        }

        const dataset = buildWeightDataset(records);
        const hasData = dataset.labels.length > 0;
        toggleWeightEmptyState(hasData);

        if (!hasData) {
            if (weightChartInstance) {
                weightChartInstance.destroy();
                weightChartInstance = null;
            }
            return;
        }

        const config = {
            type: "line",
            data: {
                labels: dataset.labels,
                datasets: [
                    {
                        label: "Rata-rata Bobot (kg)",
                        data: dataset.avgData,
                        borderColor: "#5DB996",
                        backgroundColor: "#5DB99633",
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: "#5DB996",
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    },
                    {
                        label: "Bobot Minimum",
                        data: dataset.minData,
                        borderColor: "#ff6b6b",
                        backgroundColor: "transparent",
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 3,
                        fill: false,
                    },
                    {
                        label: "Bobot Maksimum",
                        data: dataset.maxData,
                        borderColor: "#4dabf7",
                        backgroundColor: "transparent",
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 3,
                        fill: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toFixed(1) + ' kg';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        ticks: {
                            font: { size: 12 },
                            callback: function(value) {
                                return value.toFixed(1) + ' kg';
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 11 },
                            maxRotation: 45,
                            minRotation: 0
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        };

        if (weightChartInstance) {
            weightChartInstance.destroy();
        }
        weightChartInstance = new Chart(weightChartElement, config);
    }

    function aggregateRawWeightData(records) {
        if (!Array.isArray(records)) return [];

        const grouped = {};
        const cutoff = new Date();
        cutoff.setMonth(cutoff.getMonth() - 3); // 3 bulan terakhir

        records.forEach(item => {
            if (!item.measured_at || typeof item.weight === 'undefined') return;
            const dateOnly = item.measured_at.substring(0, 10);
            const current = new Date(dateOnly);
            if (Number.isNaN(current.getTime())) return;
            if (current < cutoff) return;

            if (!grouped[dateOnly]) {
                grouped[dateOnly] = [];
            }
            grouped[dateOnly].push(Number(item.weight));
        });

        const aggregated = Object.keys(grouped)
            .sort()
            .map(date => {
                const values = grouped[date];
                const total = values.reduce((sum, val) => sum + val, 0);
                return {
                    date,
                    avg: total / values.length,
                    min: Math.min(...values),
                    max: Math.max(...values),
                };
            });

        const maxPoints = 6;
        if (aggregated.length > maxPoints) {
            return aggregated.slice(-maxPoints);
        }
        return aggregated;
    }

    async function refreshWeightChart() {
        try {
            const response = await fetch('{{ route('weight.data') }}', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!response.ok) throw new Error('HTTP ' + response.status);

            const payload = await response.json();
            const aggregated = aggregateRawWeightData(payload.data || []);
            renderWeightChart(aggregated);
        } catch (error) {
            console.error('Gagal memuat data bobot sapi', error);
        }
    }

    renderWeightChart(initialWeightHistory);
    refreshWeightChart();
</script>

{{-- ICONS --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const liveCards = document.querySelectorAll('[data-live-card]');

        liveCards.forEach(card => {
            let currentIndex = parseInt(card.dataset.index) || 0;
            const sequence = JSON.parse(card.dataset.sequence || '[]');
                    const type = card.dataset.liveCard;
            const unit = type === 'temperature' ? '°C' : '%';

            // Update card with data from sequence
            const updateCard = (data) => {
                    const valueTarget = card.querySelector('[data-live-value]');
                    const metaTarget = card.querySelector('[data-live-meta]');
                const titleTarget = card.querySelector('p.text-lg');
                const iconTarget = card.querySelector('.stat-icon').parentElement;

                if (valueTarget && data.value !== undefined) {
                    valueTarget.textContent = `${Number(data.value).toFixed(1)}${unit}`;
                }
                
                if (metaTarget) {
                    metaTarget.textContent = data.status || 'Belum ada data';
                }
                
                // Update warning styling - same shape, different color + blinking
                if (data.isWarning) {
                    card.classList.add('warning-card', 'blink');
                    if (valueTarget) {
                        valueTarget.classList.remove('text-primary-dark', 'text-primary');
                        valueTarget.classList.add('text-red-600');
                    }
                    if (titleTarget) {
                        titleTarget.classList.remove('text-gray-700');
                        titleTarget.classList.add('text-red-700');
                    }
                    if (metaTarget) {
                        metaTarget.classList.remove('text-gray-500');
                        metaTarget.classList.add('text-red-600', 'font-semibold');
                    }
                    if (iconTarget) {
                        iconTarget.classList.remove('text-primary-dark', 'text-primary');
                        iconTarget.classList.add('text-red-600');
                    }
                } else {
                    card.classList.remove('warning-card', 'blink');
                    if (valueTarget) {
                        valueTarget.classList.remove('text-red-600');
                        valueTarget.classList.add(type === 'temperature' ? 'text-primary-dark' : 'text-primary');
                    }
                    if (titleTarget) {
                        titleTarget.classList.remove('text-red-700');
                        titleTarget.classList.add('text-gray-700');
                    }
                    if (metaTarget) {
                        metaTarget.classList.remove('text-red-600', 'font-semibold');
                        metaTarget.classList.add('text-gray-500');
                    }
                    if (iconTarget) {
                        iconTarget.classList.remove('text-red-600');
                        iconTarget.classList.add(type === 'temperature' ? 'text-primary-dark' : 'text-primary');
                    }
                }
            };
            
            // Initialize with first data
            if (sequence.length > 0) {
                updateCard(sequence[0]);
            }
            
            card.addEventListener('mouseenter', () => {
                if (card.dataset.loading === 'true') return;
                if (sequence.length === 0) return;
                
                card.dataset.loading = 'true';
                
                // Move to next index (circular)
                currentIndex = (currentIndex + 1) % sequence.length;
                card.dataset.index = currentIndex;
                
                // Get next data from sequence
                const nextData = sequence[currentIndex];
                updateCard(nextData);
                
                    setTimeout(() => {
                        card.dataset.loading = 'false';
                }, 300);
            });
        });
    });
</script>

@endsection

@section('footer')
{{-- Footer tidak ditampilkan di halaman monitoring --}}
@endsection
