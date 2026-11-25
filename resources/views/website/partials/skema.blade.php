@php
    // Home summary: a handful of schemes and a way to the full list on /skema.
    // SAMPLE content (see App\Support\SampleContent), always shown with a visible "Contoh" label.
    $all = \App\Support\SampleContent::schemes();
    $preview = $all->take(6);
    $counts = $all->countBy('field');
    $registerUrl = Route::has('auth.register') ? route('auth.register') : route('login');
@endphp

<section id="skema" class="scroll-mt-20 py-16 md:py-24">
    <div class="wrap">

        <div class="flex flex-wrap items-end justify-between gap-x-8 gap-y-4">
            <div class="max-w-2xl">
                <h2 class="title">Pilih skema yang sesuai dengan pekerjaan Anda.</h2>
                <p class="lead mt-4">Setiap skema memuat unit kompetensi yang diuji. Anda hanya diuji pada skema yang Anda pilih.</p>
            </div>
            <p class="inline-flex items-center gap-2 text-sm font-semibold text-accent-text">
                <span class="rounded border border-current px-1.5 py-0.5 text-xs">Contoh</span>
                Daftar skema ini hanya contoh
            </p>
        </div>

        {{-- Jump straight to one field on the full page --}}
        <div class="mt-8 flex flex-wrap items-center gap-2" role="group" aria-label="Lihat skema per bidang">
            <span class="mr-1 text-sm font-semibold text-ink">Per bidang</span>
            @foreach($counts as $field => $count)
                <a href="{{ route('skema.index') }}?bidang={{ urlencode($field) }}"
                   class="inline-flex min-h-11 items-center gap-2 rounded-md border border-line px-3.5 text-[0.9375rem] font-bold text-body transition-colors hover:border-ink hover:text-ink">
                    {{ $field }} <span class="text-xs font-semibold tabular-nums text-muted">{{ $count }}</span>
                </a>
            @endforeach
        </div>

        <ul class="mt-6 grid gap-3 md:grid-cols-2 lg:grid-cols-3">
            @foreach($preview as $item)
                <li>
                    <a href="{{ $registerUrl }}"
                       class="group flex h-full items-center gap-3 rounded-lg border border-line bg-surface px-4 py-3
                              shadow-[inset_0_1px_0_rgba(255,255,255,0.6)] transition-[transform,border-color,box-shadow] duration-200
                              hover:-translate-y-0.5 hover:border-brand/50 hover:shadow-[0_8px_16px_-10px_rgba(16,42,70,0.35)] active:translate-y-0">
                        <span class="min-w-0 flex-1">
                            <span class="block text-[0.9375rem] font-bold leading-snug text-ink line-clamp-2">{{ $item['name'] }}</span>
                            <span class="mt-0.5 block text-[0.8125rem] font-medium text-muted">{{ $item['field'] }}</span>
                        </span>
                        <span class="shrink-0 text-[0.9375rem] font-bold text-link underline decoration-transparent underline-offset-[6px] transition-colors group-hover:decoration-current">
                            Daftar<span class="sr-only"> untuk skema {{ $item['name'] }}</span>
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="mt-6 flex flex-wrap items-center gap-x-5 gap-y-2">
            <a href="{{ route('skema.index') }}" class="btn btn-solid btn-lg">
                Lihat semua {{ $all->count() }} skema
            </a>
            @if($all->count() > $preview->count())
                <span class="text-sm text-muted">Menampilkan {{ $preview->count() }} dari {{ $all->count() }}, dengan pencarian dan filter bidang.</span>
            @endif
        </div>
    </div>
</section>
