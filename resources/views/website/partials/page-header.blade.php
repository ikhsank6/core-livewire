{{--
    Props:
    $title      (string)
    $breadcrumb (string)  – current page label
    $subtitle   (string, optional)
--}}
<section class="relative isolate overflow-hidden">
    {{-- Full-width circuit motif; fades out toward the bottom edge so no line shows --}}
    @include('website.partials.circuit', ['class' => 'circuit-fade absolute inset-0 -z-10 h-full w-full text-brand opacity-[0.16] dark:text-sky dark:opacity-[0.14]'])

    <div class="wrap pt-5 pb-1 md:pt-7 md:pb-2">
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
