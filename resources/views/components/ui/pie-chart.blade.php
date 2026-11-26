{{--
    Reusable donut chart card with the total in the middle and a legend below.
    The drawing lives in resources/js/app.js (window.renderPieChart).

    <x-ui.pie-chart title="Distribusi Pengguna" :items="$rows" unit="pengguna" label-heading="Status" />

    Props:
    title        (string)
    items        (array|Collection) of ['label' => string, 'value' => int, 'color' => '#hex' (optional)]
                 Pass `color` when it carries meaning (a status); otherwise a fixed palette order is used.
    subtitle     (string, optional)
    unit         (string, optional)  – word after a value in the tooltip and table
    labelHeading (string, optional)  – first column of the screen reader table
    total        (int, optional)     – number in the middle; defaults to the sum of the values
    collapsible  (bool, optional)    – default true
    empty        (string, optional)  – text when every value is zero or there are no items
--}}
@props([
    'title',
    'items' => [],
    'subtitle' => null,
    'unit' => 'data',
    'labelHeading' => 'Kategori',
    'total' => null,
    'collapsible' => true,
    'empty' => 'Belum ada data.',
])

@php
    $rows = collect($items)->values();
    $sum = $total ?? $rows->sum('value');
@endphp

<x-ui.chart-card :title="$title" :subtitle="$subtitle" :collapsible="$collapsible" {{ $attributes }}>
    <x-slot name="icon">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
        </svg>
    </x-slot>

    @if($rows->sum('value') <= 0)
        <p class="px-6 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">{{ $empty }}</p>
    @else
        <div class="relative">
            {{-- wire:ignore keeps Livewire from wiping the chart --}}
            <div wire:ignore x-ref="chart" class="h-80 w-full" aria-hidden="true"
                 x-init="$nextTick(() => window.renderPieChart?.($el, @js($rows), @js($unit)))"></div>

            {{-- Donut centre; the bottom padding matches the plot centre above the legend --}}
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none pb-8" aria-hidden="true">
                <div class="text-center">
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $sum }}</p>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Total</p>
                </div>
            </div>
        </div>

        {{-- Same numbers as a table for screen readers. The wrapper is hidden, not the table: a table
             ignores the 1px size of sr-only and would still stretch the page. --}}
        <div class="sr-only">
            <table>
                <caption>{{ $title }}</caption>
                <thead><tr><th scope="col">{{ $labelHeading }}</th><th scope="col">Jumlah {{ $unit }}</th></tr></thead>
                <tbody>
                    @foreach($rows as $row)
                        <tr><th scope="row">{{ $row['label'] }}</th><td>{{ $row['value'] }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-ui.chart-card>
