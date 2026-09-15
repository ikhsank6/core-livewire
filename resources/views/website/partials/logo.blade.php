{{--
    Props:
    $containerClass (string, optional)
    $textClass      (string, optional)
    $mark           (string, optional) – 'ink' (default) | 'bone'
--}}
@php
    $mark = $mark ?? 'ink';
    $markBg = $mark === 'bone' ? 'bg-bone' : 'bg-ink';
    $markFg = $mark === 'bone' ? 'text-ink' : 'text-bone';
@endphp

<div class="flex items-center gap-2.5 {{ $containerClass ?? '' }}">
    @if($aboutUs?->logo)
        <img src="{{ Storage::url($aboutUs->logo) }}"
             alt="{{ $aboutUs->company_name ?? config('app.name') }}"
             class="h-9 w-auto">
    @else
        {{-- Doorway mark: one door, marigold threshold. --}}
        <span class="shrink-0 w-9 h-9 {{ $markBg }} rounded-[0.6rem] flex items-center justify-center">
            <svg class="w-5 h-5 {{ $markFg }}" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                <path d="M4 17V7.2a2 2 0 0 1 .9-1.66l4-2.67a2 2 0 0 1 2.2 0l4 2.67A2 2 0 0 1 16 7.2V17"
                      stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                <path d="M2.5 17h15" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                <circle cx="12.3" cy="11.5" r="1.15" fill="#E8A317"/>
            </svg>
        </span>
    @endif

    <span class="{{ $textClass ?? 'font-display font-bold text-[1.0625rem] tracking-tight' }}"
          style="font-variation-settings:'wdth' 108;">
        {{ $aboutUs->company_name ?? config('app.name') }}
    </span>
</div>
