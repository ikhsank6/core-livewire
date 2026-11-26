{{--
    Card shell shared by the chart components: title row (collapsible) and body.
    Any element marked with x-ref="chart" in the body is reflowed when the card reopens.

    Props:
    title       (string)
    subtitle    (string, optional)
    collapsible (bool, optional)  – default true
    Slots: icon (optional, an <svg>), default (the body)
--}}
@props([
    'title',
    'subtitle' => null,
    'collapsible' => true,
])

<div {{ $attributes->class('bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden') }}
     x-data="{ open: true }">

    <{{ $collapsible ? 'button type=button' : 'div' }}
        @if($collapsible)
            @click="open = !open; if (open) $nextTick(() => $refs.chart?._chart?.reflow())"
            :aria-expanded="open ? 'true' : 'false'"
        @endif
        class="flex w-full items-center justify-between gap-3 px-6 py-4 text-left border-b border-zinc-100 dark:border-zinc-800 {{ $collapsible ? 'cursor-pointer select-none hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors' : '' }}">
        <span class="flex items-center gap-3 min-w-0">
            @isset($icon)
                <span class="flex items-center justify-center w-8 h-8 shrink-0 rounded-lg bg-blue-600/10 dark:bg-blue-600/20 text-blue-600 dark:text-blue-400" aria-hidden="true">
                    {{ $icon }}
                </span>
            @endisset
            <span class="min-w-0">
                <span class="block text-sm font-semibold text-zinc-900 dark:text-white">{{ $title }}</span>
                @if($subtitle)
                    <span class="block text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">{{ $subtitle }}</span>
                @endif
            </span>
        </span>
        @if($collapsible)
            <svg class="w-4 h-4 shrink-0 text-zinc-400 transition-transform duration-200"
                :class="{ 'rotate-180': open }"
                fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        @endif
    </{{ $collapsible ? 'button' : 'div' }}>

    <div x-show="open" x-transition.opacity>
        {{ $slot }}
    </div>
</div>
