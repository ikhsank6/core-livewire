{{--
    Props:
    $title      (string)
    $breadcrumb (string)  – current page label
    $subtitle   (string, optional)
    $bgImage    (string|null, optional)
--}}
<section class="relative bg-ink text-bone rounded-b-3xl md:rounded-b-[2.5rem] overflow-hidden
                dark:border-b dark:border-pine-soft">

    @if(!empty($bgImage))
        <div aria-hidden="true"
             class="absolute inset-0 bg-cover bg-center opacity-[0.10] grayscale blur-[18px] scale-110"
             style="background-image:url('{{ $bgImage }}');"></div>
    @endif

    <div aria-hidden="true" class="absolute inset-0 opacity-[0.05]"
         style="background-image:radial-gradient(circle,#E8A317 1px,transparent 1px);background-size:26px 26px;"></div>
    <div aria-hidden="true" class="absolute inset-0 bg-linear-to-b from-ink/60 via-transparent to-ink"></div>

    <div class="relative mx-auto max-w-300 px-5 md:px-8 pt-36 pb-20 md:pt-44 md:pb-24">

        <nav aria-label="Remah roti" class="mb-7">
            <ol class="flex items-center gap-2.5 t-micro text-bone-dim">
                <li>
                    <a href="{{ route('landing') }}" class="hover:text-bone transition-colors">Beranda</a>
                </li>
                <li aria-hidden="true" class="text-bone-dim/40">/</li>
                <li>
                    <span class="text-marigold" aria-current="page">{{ $breadcrumb }}</span>
                </li>
            </ol>
        </nav>

        <h1 class="t-display text-[clamp(2.25rem,5vw,4rem)] max-w-[18ch]">{{ $title }}</h1>

        @if(!empty($subtitle))
            <p class="t-lead mt-6 text-bone-dim">{{ $subtitle }}</p>
        @endif
    </div>
</section>
