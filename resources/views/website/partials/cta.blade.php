@php
    $registerUrl = Route::has('auth.register') ? route('auth.register') : route('login');
@endphp

<section class="pb-20 md:pb-28">
    <div class="wrap">
        <div class="on-dark relative isolate overflow-hidden rounded-3xl bg-nila px-7 py-14 text-white md:px-16 md:py-20">

            {{-- The door again, as a line drawing: the identity motif closes the page --}}
            <svg class="pointer-events-none absolute -right-6 bottom-0 -z-10 h-[125%] w-auto text-kunyit/25 md:right-10"
                 viewBox="0 0 240 360" fill="none" aria-hidden="true">
                <path d="M16 360V120C16 53.7 69.7 0 136 0s120 53.7 120 120v240" stroke="currentColor" stroke-width="2"/>
                <path d="M56 360V124c0-44.2 35.8-80 80-80s80 35.8 80 80v236" stroke="currentColor" stroke-width="2"/>
                <path d="M96 360V128c0-22.1 17.9-40 40-40s40 17.9 40 40v232" stroke="currentColor" stroke-width="2"/>
            </svg>

            <div class="max-w-xl">
                <h2 class="title text-white">Mulai dari satu akun, selesaikan urusan dari rumah.</h2>
                <p class="lead mt-5 text-white/75">
                    Buat akun sekali. Berikutnya Anda tinggal memilih layanan dan melacak berkasnya.
                </p>

                <div class="mt-9 flex flex-wrap items-center gap-x-7 gap-y-4">
                    <a href="{{ $registerUrl }}" class="ticket ticket-lg">Buat akun</a>
                    <a href="{{ route('news.index') }}"
                       class="inline-flex items-center min-h-11 text-[0.9375rem] font-semibold text-white underline decoration-white/40 underline-offset-[6px] hover:decoration-kunyit transition-colors">
                        Baca berita terbaru
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
