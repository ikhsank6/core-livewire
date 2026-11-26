{{--
    Reusable horizontal bar chart card: one series, one hue, values written at the bar ends.
    The drawing lives in resources/js/app.js (window.renderBarChart).

    <x-ui.bar-chart title="Pengguna per Role" :items="$rows" unit="pengguna" label-heading="Role" />

    Props:
    title        (string)
    items        (array|Collection) of ['label' => string, 'value' => int]
    subtitle     (string, optional)
    unit         (string, optional)  – word after a value in the tooltip and table
    labelHeading (string, optional)  – first column of the screen reader table
    collapsible  (bool, optional)    – default true
    empty        (string, optional)  – text when there are no items
    minHeight    (int, optional)     – plot height in px before rows need more; default 200
--}}
@props([
    'title',
    'items' => [],
    'subtitle' => null,
    'unit' => 'data',
    'labelHeading' => 'Kategori',
    'collapsible' => true,
    'empty' => 'Belum ada data.',
    'minHeight' => 200,
])

@php
    $rows = collect($items)->values();
@endphp

<x-ui.chart-card :title="$title" :subtitle="$subtitle" :collapsible="$collapsible" {{ $attributes }}>
    <x-slot name="icon">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h10M4 12h16M4 18h7"/>
        </svg>
    </x-slot>

    @if($rows->isEmpty())
        <p class="px-6 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">{{ $empty }}</p>
    @else
        <div class="px-4 py-3">
            {{-- wire:ignore keeps Livewire from wiping the chart; height grows with the number of rows --}}
            <div wire:ignore x-ref="chart" class="w-full" aria-hidden="true"
                 style="height: {{ max((int) $minHeight, $rows->count() * 44 + 24) }}px"
                 x-init="$nextTick(() => window.renderBarChart?.($el, @js($rows), @js($unit)))"></div>
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
