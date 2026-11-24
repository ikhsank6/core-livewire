@php
    // Static SAMPLE content: there is no Skema model yet. These are generic occupation names,
    // shown with a visible "contoh" label. The real list follows the licence of this LSP.
    $skema = [
        ['name' => 'Teknisi Jaringan Komputer',        'field' => 'TIK'],
        ['name' => 'Pemrogram Junior',                 'field' => 'TIK'],
        ['name' => 'Administrasi Perkantoran',         'field' => 'Perkantoran'],
        ['name' => 'Asisten Administrasi',             'field' => 'Perkantoran'],
        ['name' => 'Pelaksana Lapangan Pekerjaan Gedung', 'field' => 'Konstruksi'],
        ['name' => 'Juru Gambar Konstruksi',           'field' => 'Konstruksi'],
        ['name' => 'Pramusaji',                        'field' => 'Perhotelan'],
        ['name' => 'Resepsionis Hotel',                'field' => 'Perhotelan'],
    ];

    $fields = collect($skema)->pluck('field')->unique()->values()->all();
    $registerUrl = Route::has('auth.register') ? route('auth.register') : route('login');
@endphp

<section id="skema" class="scroll-mt-20 py-20 md:py-28"
         x-data="{
            filter: 'Semua',
            fields: @js(collect($skema)->pluck('field')->values()),
            get count() { return this.filter === 'Semua' ? this.fields.length : this.fields.filter(f => f === this.filter).length }
         }">
    <div class="wrap grid gap-12 lg:grid-cols-[minmax(0,22rem)_minmax(0,1fr)] lg:gap-20">

        <div class="lg:sticky lg:top-28 lg:self-start">
            <h2 class="title">Pilih skema yang sesuai dengan pekerjaan Anda.</h2>
            <p class="lead mt-5 max-w-[34ch]">
                Setiap skema memuat unit kompetensi yang diuji. Anda hanya diuji pada skema yang Anda pilih.
            </p>

            <div class="mt-8" role="group" aria-label="Filter bidang">
                <p class="mb-3 text-sm font-semibold text-ink">Bidang</p>
                <div class="flex flex-wrap gap-2">
                    @foreach(array_merge(['Semua'], $fields) as $field)
                        <button type="button" @click="filter = '{{ $field }}'"
                                :aria-pressed="filter === '{{ $field }}' ? 'true' : 'false'"
                                class="inline-flex min-h-11 cursor-pointer items-center rounded-md border px-4 text-[0.9375rem] font-bold transition-colors"
                                :class="filter === '{{ $field }}' ? 'border-ink bg-ink text-page' : 'border-line text-body hover:border-ink hover:text-ink'">
                            {{ $field }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div>
            <div class="flex flex-wrap items-center justify-between gap-x-6 gap-y-2 pb-4">
                <p class="text-sm text-muted" aria-live="polite">
                    Menampilkan <span class="font-bold text-ink tabular-nums" x-text="count"></span> skema
                </p>
                <p class="inline-flex items-center gap-2 text-sm font-semibold text-accent-text">
                    <span class="rounded border border-current px-1.5 py-0.5 text-xs">Contoh</span>
                    Daftar skema ini hanya contoh
                </p>
            </div>

            <ul class="border-t border-line">
                @foreach($skema as $item)
                    <li x-show="filter === 'Semua' || filter === '{{ $item['field'] }}'" class="border-b border-line">
                        <a href="{{ $registerUrl }}"
                           class="group grid gap-x-8 gap-y-1 px-1 py-6 transition-colors hover:bg-sunken sm:-mx-4 sm:grid-cols-[minmax(0,1fr)_7.5rem_auto] sm:items-center sm:px-4">
                            <span class="subtitle block text-ink transition-transform duration-200 group-hover:translate-x-1">{{ $item['name'] }}</span>
                            <span class="text-sm font-medium text-muted">{{ $item['field'] }}</span>
                            <span class="mt-2 text-[0.9375rem] font-bold text-link underline decoration-transparent underline-offset-[6px] transition-colors group-hover:decoration-current sm:mt-0">
                                Daftar<span class="sr-only"> untuk skema {{ $item['name'] }}</span>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
