import './bootstrap';
import Highcharts from 'highcharts';

// Expose Highcharts globally so Alpine x-init and inline scripts can use it
window.Highcharts = Highcharts;

// Charts behind <x-ui.bar-chart> and <x-ui.pie-chart>. Colours are read from the theme at draw time,
// so every chart remembers how to draw itself and is redrawn when the theme changes.
const drawChart = (el, options) => {
    el._draw = () => {
        el._chart?.destroy();
        el._chart = Highcharts.chart(el, options());
    };
    el.dataset.chart = '';
    el._draw();

    return el._chart;
};

let chartsDrawnDark = document.documentElement.classList.contains('dark');

new MutationObserver(() => {
    const dark = document.documentElement.classList.contains('dark');

    if (dark === chartsDrawnDark) return;

    chartsDrawnDark = dark;
    document.querySelectorAll('[data-chart]').forEach((el) => el._draw?.());
}).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

const chartTheme = () => {
    const dark = document.documentElement.classList.contains('dark');
    const ink = dark ? '#a1a5b7' : '#4b5675';

    return {
        dark,
        ink,
        base: {
            title: { text: null },
            credits: { enabled: false },
            accessibility: { enabled: false }, // each component ships its own screen reader table
        },
        tooltip: {
            borderRadius: 8,
            backgroundColor: dark ? '#1e1e2d' : '#ffffff',
            borderColor: dark ? '#2d2d3a' : '#e8e8e8',
            shadow: false,
            style: { color: ink, fontSize: '13px' },
        },
    };
};

const chartBox = (type) => ({ type, backgroundColor: 'transparent', style: { fontFamily: 'inherit' }, animation: { duration: 500 } });

// Horizontal bars: magnitude per category, one series in one hue (logo blue, its lighter step on dark),
// values at the bar ends so no value axis or legend is needed.
window.renderBarChart = (el, items, unit) => drawChart(el, () => {
    const { dark, ink, base, tooltip } = chartTheme();

    return {
        ...base,
        chart: chartBox('bar'),
        xAxis: {
            categories: items.map((item) => item.label),
            lineColor: dark ? '#2d2d3a' : '#d9dce3',
            tickWidth: 0,
            labels: { style: { color: ink, fontSize: '13px' } },
        },
        yAxis: { title: { text: null }, allowDecimals: false, min: 0, gridLineWidth: 0, labels: { enabled: false } },
        tooltip: { ...tooltip, headerFormat: '<span>{point.key}</span><br/>', pointFormat: `<b>{point.y}</b> ${unit}` },
        plotOptions: {
            bar: {
                color: dark ? '#4BA5DA' : '#2D68A7',
                maxPointWidth: 22,
                borderWidth: 0,
                borderRadius: { radius: 4, where: 'end' },
                dataLabels: { enabled: true, crop: false, overflow: 'allow', style: { color: ink, fontSize: '13px', fontWeight: '600', textOutline: 'none' } },
            },
        },
        legend: { enabled: false },
        series: [{ name: unit, data: items.map((item) => item.value) }],
    };
});

// Fixed order for slices without their own colour (never reshuffled by value).
const SLICE_COLORS = {
    light: ['#257de0', '#f47230', '#00b894', '#f2a600', '#ee7fac', '#008a17', '#6a4ce2', '#ec4647'],
    dark: ['#338ced', '#e76624', '#00a888', '#d28e05', '#e24d8b', '#0aac23', '#9887ef', '#eb6263'],
};

// Donut: share of a whole, with a legend; the total is drawn by the Blade component.
window.renderPieChart = (el, items, unit) => drawChart(el, () => {
    const { dark, ink, base, tooltip } = chartTheme();
    const palette = SLICE_COLORS[dark ? 'dark' : 'light'];

    return {
        ...base,
        chart: chartBox('pie'),
        tooltip: { ...tooltip, pointFormat: `<b>{point.y}</b> ${unit} ({point.percentage:.1f}%)` },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                innerSize: '65%',
                size: '75%',
                center: ['50%', '45%'],
                borderWidth: 0,
                borderRadius: 6,
                dataLabels: { enabled: false },
                showInLegend: true,
                states: { hover: { brightness: 0.08, halo: { size: 0 } }, select: { color: null, brightness: -0.05 } },
            },
        },
        legend: {
            enabled: true,
            symbolRadius: 4,
            symbolWidth: 12,
            symbolHeight: 12,
            itemDistance: 20,
            margin: 0,
            itemStyle: { color: ink, fontSize: '13px', fontWeight: '500', cursor: 'default' },
            itemHoverStyle: { color: ink },
        },
        series: [{
            name: unit,
            colorByPoint: true,
            data: items.map((item, i) => ({ name: item.label, y: item.value, color: item.color ?? palette[i % palette.length] })),
        }],
    };
});

// Re-initialize Flowbite components after Livewire navigation
document.addEventListener('livewire:navigated', () => {
    if (typeof initFlowbite === 'function') {
        initFlowbite();
    }
});
