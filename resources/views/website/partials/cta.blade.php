@php
    $registerUrl = Route::has('auth.register') ? route('auth.register') : route('login');
@endphp

<section class="pb-20 md:pb-28">
    <div class="wrap">
        <div class="on-dark relative isolate overflow-hidden rounded-2xl bg-navy px-7 py-14 text-white md:px-16 md:py-20">

            @include('website.partials.lattice', ['class' => 'pointer-events-none absolute inset-0 -z-10 h-full w-full text-white opacity-[0.06]'])

            <div class="max-w-xl">
                <h2 class="title text-white">Siap membuktikan kompetensi Anda?</h2>
                <p class="lead mt-5 text-white/75">
                    Buat akun asesi, pilih skema, dan ajukan permohonan. Anda bisa memantau setiap tahapnya dari akun.
                </p>

                <div class="mt-9 flex flex-wrap items-center gap-x-7 gap-y-4">
                    <a href="{{ $registerUrl }}" class="btn btn-accent btn-lg">Daftar sebagai asesi</a>
                    <a href="{{ route('skema.index') }}"
                       class="inline-flex min-h-11 items-center text-[0.9375rem] font-bold text-white underline decoration-white/40 underline-offset-[6px] transition-colors hover:decoration-gold-soft">
                        Lihat skema sertifikasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
