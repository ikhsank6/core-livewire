@php
    $dateId = $row['date']->copy()->locale('id');
    $dayNum = $dateId->format('d');
    $monthAbbr = $dateId->translatedFormat('M');
    $dayName = $dateId->translatedFormat('l');
    $fullDate = $dateId->translatedFormat('d M Y');
@endphp

<div @isset($index) x-show="show({{ $index }})" @if($index >= ($perPage ?? 5)) x-cloak @endif @endisset
     class="group grid grid-cols-1 gap-3 px-4 py-4 transition-colors hover:bg-sunken/45 sm:px-5 md:grid-cols-[12rem_minmax(0,1fr)_13rem_10.5rem] md:items-center md:gap-6"
     role="row">

    {{-- Date + Mobile Status --}}
    <div class="flex items-center justify-between gap-3 md:contents">
        <time role="cell" datetime="{{ $row['date']->toDateString() }}" class="flex items-center gap-3 md:order-1">
            <span class="flex h-11 w-11 shrink-0 flex-col items-center justify-center rounded-lg border border-line bg-sunken/80 text-center leading-none transition-colors group-hover:border-brand/30 group-hover:bg-surface">
                <span class="text-[0.9375rem] font-extrabold tabular-nums text-ink">{{ $dayNum }}</span>
                <span class="mt-0.5 text-[0.625rem] font-bold uppercase tracking-wider text-accent-text">{{ $monthAbbr }}</span>
            </span>
            <span class="min-w-0">
                <span class="block text-sm font-bold leading-tight text-ink">{{ $dayName }}</span>
                <span class="mt-0.5 block text-xs font-medium tabular-nums text-muted">{{ $fullDate }}</span>
            </span>
        </time>

        <div role="cell" class="md:order-4 md:justify-self-start">
            @if($row['open'])
                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-500/25 bg-emerald-500/10 px-3 py-1 text-xs font-bold text-emerald-700 dark:border-emerald-400/25 dark:bg-emerald-500/15 dark:text-emerald-300">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400" aria-hidden="true"></span>
                    Dibuka
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 rounded-full border border-line bg-sunken px-3 py-1 text-xs font-semibold text-muted">
                    @svg('heroicon-o-lock-closed', 'h-3.5 w-3.5 shrink-0')
                    Belum dibuka
                </span>
            @endif
        </div>
    </div>

    {{-- Scheme --}}
    <div role="cell" class="min-w-0 md:order-2">
        <span class="block text-[0.9375rem] font-bold leading-snug text-ink sm:text-base">{{ $row['skema'] }}</span>
        @if(!empty($field))
            <span class="mt-0.5 block text-xs font-medium text-muted">Bidang {{ $field }}</span>
        @endif
    </div>

    {{-- Test venue (TUK) --}}
    <div role="cell" class="flex items-center gap-1.5 text-sm font-medium text-body md:order-3">
        @svg('heroicon-o-map-pin', 'h-4 w-4 shrink-0 text-muted')
        <span>{{ $row['venue'] }}</span>
    </div>
</div>
