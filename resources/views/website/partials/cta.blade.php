@php
    $registerUrl = Route::has('auth.register') ? route('auth.register') : route('login');
@endphp

<section class="pb-20 md:pb-28">
    <div class="wrap">
        <div class="on-dark relative isolate overflow-hidden rounded-2xl bg-rimba px-7 py-14 text-white md:px-16 md:py-20">

            <div class="rosette -right-24 -top-24 -z-10 w-[26rem] max-w-none text-white/[0.09]" aria-hidden="true"></div>
            <div class="rosette -bottom-40 right-40 -z-10 hidden w-[22rem] text-white/[0.05] lg:block" aria-hidden="true"></div>

            <div class="max-w-xl">
                <h2 class="title text-white">Siap membuktikan kompetensi Anda?</h2>
                <p class="lead mt-5 text-white/75">
                    Buat akun asesi, pilih skema, dan ajukan permohonan. Anda bisa memantau setiap tahapnya dari akun.
                </p>

                <div class="mt-9 flex flex-wrap items-center gap-x-7 gap-y-4">
                    <a href="{{ $registerUrl }}" class="btn btn-cap btn-lg">Daftar sebagai asesi</a>
                    <a href="#skema"
                       class="inline-flex min-h-11 items-center text-[0.9375rem] font-bold text-white underline decoration-white/40 underline-offset-[6px] transition-colors hover:decoration-cap-soft">
                        Lihat skema sertifikasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
