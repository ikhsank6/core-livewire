{{--
    Props:
    $eyebrow  (string)  – small label above title
    $title    (string)  – main heading (HTML allowed)
    $subtitle (string)  – optional sub-text
    $align    (string)  – 'center' (default) | 'left'
--}}
@php $align = $align ?? 'center'; @endphp

<div class="{{ $align === 'left' ? 'text-left' : 'text-center' }} mb-12">
    @if(!empty($eyebrow))
        <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-primary/10 dark:bg-primary/20 text-primary rounded-full text-sm font-semibold mb-4">
            <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
            {{ $eyebrow }}
        </span>
    @endif

    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 dark:text-white leading-tight mb-4">
        {!! $title !!}
    </h2>

    @if(!empty($subtitle))
        <p class="{{ $align === 'left' ? '' : 'max-w-2xl mx-auto' }} text-lg text-slate-500 dark:text-slate-400">
            {{ $subtitle }}
        </p>
    @endif
</div>
