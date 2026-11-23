{{--
    Props:
    $textClass (string, optional) – overrides the wordmark colour/size
    The mark is a doorway: the leaf swings open a little when the parent
    carries the `group` class and is hovered or focused.
--}}
<span class="flex items-center gap-2.5">
    @if($aboutUs?->logo)
        <img src="{{ Storage::url($aboutUs->logo) }}"
             alt="{{ $aboutUs->company_name ?? config('app.name') }}"
             class="h-9 w-auto">
    @else
        <svg class="w-9 h-9 shrink-0" viewBox="0 0 36 36" fill="none" aria-hidden="true">
            <path d="M5 33V16C5 8.82 10.82 3 18 3s13 5.82 13 13v17" stroke="#FFB81C" stroke-width="2.4" stroke-linecap="round"/>
            <path d="M2.5 33h31" stroke="#FFB81C" stroke-width="2.4" stroke-linecap="round"/>
            <path d="M18 8c4.4 0 8 3.6 8 8v17H10V16c0-4.4 3.6-8 8-8Z" fill="#FFB81C"
                  style="transform-origin: 10px 33px; transition: transform 380ms cubic-bezier(.3,1.2,.4,1);"
                  class="group-hover:[transform:scaleX(0.52)] group-focus-visible:[transform:scaleX(0.52)]"/>
        </svg>
    @endif

    <span class="{{ $textClass ?? 'font-display font-bold text-xl tracking-tight text-white' }}"
          style="font-variation-settings: 'wdth' 90;">
        {{ $aboutUs->company_name ?? config('app.name') }}
    </span>
</span>
