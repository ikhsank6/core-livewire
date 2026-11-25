@php
    // The full list of schemes. SAMPLE content (see App\Support\SampleContent); a page passes $skema.
    $skema = collect($skema ?? \App\Support\SampleContent::schemes())->values();

    $counts = $skema->countBy('field');
    $fields = $counts->keys()->all();
    $registerUrl = Route::has('auth.register') ? route('auth.register') : route('login');
@endphp

{{-- The full list, on its own page (/skema). It stays short however many schemes there are: a search box, a field filter with counts,
     two compact columns, and a "show more" step instead of one endless column. The hero search box
     (?q=) and field tags (?bidang=) land here with the filter already applied. --}}
<section id="skema" class="scroll-mt-20 pt-8 pb-16 md:pt-10 md:pb-24"
         x-data="{
            items: @js($skema),
            fields: @js($fields),
            filter: 'Semua',
            q: '',
            step: window.innerWidth < 768 ? 6 : 10,
            limit: 0,
            init() {
                const p = new URLSearchParams(window.location.search);
                this.q = p.get('q') ?? '';
                const f = p.get('bidang');
                if (f && this.fields.includes(f)) this.filter = f;
                this.limit = this.step;
                this.$watch('q', () => this.limit = this.step);
            },
            get matched() {
                const q = this.q.trim().toLowerCase();
                const out = [];
                this.items.forEach((it, i) => {
                    const inField = this.filter === 'Semua' || it.field === this.filter;
                    const inText = !q || it.name.toLowerCase().includes(q) || it.field.toLowerCase().includes(q);
                    if (inField && inText) out.push(i);
                });
                return out;
            },
            get visible() { return this.matched.slice(0, this.limit) },
            get remaining() { return Math.max(0, this.matched.length - this.limit) },
            show(i) { return this.visible.includes(i) },
            pick(f) { this.filter = f; this.limit = this.step },
            reset() { this.q = ''; this.filter = 'Semua'; this.limit = this.step },
            more() { this.limit += this.step },
            less() { this.limit = this.step; this.$el.closest('section').scrollIntoView({ behavior: 'smooth', block: 'start' }) }
         }">
    <div class="wrap grid gap-10 lg:grid-cols-[minmax(0,20rem)_minmax(0,1fr)] lg:gap-14">

        {{-- Left: title, search and field filter --}}
        <div class="lg:sticky lg:top-24 lg:self-start">
            @if($showHeading ?? true)
                <h2 class="title">Pilih skema yang sesuai dengan pekerjaan Anda.</h2>
                <p class="lead mt-4 max-w-[34ch]">
                    Setiap skema memuat unit kompetensi yang diuji. Anda hanya diuji pada skema yang Anda pilih.
                </p>
            @endif

            <div class="{{ ($showHeading ?? true) ? 'mt-6' : '' }}">
                <label for="skema-search" class="mb-2 block text-sm font-semibold text-ink">Cari skema</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-muted" aria-hidden="true">
                        @svg('heroicon-o-magnifying-glass', 'h-4 w-4')
                    </span>
                    <input id="skema-search" type="search" x-model="q" autocomplete="off"
                           placeholder="Nama skema atau bidang"
                           class="h-11 w-full rounded-lg border border-line bg-surface pl-10 pr-4 text-[0.9375rem] text-ink placeholder:text-muted focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/30">
                </div>
            </div>

            <div class="mt-6" role="group" aria-label="Filter bidang">
                <p class="mb-2 text-sm font-semibold text-ink">Bidang</p>
                <div class="flex flex-wrap gap-2">
                    <button type="button" @click="pick('Semua')"
                            :aria-pressed="filter === 'Semua' ? 'true' : 'false'"
                            class="inline-flex min-h-11 cursor-pointer items-center gap-2 rounded-md border px-3.5 text-[0.9375rem] font-bold transition-colors"
                            :class="filter === 'Semua' ? 'border-ink bg-ink text-page' : 'border-line text-body hover:border-ink hover:text-ink'">
                        Semua <span class="text-xs font-semibold tabular-nums opacity-70">{{ $skema->count() }}</span>
                    </button>
                    @foreach($fields as $field)
                        <button type="button" @click="pick(@js($field))"
                                :aria-pressed="filter === @js($field) ? 'true' : 'false'"
                                class="inline-flex min-h-11 cursor-pointer items-center gap-2 rounded-md border px-3.5 text-[0.9375rem] font-bold transition-colors"
                                :class="filter === @js($field) ? 'border-ink bg-ink text-page' : 'border-line text-body hover:border-ink hover:text-ink'">
                            {{ $field }} <span class="text-xs font-semibold tabular-nums opacity-70">{{ $counts[$field] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right: the list --}}
        <div>
            <div class="flex flex-wrap items-center justify-between gap-x-6 gap-y-2 pb-3">
                <p class="text-sm text-muted" aria-live="polite">
                    Menampilkan <span class="font-bold text-ink tabular-nums" x-text="visible.length"></span>
                    dari <span class="font-bold text-ink tabular-nums" x-text="matched.length"></span> skema
                </p>
                <p class="inline-flex items-center gap-2 text-sm font-semibold text-accent-text">
                    <span class="rounded border border-current px-1.5 py-0.5 text-xs">Contoh</span>
                    Daftar skema ini hanya contoh
                </p>
            </div>

            <ul class="grid gap-3 md:grid-cols-2">
                @foreach($skema as $i => $item)
                    <li x-show="show({{ $i }})" @if($i >= 10) x-cloak @endif>
                        <a href="{{ $registerUrl }}"
                           class="group flex h-full items-center gap-3 rounded-lg border border-line bg-surface px-4 py-3
                                  shadow-[inset_0_1px_0_rgba(255,255,255,0.6)] transition-[transform,border-color,box-shadow] duration-200
                                  hover:-translate-y-0.5 hover:border-brand/50 hover:shadow-[0_8px_16px_-10px_rgba(16,42,70,0.35)]
                                  active:translate-y-0">
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

            {{-- Nothing matches: say so and offer the way out --}}
            <div x-show="matched.length === 0" x-cloak class="rounded-lg border border-dashed border-line px-6 py-12 text-center">
                <p class="subtitle">Tidak ada skema yang cocok.</p>
                <p class="mx-auto mt-2 max-w-[40ch] text-[0.9375rem]">Coba kata kunci lain, atau tampilkan seluruh skema.</p>
                <button type="button" @click="reset()" class="btn btn-line mt-5">Tampilkan semua skema</button>
            </div>

            {{-- Show more, in steps --}}
            <div class="mt-5 flex flex-wrap items-center gap-3" x-show="matched.length > step" x-cloak>
                <button type="button" x-show="remaining > 0" @click="more()" class="btn btn-solid">
                    <span>Tampilkan <span x-text="Math.min(step, remaining)"></span> skema lainnya</span>
                </button>
                <button type="button" x-show="limit > step" @click="less()" class="btn btn-line">Ringkas daftar</button>
            </div>
        </div>
    </div>
</section>
