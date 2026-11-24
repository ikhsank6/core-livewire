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

<section id="faq" class="scroll-mt-20 border-t border-line py-20 md:py-28">
    <div class="wrap grid gap-12 lg:grid-cols-[minmax(0,22rem)_minmax(0,1fr)] lg:gap-20">

        <div>
            <h2 class="title">Pertanyaan yang paling sering diajukan calon asesi.</h2>
            <p class="lead mt-5 max-w-[34ch]">Belum menemukan jawabannya? Hubungi LSP lewat kanal resmi.</p>
            <a href="{{ route('about') }}#kontak" class="btn btn-line btn-lg mt-8">Lihat kontak</a>
        </div>

        <div class="border-t border-line">
            @foreach($faqs as $index => $faq)
                <div x-data="{ open: false }" class="border-b border-line">
                    <h3>
                        <button type="button"
                                @click="open = !open"
                                :aria-expanded="open ? 'true' : 'false'"
                                aria-controls="faq-panel-{{ $index }}"
                                class="group flex w-full cursor-pointer items-center justify-between gap-6 py-6 text-left">
                            <span class="subtitle text-[1.125rem] transition-colors group-hover:text-link">{{ $faq['q'] }}</span>
                            <span class="shrink-0 text-muted transition-transform duration-300" :class="open ? 'rotate-45 text-ink' : ''" aria-hidden="true">
                                @svg('heroicon-m-plus', 'h-6 w-6')
                            </span>
                        </button>
                    </h3>

                    <div id="faq-panel-{{ $index }}" x-show="open" x-collapse x-cloak>
                        <p class="max-w-[62ch] pb-7 leading-relaxed">{{ $faq['a'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
