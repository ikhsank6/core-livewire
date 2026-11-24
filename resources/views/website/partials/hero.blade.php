@php
    $registerUrl = Route::has('auth.register') ? route('auth.register') : route('login');

    $office = collect([
        ['label' => 'Kantor LSP', 'value' => $aboutUs?->address],
        ['label' => 'Telepon',    'value' => $aboutUs?->phone],
    ])->filter(fn ($f) => filled($f['value']));

    // Quick links, the pattern the national certification body uses for its main tasks.
    $quick = [
        ['title' => 'Skema sertifikasi',   'text' => 'Pilih okupasi yang ingin diuji.',          'href' => '#skema'],
        ['title' => 'Alur dan persyaratan', 'text' => 'Dari akun sampai sertifikat terbit.',      'href' => '#alur'],
        ['title' => 'Jadwal uji',           'text' => 'Lihat kapan dan di mana ujinya.',          'href' => '#jadwal'],
    ];

    // The specimen: placeholders only, never a real person or a real certificate number.
    $units = ['Unit kompetensi pertama', 'Unit kompetensi kedua', 'Unit kompetensi ketiga'];
@endphp

<section class="on-dark relative isolate overflow-hidden bg-rimba text-white"
         x-data="{
            rot: 0,
            reduce: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
            tilt(e) {
                if (this.reduce) return;
                const r = this.$el.getBoundingClientRect();
                this.rot = ((e.clientX - r.left) / r.width - 0.5) * 16;
            }
         }"
         @pointermove="tilt($event)">

    {{-- Background effect: guilloche rosettes, the security print of a certificate.
         They turn a few degrees with the pointer. --}}
    <div class="rosette -right-48 -top-40 -z-10 w-[52rem] max-w-none text-white/[0.085]" :style="`--rot: ${rot}deg`" aria-hidden="true"></div>
    <div class="rosette -left-72 -bottom-80 -z-10 w-[40rem] max-w-none text-white/[0.05]" :style="`--rot: ${-rot}deg`" aria-hidden="true"></div>

    <div class="wrap grid items-center gap-14 py-14 md:py-20 lg:grid-cols-[minmax(0,1.05fr)_minmax(0,0.95fr)] lg:gap-16">

        {{-- Copy --}}
        <div>
            <h1 class="display text-white max-w-[17ch]">
                Yang kami uji adalah apa yang bisa Anda kerjakan.
            </h1>

            <p class="lead mt-7 max-w-[50ch] text-white/75">
                Asesmen kompetensi kerja berbasis unit kompetensi. Daftar, pilih skema, ikuti uji bersama asesor,
                lalu terima keputusannya: Kompeten atau Belum Kompeten.
            </p>

            <div class="mt-9 flex flex-wrap items-center gap-x-7 gap-y-4">
                <a href="{{ $registerUrl }}" class="btn btn-cap btn-lg">Daftar sebagai asesi</a>
                <a href="#skema"
                   class="inline-flex min-h-11 items-center text-[0.9375rem] font-bold text-white underline decoration-white/40 underline-offset-[6px] transition-colors hover:decoration-cap-soft">
                    Lihat skema sertifikasi
                </a>
            </div>

            @if($office->isNotEmpty())
                <dl class="mt-12 grid max-w-xl gap-x-10 gap-y-5 border-t border-white/15 pt-6 sm:grid-cols-2">
                    @foreach($office as $fact)
                        <div>
                            <dt class="text-sm text-white/60">{{ $fact['label'] }}</dt>
                            <dd class="mt-1 text-[0.9375rem] font-semibold text-white wrap-break-word">{{ $fact['value'] }}</dd>
                        </div>
                    @endforeach
                </dl>
            @endif
        </div>

        {{-- Specimen certificate --}}
        <figure class="relative mx-auto w-full max-w-md lg:max-w-[28rem]" aria-label="Contoh tampilan sertifikat kompetensi">
            <div class="absolute inset-0 translate-x-3 translate-y-4 rotate-[3deg] rounded-xl bg-white/12" aria-hidden="true"></div>

            <div class="relative -rotate-1 overflow-hidden rounded-xl bg-white text-[#0F1C1A] shadow-[0_34px_80px_-30px_rgba(0,0,0,0.65)]">
                <div class="rosette -bottom-24 -right-20 w-72 text-rimba/[0.09]" aria-hidden="true"></div>

                <div class="relative m-3 border border-rimba/25 p-6 sm:p-7">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-2.5">
                            <svg class="h-8 w-8 shrink-0" viewBox="0 0 36 36" fill="none" aria-hidden="true">
                                <circle cx="18" cy="18" r="16" stroke="#E4472D" stroke-width="2.4" stroke-linecap="round" stroke-dasharray="0.1 3.6"/>
                                <circle cx="18" cy="18" r="12.5" fill="#E4472D"/>
                                <path d="M11.8 18.4l4.4 4.4 8.2-9.2" stroke="#1B0B07" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class="text-[0.9375rem] font-extrabold tracking-tight">Sertifikat Kompetensi</span>
                        </div>
                        <span class="rounded border border-cap-deep/50 px-2 py-0.5 text-xs font-bold text-cap-deep">Contoh</span>
                    </div>

                    <p class="mt-7 text-xs font-semibold text-[#566763]">Diberikan kepada</p>
                    <p class="mt-1 border-b border-rimba/30 pb-2 text-xl font-extrabold tracking-tight text-[#566763]/80">Nama pemegang sertifikat</p>

                    <p class="mt-5 text-xs font-semibold text-[#566763]">Skema sertifikasi</p>
                    <p class="mt-1 text-[0.9375rem] font-bold text-[#566763]/90">Nama skema sertifikasi</p>

                    <ul class="mt-5 space-y-2" aria-label="Unit kompetensi yang dinyatakan terpenuhi (contoh)">
                        @foreach($units as $unit)
                            <li class="flex items-center gap-3 text-[0.8125rem] font-medium">
                                <span class="grid h-5 w-5 shrink-0 place-items-center rounded-[4px] border-2 border-rimba bg-rimba" aria-hidden="true">
                                    <svg class="h-3.5 w-3.5 text-white" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 10.5l3.5 3.5 7.5-8.5"/></svg>
                                </span>
                                <span class="flex-1 text-[#3C4B47]">{{ $unit }}</span>
                                <span class="font-extrabold text-rimba" aria-label="Kompeten">K</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-16 flex items-end justify-between gap-4 text-xs text-[#566763]">
                        <span class="tabular-nums">No. 000-0000</span>
                        <span class="w-28 border-t border-rimba/30 pt-1 text-center">Ketua LSP</span>
                    </div>

                    <div class="stamp absolute bottom-12 right-5 bg-white/0 px-4 py-1.5 text-xl font-extrabold tracking-tight" aria-hidden="true">KOMPETEN</div>
                </div>
            </div>
        </figure>
    </div>

    {{-- Quick links --}}
    <div class="relative border-t border-white/15">
        <ul class="wrap grid gap-px sm:grid-cols-3">
            @foreach($quick as $link)
                <li>
                    <a href="{{ $link['href'] }}" class="group block px-1 py-5 sm:px-6 sm:first:pl-0 transition-colors hover:bg-white/[0.04]">
                        <span class="block text-base font-extrabold tracking-tight text-white group-hover:text-cap-soft transition-colors">{{ $link['title'] }}</span>
                        <span class="mt-0.5 block text-sm text-white/65">{{ $link['text'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
