@php
    // Static SAMPLE schedule: there is no Jadwal model yet. Dates are generated relative to today
    // and the whole block is labelled as an example. TUK = tempat uji kompetensi (test venue).
    $now = \Illuminate\Support\Carbon::now();
    $rows = [
        ['date' => $now->copy()->addDays(9),  'skema' => 'Teknisi Jaringan Komputer',  'venue' => 'TUK Gedung A',     'open' => true],
        ['date' => $now->copy()->addDays(16), 'skema' => 'Administrasi Perkantoran',   'venue' => 'TUK Gedung B',     'open' => true],
        ['date' => $now->copy()->addDays(23), 'skema' => 'Pramusaji',                  'venue' => 'TUK Mitra Hotel',  'open' => true],
        ['date' => $now->copy()->addDays(37), 'skema' => 'Juru Gambar Konstruksi',     'venue' => 'TUK Gedung A',     'open' => false],
    ];
@endphp

<section id="jadwal" class="scroll-mt-20 py-20 md:py-28">
    <div class="wrap">

        <div class="flex flex-wrap items-end justify-between gap-x-8 gap-y-4">
            <div class="max-w-2xl">
                <h2 class="title">Jadwal uji kompetensi terdekat.</h2>
                <p class="lead mt-5">Jadwal ditetapkan LSP. Tempat uji (TUK) tertera di setiap baris.</p>
            </div>
            <p class="inline-flex items-center gap-2 text-sm font-semibold text-accent-text">
                <span class="rounded border border-current px-1.5 py-0.5 text-xs">Contoh</span>
                Isi jadwal sebenarnya diatur pengelola LSP
            </p>
        </div>

        <div class="mt-12 border-t border-line" role="table" aria-label="Jadwal uji kompetensi (contoh)">
            <div class="hidden gap-6 border-b border-line py-3 text-sm font-semibold text-muted sm:grid sm:grid-cols-[9.5rem_minmax(0,1fr)_11rem_9rem]" role="row">
                <span role="columnheader">Tanggal</span>
                <span role="columnheader">Skema</span>
                <span role="columnheader">Tempat uji</span>
                <span role="columnheader">Pendaftaran</span>
            </div>

            @foreach($rows as $row)
                <div class="grid gap-x-6 gap-y-1 border-b border-line py-5 sm:grid-cols-[9.5rem_minmax(0,1fr)_11rem_9rem] sm:items-center" role="row">
                    <time role="cell" datetime="{{ $row['date']->toDateString() }}" class="text-[0.9375rem] font-extrabold tabular-nums text-ink">
                        {{ $row['date']->translatedFormat('d M Y') }}
                    </time>
                    <span role="cell" class="subtitle text-[1.0625rem]">{{ $row['skema'] }}</span>
                    <span role="cell" class="text-[0.9375rem]">{{ $row['venue'] }}</span>
                    <span role="cell" class="inline-flex items-center gap-2 text-[0.9375rem] font-bold {{ $row['open'] ? 'text-link' : 'text-muted' }}">
                        @if($row['open'])
                            @svg('heroicon-s-check-circle', 'h-5 w-5')
                            Dibuka
                        @else
                            @svg('heroicon-o-lock-closed', 'h-5 w-5')
                            Belum dibuka
                        @endif
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</section>
