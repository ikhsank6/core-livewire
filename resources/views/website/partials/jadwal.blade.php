@php
    // Home summary: the next five exam dates and a way to the full schedule on /jadwal.
    // SAMPLE content (see App\Support\SampleContent), always shown with a visible "Contoh" label.
    $all = \App\Support\SampleContent::schedule();
    $upcoming = $all->take(5);
    $fieldByScheme = \App\Support\SampleContent::schemes()->pluck('field', 'name');
@endphp

<section id="jadwal" class="scroll-mt-20 py-16 md:py-24">
    <div class="wrap">

        <div class="flex flex-wrap items-end justify-between gap-x-8 gap-y-4">
            <div class="max-w-2xl">
                <h2 class="title">Jadwal uji kompetensi terdekat.</h2>
                <p class="lead mt-4">Lima jadwal terdekat. Tempat uji (TUK) tertera di setiap baris.</p>
            </div>
            <p class="inline-flex items-center gap-2 text-sm font-semibold text-accent-text">
                <span class="rounded border border-current px-1.5 py-0.5 text-xs">Contoh</span>
                Isi jadwal sebenarnya diatur pengelola LSP
            </p>
        </div>

        <div class="panel mt-8 overflow-hidden shadow-xs" role="table" aria-label="Lima jadwal uji kompetensi terdekat (contoh)">
            <div class="hidden border-b border-line bg-sunken/70 px-5 py-3 text-xs font-bold uppercase tracking-wider text-muted md:grid md:grid-cols-[12rem_minmax(0,1fr)_13rem_10.5rem] md:gap-6" role="row">
                <span role="columnheader">Tanggal</span>
                <span role="columnheader">Skema Sertifikasi</span>
                <span role="columnheader">Tempat Uji (TUK)</span>
                <span role="columnheader">Pendaftaran</span>
            </div>

            <div class="divide-y divide-line">
                @foreach($upcoming as $row)
                    @include('website.partials.jadwal-row', [
                        'row'   => $row,
                        'field' => $fieldByScheme[$row['skema']] ?? null,
                    ])
                @endforeach
            </div>
        </div>

        <div class="mt-6 flex flex-wrap items-center gap-x-5 gap-y-2">
            <a href="{{ route('jadwal.index') }}" class="btn btn-solid btn-lg">
                Lihat semua {{ $all->count() }} jadwal
            </a>
            @if($all->count() > $upcoming->count())
                <span class="text-sm text-muted">Menampilkan {{ $upcoming->count() }} dari {{ $all->count() }}, dengan filter pendaftaran.</span>
            @endif
        </div>
    </div>
</section>
