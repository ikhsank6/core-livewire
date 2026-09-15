{{--
    Props:
    $eyebrow  (string, optional) – mono micro-label; use only for real metadata
    $title    (string)           – may contain HTML (e.g. <em> for the accent word)
    $subtitle (string, optional)
    $align    (string)           – 'left' (default) | 'center'
    $light    (bool, optional)   – true when sitting on an ink ground
--}}
@php
    $align = $align ?? 'left';
    $light = $light ?? false;
    $alignClass = $align === 'center' ? 'text-center items-center' : 'text-left items-start';
@endphp

<div class="flex flex-col {{ $alignClass }} gap-4 mb-12">
    @if(!empty($eyebrow))
        <span class="t-micro flex items-center gap-2.5 {{ $light ? 'text-marigold' : 'text-moss dark:text-bone-dim' }}">
            <span class="w-5 h-px {{ $light ? 'bg-marigold' : 'bg-marigold' }}"></span>
            {{ $eyebrow }}
        </span>
    @endif

    <h2 class="t-section {{ $light ? 'text-bone' : 'text-ink dark:text-bone' }}">
        {!! $title !!}
    </h2>

    @if(!empty($subtitle))
        <p class="t-lead {{ $align === 'center' ? 'mx-auto' : '' }}
                  {{ $light ? 'text-bone-dim' : 'text-moss dark:text-bone-dim' }}">
            {{ $subtitle }}
        </p>
    @endif
</div>
