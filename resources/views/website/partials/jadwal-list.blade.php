@php
    // The full schedule. SAMPLE content (see App\Support\SampleContent); a page passes $jadwal.
    $jadwal = collect($jadwal ?? \App\Support\SampleContent::schedule())->sortBy('date')->values();
    $fieldByScheme = \App\Support\SampleContent::schemes()->pluck('field', 'name');

    $openCount = $jadwal->where('open', true)->count();
    $closedCount = $jadwal->where('open', false)->count();
    $perPage = 5;
@endphp

<section id="jadwal" class="scroll-mt-20 pt-8 pb-14 md:pt-10 md:pb-20"
         x-data="{
            items: @js($jadwal->map(fn ($r) => [
                'open'  => (bool) $r['open'],
                'skema' => $r['skema'],
                'venue' => $r['venue'],
                'field' => $fieldByScheme[$r['skema']] ?? '',
            ])->values()),
            status: 'all',
            q: '',
            perPage: {{ $perPage }},
            page: 1,
            init() {
                this.$watch('q', () => { this.page = 1; });
                this.$watch('status', () => { this.page = 1; });
                this.$watch('perPage', () => { this.page = 1; });
            },
            get matched() {
                const q = this.q.trim().toLowerCase();
                const out = [];
                this.items.forEach((it, i) => {
                    const matchStatus = this.status === 'all' || (this.status === 'open' ? it.open : !it.open);
                    const matchText = !q
                        || it.skema.toLowerCase().includes(q)
                        || it.venue.toLowerCase().includes(q)
                        || it.field.toLowerCase().includes(q);
                    if (matchStatus && matchText) out.push(i);
                });
                return out;
            },
            get totalPages() { return Math.max(1, Math.ceil(this.matched.length / this.perPage)); },
            get visible() {
                const start = (this.page - 1) * this.perPage;
                return this.matched.slice(start, start + this.perPage);
            },
            get fromItem() { return this.matched.length === 0 ? 0 : (this.page - 1) * this.perPage + 1; },
            get toItem() { return Math.min(this.page * this.perPage, this.matched.length); },
            get pages() {
                const total = this.totalPages;
                const cur = this.page;
                if (total <= 7) {
                    return Array.from({ length: total }, (_, i) => i + 1);
                }
                const out = [1];
                if (cur > 3) out.push('...');
                for (let p = Math.max(2, cur - 1); p <= Math.min(total - 1, cur + 1); p++) {
                    out.push(p);
                }
                if (cur < total - 2) out.push('...');
                out.push(total);
                return out;
            },
            show(i) { return this.visible.includes(i); },
            goTo(p) {
                const target = Math.min(Math.max(1, Number(p)), this.totalPages);
                if (target === this.page) return;
                this.page = target;
                this.$nextTick(() => {
                    this.$refs.tableTop?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                });
            },
            reset() { this.q = ''; this.status = 'all'; this.page = 1; }
         }">
    <div class="wrap">

        @if($showHeading ?? true)
            <div class="flex flex-wrap items-end justify-between gap-x-8 gap-y-4">
                <div class="max-w-2xl">
                    <h2 class="title">Jadwal uji kompetensi terdekat.</h2>
                    <p class="lead mt-4">Jadwal ditetapkan LSP. Tempat uji (TUK) tertera di setiap baris.</p>
                </div>
                <p class="inline-flex items-center gap-2 text-sm font-semibold text-accent-text">
                    <span class="rounded border border-current px-1.5 py-0.5 text-xs">Contoh</span>
                    Isi jadwal sebenarnya diatur pengelola LSP
                </p>
            </div>
        @else
            <div class="flex flex-wrap items-center justify-between gap-3">
                <p class="inline-flex items-center gap-2 rounded-full border border-brand/20 bg-brand/8 px-3 py-1 text-xs font-semibold text-accent-text">
                    <span class="rounded bg-brand px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-white">Contoh</span>
                    Isi jadwal sebenarnya diatur pengelola LSP
                </p>
            </div>
        @endif

        {{-- Toolbar: status filters + search --}}
        <div x-ref="tableTop" class="{{ ($showHeading ?? true) ? 'mt-8' : 'mt-5' }} flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="inline-flex flex-wrap gap-1 rounded-xl border border-line bg-surface p-1 shadow-2xs" role="group" aria-label="Filter pendaftaran">
                <button type="button" @click="status = 'all'" :aria-pressed="status === 'all' ? 'true' : 'false'"
                        class="inline-flex min-h-9 cursor-pointer items-center gap-2 rounded-lg px-3.5 text-sm font-bold transition-colors"
                        :class="status === 'all' ? 'bg-brand text-white shadow-2xs' : 'text-body hover:bg-sunken hover:text-ink'">
                    Semua
                    <span class="rounded-full px-1.5 py-0.5 text-[11px] font-bold tabular-nums"
                          :class="status === 'all' ? 'bg-white/20 text-white' : 'bg-sunken text-muted'">{{ $jadwal->count() }}</span>
                </button>
                <button type="button" @click="status = 'open'" :aria-pressed="status === 'open' ? 'true' : 'false'"
                        class="inline-flex min-h-9 cursor-pointer items-center gap-2 rounded-lg px-3.5 text-sm font-bold transition-colors"
                        :class="status === 'open' ? 'bg-brand text-white shadow-2xs' : 'text-body hover:bg-sunken hover:text-ink'">
                    Dibuka
                    <span class="rounded-full px-1.5 py-0.5 text-[11px] font-bold tabular-nums"
                          :class="status === 'open' ? 'bg-white/20 text-white' : 'bg-sunken text-muted'">{{ $openCount }}</span>
                </button>
                <button type="button" @click="status = 'closed'" :aria-pressed="status === 'closed' ? 'true' : 'false'"
                        class="inline-flex min-h-9 cursor-pointer items-center gap-2 rounded-lg px-3.5 text-sm font-bold transition-colors"
                        :class="status === 'closed' ? 'bg-brand text-white shadow-2xs' : 'text-body hover:bg-sunken hover:text-ink'">
                    Belum dibuka
                    <span class="rounded-full px-1.5 py-0.5 text-[11px] font-bold tabular-nums"
                          :class="status === 'closed' ? 'bg-white/20 text-white' : 'bg-sunken text-muted'">{{ $closedCount }}</span>
                </button>
            </div>

            <div class="relative w-full sm:w-72">
                <label for="jadwal-search" class="sr-only">Cari skema atau tempat uji</label>
                <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-muted" aria-hidden="true">
                    @svg('heroicon-o-magnifying-glass', 'h-4 w-4')
                </span>
                <input id="jadwal-search" type="text" x-model="q" autocomplete="off"
                       placeholder="Cari skema atau tempat uji..."
                       class="h-11 w-full rounded-xl border border-line bg-surface pl-10 pr-9 text-sm text-ink placeholder:text-muted shadow-2xs focus:border-brand focus:outline-none">
                <button type="button" x-show="q.length > 0" x-cloak @click="q = ''" aria-label="Hapus pencarian"
                        class="absolute inset-y-0 right-2.5 flex items-center text-muted hover:text-ink cursor-pointer">
                    @svg('heroicon-o-x-mark', 'h-4 w-4')
                </button>
            </div>
        </div>

        {{-- Table card --}}
        <div class="panel mt-4 overflow-hidden shadow-xs" role="table" aria-label="Jadwal uji kompetensi (contoh)">
            <div class="hidden border-b border-line bg-sunken/70 px-5 py-3 text-xs font-bold uppercase tracking-wider text-muted md:grid md:grid-cols-[12rem_minmax(0,1fr)_13rem_10.5rem] md:gap-6" role="row">
                <span role="columnheader">Tanggal</span>
                <span role="columnheader">Skema Sertifikasi</span>
                <span role="columnheader">Tempat Uji (TUK)</span>
                <span role="columnheader">Pendaftaran</span>
            </div>

            <div class="divide-y divide-line">
                @foreach($jadwal as $i => $row)
                    @include('website.partials.jadwal-row', [
                        'row'     => $row,
                        'field'   => $fieldByScheme[$row['skema']] ?? null,
                        'index'   => $i,
                        'perPage' => $perPage,
                    ])
                @endforeach
            </div>

            {{-- Empty state --}}
            <div x-show="matched.length === 0" x-cloak class="px-6 py-12 text-center">
                <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-sunken text-muted">
                    @svg('heroicon-o-calendar-days', 'h-5 w-5')
                </div>
                <p class="subtitle mt-3">Jadwal tidak ditemukan.</p>
                <p class="mx-auto mt-1 max-w-[38ch] text-sm text-muted">Coba ubah filter pendaftaran atau kata kunci pencarian Anda.</p>
                <button type="button" @click="reset()" class="btn btn-line mt-4">Tampilkan semua jadwal</button>
            </div>

            {{-- Pagination footer --}}
            <div x-show="matched.length > 0"
                 class="flex flex-col gap-3 border-t border-line bg-sunken/40 px-4 py-3.5 sm:flex-row sm:items-center sm:justify-between sm:px-5">
                <div class="flex flex-wrap items-center justify-between gap-3 sm:justify-start">
                    <p class="text-sm text-muted" aria-live="polite">
                        Menampilkan <span class="font-bold text-ink tabular-nums" x-text="fromItem"></span>–<span class="font-bold text-ink tabular-nums" x-text="toItem"></span>
                        dari <span class="font-bold text-ink tabular-nums" x-text="matched.length"></span> jadwal
                    </p>
                    <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-muted">
                        <span class="sr-only">Jumlah per halaman</span>
                        <select x-model.number="perPage" aria-label="Jumlah jadwal per halaman"
                                class="h-8 rounded-lg border border-line bg-surface px-2 text-xs font-bold text-ink focus:border-brand focus:outline-none">
                            <option value="5">5 / hal</option>
                            <option value="10">10 / hal</option>
                            <option value="20">20 / hal</option>
                        </select>
                    </label>
                </div>

                {{-- Mobile paging: Prev & Next only --}}
                <nav role="navigation" aria-label="Navigasi halaman jadwal (seluler)" class="flex items-center justify-between gap-2 sm:hidden">
                    <button type="button" @click="goTo(page - 1)" :disabled="page <= 1"
                            class="inline-flex h-10 flex-1 items-center justify-center gap-1.5 rounded-lg border border-line bg-surface px-3 text-sm font-bold text-ink transition-colors hover:bg-sunken disabled:cursor-not-allowed disabled:opacity-45 cursor-pointer">
                        @svg('heroicon-o-chevron-left', 'h-4 w-4')
                        Sebelumnya
                    </button>
                    <span class="px-2 text-xs font-bold tabular-nums text-muted" x-text="`${page} / ${totalPages}`"></span>
                    <button type="button" @click="goTo(page + 1)" :disabled="page >= totalPages"
                            class="inline-flex h-10 flex-1 items-center justify-center gap-1.5 rounded-lg border border-line bg-surface px-3 text-sm font-bold text-ink transition-colors hover:bg-sunken disabled:cursor-not-allowed disabled:opacity-45 cursor-pointer">
                        Berikutnya
                        @svg('heroicon-o-chevron-right', 'h-4 w-4')
                    </button>
                </nav>

                {{-- Desktop paging: Prev, page numbers, Next --}}
                <nav role="navigation" aria-label="Navigasi halaman jadwal" class="hidden items-center gap-1 sm:flex">
                    <button type="button" @click="goTo(page - 1)" :disabled="page <= 1" aria-label="Halaman sebelumnya"
                            class="inline-flex h-9 items-center gap-1 rounded-lg border border-line bg-surface px-3 text-sm font-semibold text-body transition-colors hover:bg-sunken hover:text-ink disabled:cursor-not-allowed disabled:opacity-45 cursor-pointer">
                        @svg('heroicon-o-chevron-left', 'h-4 w-4')
                        <span>Sebelumnya</span>
                    </button>

                    <template x-for="(p, idx) in pages" :key="idx">
                        <span>
                            <template x-if="p === '...'">
                                <span class="inline-grid h-9 min-w-9 place-items-center px-1 text-sm font-semibold text-muted">...</span>
                            </template>
                            <template x-if="p !== '...'">
                                <button type="button" @click="goTo(p)"
                                        :aria-current="page === p ? 'page' : null"
                                        :aria-label="'Halaman ' + p"
                                        class="inline-grid h-9 min-w-9 place-items-center rounded-lg border px-2.5 text-sm font-bold tabular-nums transition-colors cursor-pointer"
                                        :class="page === p ? 'border-brand bg-brand text-white shadow-2xs' : 'border-line bg-surface text-body hover:bg-sunken hover:text-ink'"
                                        x-text="p"></button>
                            </template>
                        </span>
                    </template>

                    <button type="button" @click="goTo(page + 1)" :disabled="page >= totalPages" aria-label="Halaman berikutnya"
                            class="inline-flex h-9 items-center gap-1 rounded-lg border border-line bg-surface px-3 text-sm font-semibold text-body transition-colors hover:bg-sunken hover:text-ink disabled:cursor-not-allowed disabled:opacity-45 cursor-pointer">
                        <span>Berikutnya</span>
                        @svg('heroicon-o-chevron-right', 'h-4 w-4')
                    </button>
                </nav>
            </div>
        </div>
    </div>
</section>
