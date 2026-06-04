{{--
    Props:
    $eyebrow  (string, optional) – kecil di atas heading
    $title    (string)           – heading utama (bisa berisi HTML untuk <span> gradient)
    $subtitle (string, optional) – deskripsi di bawah heading
    $align    (string)           – 'center' (default) | 'left'
    $light    (bool, optional)   – true jika dalam dark section (teks putih)
--}}
@php
    $align = $align ?? 'center';
    $light = $light ?? false;
    $centerClass = $align === 'center' ? 'text-center items-center' : 'text-left items-start';
@endphp

<div class="flex flex-col {{ $centerClass }} gap-3 mb-12">
    {{-- Eyebrow pill --}}
    @if(!empty($eyebrow))
        <span class="{{ $light ? 'eyebrow-pill-light' : 'eyebrow-pill' }}">
            <span class="eyebrow-dot"></span>
            {{ $eyebrow }}
        </span>
    @endif

    {{-- Main heading --}}
    <h2 class="font-heading font-bold leading-tight
               {{ $light ? 'text-white' : 'text-slate-900 dark:text-white' }}
               text-3xl sm:text-4xl lg:text-[2.75rem]">
        {!! $title !!}
    </h2>

    {{-- Subtitle --}}
    @if(!empty($subtitle))
        <p class="text-base leading-relaxed max-w-2xl
                  {{ $align === 'center' ? 'mx-auto' : '' }}
                  {{ $light ? 'text-white/70' : 'text-slate-500 dark:text-slate-400' }}">
            {{ $subtitle }}
        </p>
    @endif
</div>

@once
<style>
.eyebrow-pill-light {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.375rem 1rem;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 9999px;
    color: #fff;
    font-family: "Quicksand", sans-serif;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
}
</style>
@endonce
