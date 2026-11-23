@php
    // The stages are a real sequence, so they are numbered. The panel on the
    // right is an illustration of the tracking screen, labelled as a sample.
    $stages = [
        ['name' => 'Diajukan',    'status' => 'Berkas diterima',
         'body' => 'Pilih layanan, isi formulir, lalu unggah dokumen dari ponsel atau komputer. Begitu terkirim, berkas langsung tercatat dengan nomor registrasi.',
         'note' => 'Nomor registrasi muncul di akun Anda.'],
        ['name' => 'Diverifikasi', 'status' => 'Sedang diperiksa petugas',
         'body' => 'Petugas memeriksa kelengkapan. Kalau ada yang kurang, Anda diberi tahu dokumen mana yang perlu dilengkapi, tanpa mengulang pengajuan dari awal.',
         'note' => 'Pemberitahuan dikirim lewat surel dan pesan singkat.'],
        ['name' => 'Diproses',    'status' => 'Diteruskan ke instansi',
         'body' => 'Berkas diteruskan ke instansi yang berwenang. Posisinya bisa Anda lihat kapan saja dari akun, tanpa perlu menelepon kantor.',
         'note' => 'Setiap perubahan status tercatat bersama tanggalnya.'],
        ['name' => 'Terbit',      'status' => 'Dokumen siap diunduh',
         'body' => 'Dokumen terbit secara elektronik dan memuat kode verifikasi. Siapa pun bisa memeriksa keasliannya lewat halaman verifikasi.',
         'note' => 'Unduh dari akun, tanpa antre di loket.'],
    ];
@endphp

<section id="alur" class="on-dark scroll-mt-16 bg-nila text-white py-20 md:py-28"
         x-data="{ s: 0, stages: @js($stages),
                   key(e) {
                       if (e.key === 'ArrowDown' || e.key === 'ArrowRight') { this.s = (this.s + 1) % this.stages.length; }
                       if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') { this.s = (this.s + this.stages.length - 1) % this.stages.length; }
                       this.$nextTick(() => this.$refs['tab' + this.s].focus());
                   } }">
    <div class="wrap">

        <div class="max-w-2xl">
            <h2 class="title text-white">Perjalanan sebuah berkas, dari meja Anda sampai terbit.</h2>
            <p class="lead mt-5 text-white/70">
                Pilih satu tahap untuk melihat apa yang terjadi di sana dan apa yang Anda lihat di akun.
            </p>
        </div>

        <div class="mt-14 grid gap-10 lg:grid-cols-[minmax(0,20rem)_minmax(0,1fr)] lg:gap-16">

            {{-- Stage list --}}
            <div role="tablist" aria-label="Tahap pengajuan" aria-orientation="vertical" class="flex flex-col" @keydown="key($event)">
                @foreach($stages as $i => $stage)
                    <button type="button" role="tab" id="tab-{{ $i }}" x-ref="tab{{ $i }}"
                            aria-controls="panel-{{ $i }}"
                            :aria-selected="s === {{ $i }} ? 'true' : 'false'"
                            :tabindex="s === {{ $i }} ? 0 : -1"
                            @click="s = {{ $i }}"
                            class="group flex items-center gap-5 min-h-16 py-3 text-left border-t border-white/15 last:border-b cursor-pointer
                                   transition-colors"
                            :class="s === {{ $i }} ? 'text-white' : 'text-white/60 hover:text-white'">
                        <span class="w-9 shrink-0 font-display text-2xl font-bold tabular-nums transition-colors"
                              :class="s === {{ $i }} ? 'text-kunyit' : 'text-white/40 group-hover:text-white/70'"
                              style="font-variation-settings: 'wdth' 90;">{{ $i + 1 }}</span>
                        <span class="subtitle text-inherit" style="color: inherit;">{{ $stage['name'] }}</span>
                    </button>
                @endforeach
            </div>

            {{-- Stage panel --}}
            <div class="relative">
                @foreach($stages as $i => $stage)
                    <div role="tabpanel" id="panel-{{ $i }}" aria-labelledby="tab-{{ $i }}"
                         x-show="s === {{ $i }}" @if($i > 0) style="display: none" @endif
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0">

                        <p class="max-w-[56ch] text-xl leading-relaxed text-white/90 font-display" style="font-variation-settings: 'wdth' 94; font-weight: 500;">
                            {{ $stage['body'] }}
                        </p>

                        {{-- Sample of the tracking screen --}}
                        <div class="mt-10 rounded-2xl border border-white/15 bg-white/[0.04] p-6 md:p-8">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <p class="text-sm text-white/60">Nomor registrasi</p>
                                    <p class="mt-0.5 font-display text-lg font-semibold tabular-nums">REG-0000</p>
                                </div>
                                <p class="text-sm text-white/60">Contoh tampilan, bukan data nyata</p>
                            </div>

                            {{-- Rail: progress is a transform, so it animates cheaply --}}
                            <div class="mt-8">
                                <div class="relative h-1 rounded-full bg-white/15">
                                    <div class="absolute inset-y-0 left-0 w-full origin-left rounded-full bg-kunyit transition-transform duration-500 ease-out"
                                         style="transform: scaleX({{ $i / (count($stages) - 1) }});"></div>
                                </div>
                                <ol class="relative mt-3 h-6 text-sm">
                                    @foreach($stages as $j => $other)
                                        @php $pct = $j / (count($stages) - 1) * 100; @endphp
                                        <li class="absolute top-0 whitespace-nowrap {{ $j === $i ? 'font-semibold text-white' : ($j < $i ? 'text-white/80' : 'text-white/50') }}"
                                            style="left: {{ $pct }}%; transform: translateX(-{{ $pct }}%);">
                                            {{ $other['name'] }}
                                        </li>
                                    @endforeach
                                </ol>
                            </div>

                            <div class="mt-8 flex flex-wrap items-baseline justify-between gap-x-6 gap-y-1 border-t border-white/15 pt-5">
                                <p class="font-semibold text-kunyit">{{ $stage['status'] }}</p>
                                <p class="text-sm text-white/70">{{ $stage['note'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
