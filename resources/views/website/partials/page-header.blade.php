{{--
    Props:
    $title      (string)
    $breadcrumb (string)  – current page label
    $subtitle   (string, optional)
--}}
<section class="relative isolate overflow-hidden border-b border-line">
    <div class="rosette -right-24 -top-28 -z-10 w-[22rem] max-w-none text-ink/[0.06]" aria-hidden="true"></div>

    <div class="wrap py-5 md:py-7">
        <nav aria-label="Remah roti">
            <ol class="flex items-center gap-3 text-sm">
                <li><a href="{{ route('landing') }}" class="inline-flex min-h-11 items-center text-muted md:min-h-8 underline decoration-transparent underline-offset-4 transition-colors hover:text-ink hover:decoration-current">Beranda</a></li>
                <li aria-hidden="true" class="text-muted/60">/</li>
                <li class="font-bold text-ink" aria-current="page">{{ $breadcrumb }}</li>
            </ol>
        </nav>

        <h1 class="title mt-1 max-w-[28ch] text-[clamp(1.75rem,3.4vw,2.5rem)]">{{ $title }}</h1>

        @if(!empty($subtitle))
            <p class="mt-2 max-w-[64ch] text-base leading-relaxed text-body">{{ $subtitle }}</p>
        @endif
    </div>
</section>
