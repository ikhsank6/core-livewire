<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Dashboard</flux:breadcrumbs.item>
</x-slot>

<div class="space-y-6">

    {{-- ===================== WELCOME CARD ===================== --}}
    <div class="flex items-start gap-3 p-4 bg-blue-50 dark:bg-[#1a2238] border border-blue-200 dark:border-[#2b3a61] text-blue-800 dark:text-blue-200 rounded-2xl shadow-sm" role="alert">
        <svg class="flex-shrink-0 w-5 h-5 mt-0.5 text-blue-600 dark:text-blue-400" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <div class="text-sm">
            <span class="font-semibold text-blue-900 dark:text-blue-100">Selamat datang kembali, {{ auth()->user()->name }}!</span>
            <div class="mt-1 text-xs text-blue-700 dark:text-blue-300/90 leading-relaxed">
                Anda masuk sebagai <span class="font-semibold text-blue-800 dark:text-blue-200">{{ auth()->user()->role->name ?? '-' }}</span>. Gunakan sidebar untuk berpindah antar halaman.
            </div>
        </div>
    </div>

    {{-- ===================== STAT CARDS ===================== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm flex items-center gap-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-blue-600 text-white shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Total Pengguna</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-white mt-0.5">{{ $totalUsers }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm flex items-center gap-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-500 text-white shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Aktif</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-white mt-0.5">{{ $activeUsers }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm flex items-center gap-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-zinc-400 text-white shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Pending</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-white mt-0.5">{{ $pendingUsers }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm flex items-center gap-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-red-500 text-white shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Ditangguhkan</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-white mt-0.5">{{ $suspendedUsers }}</p>
            </div>
        </div>
    </div>

    {{-- ===================== CHART CARD (Collapsible) ===================== --}}
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden"
        x-data="{ open: true }">

        {{-- Header / Toggle --}}
        <div @click="open = !open; if(open) { $nextTick(() => window.reflowUserChart && window.reflowUserChart()) }"
            class="flex items-center justify-between px-6 py-4 border-b border-zinc-100 dark:border-zinc-800 cursor-pointer select-none hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-600/10 dark:bg-blue-600/20 text-blue-600 dark:text-blue-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Distribusi Pengguna</h3>
                    <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5">
                        Berdasarkan status akun — Total {{ $totalUsers }} pengguna
                    </p>
                </div>
            </div>
            <svg class="w-4 h-4 text-zinc-400 transition-transform duration-200"
                :class="{ 'rotate-180': open }"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        {{-- Body --}}
        <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">

            <div class="relative">
                {{-- Chart container — wire:ignore prevents Livewire re-render --}}
                <div wire:ignore id="chart-user-distribution" class="h-80 w-full"></div>

                {{-- Donut center label --}}
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none" style="padding-bottom:2rem">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $totalUsers }}</p>
                        <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5">Total</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

{{-- ===================== HIGHCHARTS INIT ===================== --}}
{{-- All chart JS lives here — NO complex JS inside x-data attributes --}}
<script>
(function () {
    var chartData = {
        active:    @json($activeUsers),
        pending:   @json($pendingUsers),
        suspended: @json($suspendedUsers)
    };

    function buildChart() {
        var el = document.getElementById('chart-user-distribution');
        if (!el || !window.Highcharts) return;

        var dark       = document.documentElement.classList.contains('dark');
        var textColor  = dark ? '#a1a5b7' : '#4b5675';
        var tooltipBg  = dark ? '#1e1e2d' : '#ffffff';
        var tooltipBdr = dark ? '#2d2d3a' : '#e8e8e8';
        var chart = Highcharts.chart(el, {
            chart: {
                type: 'pie',
                backgroundColor: 'transparent',
                style: { fontFamily: 'inherit' },
                animation: { duration: 600 }
            },
            title:    { text: null },
            subtitle: { text: null },
            tooltip: {
                borderRadius: 8,
                backgroundColor: tooltipBg,
                borderColor: tooltipBdr,
                shadow: false,
                style: { color: textColor, fontSize: '13px' },
                pointFormat: '<b>{point.y}</b> pengguna ({point.percentage:.1f}%)'
            },
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
                    states: {
                        hover:  { brightness: 0.08, halo: { size: 0 } },
                        select: { color: null, brightness: -0.05 }
                    }
                }
            },
            legend: {
                enabled: true,
                align: 'center',
                verticalAlign: 'bottom',
                layout: 'horizontal',
                symbolRadius: 4,
                symbolWidth: 12,
                symbolHeight: 12,
                itemDistance: 20,
                margin: 0,
                itemStyle: {
                    color: textColor,
                    fontSize: '13px',
                    fontWeight: '500',
                    fontFamily: 'inherit',
                    cursor: 'default'
                },
                itemHoverStyle: { color: textColor }
            },
            series: [{
                name: 'Pengguna',
                colorByPoint: true,
                data: [
                    { name: 'Aktif',        y: chartData.active,    color: '#1b84ff' },
                    { name: 'Pending',      y: chartData.pending,   color: '#99a1b7' },
                    { name: 'Ditangguhkan', y: chartData.suspended, color: '#f8285a' }
                ]
            }],
            credits:      { enabled: false },
            accessibility: { enabled: false }
        });

        // Expose reflow helper so Alpine toggle can call it
        window.reflowUserChart = function () {
            if (chart) chart.reflow();
        };
    }

    // Run once on initial page load or after Livewire navigation
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', buildChart);
    } else {
        buildChart();
    }

    // Re-init after Livewire SPA navigation
    document.addEventListener('livewire:navigated', buildChart);
})();
</script>
