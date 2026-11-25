@php
    // Static content: there is no FAQ model. Every question is one a candidate really asks before
    // an assessment. Answers stay general: scheme specific rules are shown when applying.
    $faqs = [
        ['q' => 'Apa bedanya sertifikat kompetensi dengan ijazah?',
         'a' => 'Ijazah menunjukkan Anda menyelesaikan pendidikan. Sertifikat kompetensi menunjukkan Anda telah diuji dan mampu mengerjakan pekerjaan tertentu menurut standar kompetensinya.'],
        ['q' => 'Apakah saya harus ikut pelatihan sebelum uji?',
         'a' => 'Tidak selalu. Yang dinilai adalah kemampuan Anda, bukan lamanya Anda berlatih. Syarat yang berlaku untuk setiap skema ditampilkan saat Anda mengajukan permohonan.'],
        ['q' => 'Dokumen apa yang perlu saya siapkan?',
         'a' => 'Umumnya identitas dan bukti pendukung kompetensi, seperti ijazah, sertifikat pelatihan, atau bukti pengalaman kerja. Daftar lengkapnya mengikuti skema yang Anda pilih.'],
        ['q' => 'Siapa yang menilai saya?',
         'a' => 'Asesor kompetensi. Asesor menilai Anda terhadap unit kompetensi pada skema, bukan terhadap peserta lain.'],
        ['q' => 'Apa yang terjadi jika hasil saya Belum Kompeten?',
         'a' => 'Asesor menyampaikan unit mana yang belum terpenuhi. Dengan begitu Anda tahu bagian mana yang perlu dipelajari lagi.'],
        ['q' => 'Di mana ujinya dilaksanakan?',
         'a' => 'Di tempat uji kompetensi (TUK). Lokasi untuk setiap jadwal tertera di bagian Jadwal.'],
    ];
@endphp

<section id="faq" class="scroll-mt-20 py-20 md:py-28">
    <div class="wrap grid gap-12 lg:grid-cols-[minmax(0,22rem)_minmax(0,1fr)] lg:gap-20">

        <div>
            <h2 class="title">Pertanyaan yang paling sering diajukan calon asesi.</h2>
            <p class="lead mt-5 max-w-[34ch]">Belum menemukan jawabannya? Hubungi LSP lewat kanal resmi.</p>
            {{-- Same gold button as "Masuk" in the navbar; 44px tall here so it is an easy tap target --}}
            <a href="{{ route('about') }}#kontak" class="btn btn-login mt-8 h-11 px-5">
                @svg('heroicon-o-phone', 'h-4 w-4')
                Lihat kontak
            </a>
        </div>

        {{-- Each question is its own solid card, so it stands apart from the background motif --}}
        <div class="space-y-3">
            @foreach($faqs as $index => $faq)
                <div x-data="{ open: false }"
                     class="rounded-xl border bg-surface shadow-xs transition-[border-color,box-shadow] duration-200"
                     :class="open ? 'border-brand/50 shadow-md' : 'border-line hover:border-brand/40'">
                    <h3>
                        <button type="button"
                                @click="open = !open"
                                :aria-expanded="open ? 'true' : 'false'"
                                aria-controls="faq-panel-{{ $index }}"
                                class="group flex w-full cursor-pointer items-center gap-4 px-4 py-4 text-left md:px-5">
                            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-lg transition-colors duration-200"
                                  :class="open ? 'bg-brand text-white' : 'bg-sunken text-brand'" aria-hidden="true">
                                @svg('heroicon-o-question-mark-circle', 'h-6 w-6')
                            </span>
                            <span class="subtitle min-w-0 flex-1 text-[1.0625rem] transition-colors group-hover:text-link">{{ $faq['q'] }}</span>
                            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full border border-line text-muted transition-[transform,color,border-color] duration-300 group-hover:border-brand/50 group-hover:text-ink"
                                  :class="open ? 'rotate-45 border-brand/50 text-ink' : ''" aria-hidden="true">
                                @svg('heroicon-m-plus', 'h-5 w-5')
                            </span>
                        </button>
                    </h3>

                    <div id="faq-panel-{{ $index }}" x-show="open" x-collapse x-cloak>
                        <p class="max-w-[62ch] px-4 pb-5 leading-relaxed md:pl-[4.75rem] md:pr-5">{{ $faq['a'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
