@php
    // The stages are a real sequence, so they are numbered. APL-01 and APL-02 are the standard
    // application and self-assessment forms of competence certification.
    $steps = [
        ['form' => null,      'title' => 'Buat akun asesi',
         'body' => 'Daftar dengan data diri Anda. Akun ini dipakai untuk semua pengajuan berikutnya.'],
        ['form' => 'APL-01',  'title' => 'Ajukan permohonan',
         'body' => 'Pilih skema, isi formulir permohonan, dan lampirkan bukti pendukung seperti ijazah, sertifikat pelatihan, atau bukti pengalaman kerja.'],
        ['form' => 'APL-02',  'title' => 'Asesmen mandiri',
         'body' => 'Centang unit kompetensi yang Anda yakin bisa dikerjakan, lalu lampirkan bukti untuk tiap unit.'],
        ['form' => null,      'title' => 'Uji kompetensi',
         'body' => 'Asesor menilai dengan metode yang sesuai skema, misalnya observasi, wawancara, atau portofolio, di tempat uji kompetensi (TUK).'],
        ['form' => null,      'title' => 'Keputusan',
         'body' => 'Asesor memutuskan Kompeten atau Belum Kompeten. Jika Kompeten, sertifikat kompetensi diterbitkan.'],
    ];
@endphp

<section id="alur" class="scroll-mt-20 border-y border-line bg-sunken/60 py-20 md:py-28">
    <div class="wrap">

        <div class="max-w-2xl">
            <h2 class="title">Lima langkah dari akun sampai sertifikat.</h2>
            <p class="lead mt-5">
                Tidak ada kuota nilai atau peringkat. Yang diperiksa adalah apakah Anda mampu mengerjakan setiap unit kompetensi pada skema Anda.
            </p>
        </div>

        <ol class="mt-14 grid gap-x-8 gap-y-10 md:grid-cols-2 lg:grid-cols-5 lg:gap-x-6">
            @foreach($steps as $step)
                <li class="relative lg:pt-8">
                    {{-- Perforated rail between the steps (wide screens) --}}
                    <span class="absolute left-0 right-[-1.5rem] top-0 hidden border-t-2 border-dashed border-line lg:block {{ $loop->last ? 'right-0' : '' }}" aria-hidden="true"></span>
                    <span class="absolute left-0 top-[-5px] hidden h-3 w-3 rounded-full border-2 border-cap bg-page lg:block" aria-hidden="true"></span>

                    <p class="text-5xl font-extrabold leading-none tracking-tighter text-ink/15 tabular-nums" aria-hidden="true">{{ $loop->iteration }}</p>

                    <h3 class="subtitle mt-4">
                        <span class="sr-only">Langkah {{ $loop->iteration }}: </span>{{ $step['title'] }}
                    </h3>

                    @if($step['form'])
                        <span class="mt-2 inline-block rounded border border-line bg-surface px-2 py-0.5 text-xs font-bold text-ink">{{ $step['form'] }}</span>
                    @endif

                    <p class="mt-3 text-[0.9375rem] leading-relaxed">{{ $step['body'] }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- The decision: only two outcomes --}}
<section class="on-dark bg-rimba py-20 text-white md:py-24">
    <div class="wrap grid gap-12 lg:grid-cols-[minmax(0,26rem)_minmax(0,1fr)] lg:gap-20">

        <div>
            <h2 class="title text-white">Hasilnya hanya dua: Kompeten atau Belum Kompeten.</h2>
            <p class="lead mt-5 text-white/75">
                Keputusan diambil per skema, berdasarkan bukti dari uji dan asesmen mandiri Anda.
            </p>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <article class="rounded-xl border border-white/15 bg-rimba-2 p-7">
                <span class="grid h-12 w-12 place-items-center rounded-lg bg-white text-rimba" aria-hidden="true">
                    <svg class="h-7 w-7" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 10.5l3.5 3.5 7.5-8.5"/></svg>
                </span>
                <h3 class="subtitle mt-5 text-white">Kompeten (K)</h3>
                <p class="mt-3 text-[0.9375rem] leading-relaxed text-white/75">
                    Anda memenuhi seluruh unit kompetensi pada skema. Sertifikat kompetensi diterbitkan atas nama Anda.
                </p>
            </article>

            <article class="rounded-xl border border-white/15 bg-rimba-2 p-7">
                <span class="grid h-12 w-12 place-items-center rounded-lg border-2 border-white/60 text-white" aria-hidden="true">
                    <span class="text-base font-extrabold tracking-tight">BK</span>
                </span>
                <h3 class="subtitle mt-5 text-white">Belum Kompeten (BK)</h3>
                <p class="mt-3 text-[0.9375rem] leading-relaxed text-white/75">
                    Masih ada unit yang belum terpenuhi. Asesor menyampaikan unit mana yang kurang, supaya Anda tahu apa yang perlu dipelajari.
                </p>
            </article>
        </div>
    </div>
</section>
