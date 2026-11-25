{{--
    Props:
    $textClass (string, optional) – overrides the wordmark colour/size
    The mark is a competence seal: a serrated ring around a gold disc with a check.
    The check redraws when the parent carries the `group` class and is hovered or focused.
    This is a placeholder mark until the institution uploads its own logo.
--}}
<span class="flex items-center gap-2.5">
    @if($aboutUs?->logo)
        <img src="{{ Storage::url($aboutUs->logo) }}"
             alt="{{ $aboutUs->company_name ?? config('app.name') }}"
             class="h-9 w-auto">
    @else
        <svg class="w-9 h-9 shrink-0" viewBox="0 0 36 36" fill="none" aria-hidden="true">
            <circle cx="18" cy="18" r="16" stroke="#E7BA4B" stroke-width="2.4" stroke-linecap="round" stroke-dasharray="0.1 3.6"/>
            <circle cx="18" cy="18" r="12.5" fill="#E7BA4B"/>
            <path class="seal-check" d="M11.8 18.4l4.4 4.4 8.2-9.2" stroke="#14213A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    @endif

    <span class="{{ $textClass ?? 'font-extrabold text-lg tracking-tight text-ink' }}">
        {{ $aboutUs->company_name ?? config('app.name') }}
    </span>
</span>
