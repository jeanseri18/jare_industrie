import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const THEME = {
    colors: ['#111827', '#ff7200', '#6b7280', '#374151', '#9ca3af', '#d1d5db'],
    red: '#ff7200',
    black: '#111827',
    gray: '#6b7280',
    grayLight: '#e5e7eb',
    white: '#ffffff',
};

function parseJson(value, fallback) {
    try {
        return value ? JSON.parse(value) : fallback;
    } catch {
        return fallback;
    }
}

function formatAxisValue(value, isCurrency) {
    const n = Number(value);
    if (!isCurrency) {
        return Number.isInteger(n) ? n : n.toLocaleString('fr-FR');
    }
    if (n >= 1_000_000) {
        return (n / 1_000_000).toFixed(1) + ' M';
    }
    if (n >= 1_000) {
        return (n / 1_000).toFixed(0) + ' K';
    }
    return n.toLocaleString('fr-FR');
}

function baseOptions(isCurrency = false) {
    return {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: THEME.black,
                    font: { size: 12, weight: '500' },
                    usePointStyle: true,
                    padding: 16,
                },
            },
            tooltip: {
                backgroundColor: THEME.black,
                titleColor: THEME.white,
                bodyColor: THEME.white,
                borderColor: THEME.gray,
                borderWidth: 1,
                padding: 12,
                cornerRadius: 8,
            },
        },
        scales: {
            x: {
                grid: { color: THEME.grayLight, drawBorder: false },
                ticks: { color: THEME.gray, font: { size: 11 } },
            },
            y: {
                grid: { color: THEME.grayLight, drawBorder: false },
                ticks: {
                    color: THEME.gray,
                    font: { size: 11 },
                    callback: (v) => formatAxisValue(v, isCurrency),
                },
            },
        },
    };
}

function initChart(canvas) {
    const type = canvas.dataset.chartType;
    const labels = parseJson(canvas.dataset.chartLabels, []);
    const values = parseJson(canvas.dataset.chartValues, []);
    const datasetsRaw = parseJson(canvas.dataset.chartDatasets, null);
    const isCurrency = canvas.dataset.chartCurrency === '1';
    const colors = THEME.colors;

    if (type === 'doughnut') {
        new Chart(canvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors.slice(0, Math.max(labels.length, 1)),
                    borderColor: THEME.white,
                    borderWidth: 3,
                    hoverOffset: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: THEME.black,
                            padding: 16,
                            usePointStyle: true,
                            font: { size: 12, weight: '500' },
                        },
                    },
                    tooltip: {
                        backgroundColor: THEME.black,
                        titleColor: THEME.white,
                        bodyColor: THEME.white,
                    },
                },
            },
        });
        return;
    }

    if (type === 'line') {
        const datasets = datasetsRaw
            ? datasetsRaw.map((ds, i) => ({
                label: ds.label,
                data: ds.values,
                borderColor: colors[i % colors.length],
                backgroundColor: colors[i % colors.length] + '20',
                tension: 0.35,
                fill: true,
                pointBackgroundColor: colors[i % colors.length],
                pointBorderColor: THEME.white,
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
            }))
            : [{
                label: canvas.dataset.chartLabel || 'Valeurs',
                data: values,
                borderColor: THEME.red,
                backgroundColor: THEME.red + '20',
                tension: 0.35,
                fill: true,
                pointBackgroundColor: THEME.red,
                pointBorderColor: THEME.white,
                pointBorderWidth: 2,
                pointRadius: 4,
            }];

        new Chart(canvas.getContext('2d'), {
            type: 'line',
            data: { labels, datasets },
            options: {
                ...baseOptions(isCurrency),
                plugins: {
                    ...baseOptions(isCurrency).plugins,
                    legend: { position: 'bottom', labels: baseOptions().plugins.legend.labels },
                },
            },
        });
        return;
    }

    if (type === 'bar') {
        const barColors = labels.map((_, i) => (i % 2 === 0 ? THEME.black : THEME.red));

        new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: canvas.dataset.chartLabel || 'Valeurs',
                    data: values,
                    backgroundColor: barColors,
                    borderRadius: 8,
                    borderSkipped: false,
                    maxBarThickness: 48,
                }],
            },
            options: {
                ...baseOptions(isCurrency),
                plugins: {
                    ...baseOptions(isCurrency).plugins,
                    legend: { display: false },
                },
            },
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-chart-type]').forEach(initChart);
});
