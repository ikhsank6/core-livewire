{{--
    Props:
    $title      (string)
    $breadcrumb (string)  – current page label
    $subtitle   (string, optional)
--}}
<section class="relative isolate overflow-hidden border-b border-line">
    <div class="rosette -right-28 -top-32 -z-10 w-[30rem] max-w-none text-ink/[0.06]" aria-hidden="true"></div>

    <div class="wrap py-14 md:py-20">
        <nav aria-label="Remah roti">
            <ol class="flex items-center gap-3 text-sm">
                <li><a href="{{ route('landing') }}" class="inline-flex min-h-11 items-center text-muted underline decoration-transparent underline-offset-4 transition-colors hover:text-ink hover:decoration-current">Beranda</a></li>
                <li aria-hidden="true" class="text-muted/60">/</li>
                <li class="font-bold text-ink" aria-current="page">{{ $breadcrumb }}</li>
            </ol>
        </nav>

        <h1 class="title mt-6 max-w-[22ch] text-[clamp(2.1rem,4.6vw,3.75rem)]">{{ $title }}</h1>

        @if(!empty($subtitle))
            <p class="lead mt-5 max-w-[56ch]">{{ $subtitle }}</p>
        @endif
    </div>
</section>
