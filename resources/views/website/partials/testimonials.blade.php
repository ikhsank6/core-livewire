@php
    // Static content — no Testimonial model. Initials stand in for avatars
    // rather than fabricating photographs of people.
    $voices = [
        [
            'quote' => 'Berkas izin warung saya selesai tiga hari. Sebelumnya saya bolak-balik empat kali hanya untuk menanyakan posisi berkas.',
            'name' => 'Rahmawati',
            'role' => 'Pemilik usaha mikro, Kel. Bumiwangi',
        ],
        [
            'quote' => 'Yang paling membantu itu nomor registrasinya. Saya bisa cek sendiri dari rumah tanpa menelepon kantor.',
            'name' => 'Sudarsono',
            'role' => 'Ketua RW 04, Kel. Pasar Lama',
        ],
        [
            'quote' => 'Sejak antrean dipisah, loket pengambilan jauh lebih cepat. Dulu bisa setengah jam, sekarang sepuluh menit.',
            'name' => 'Nur Aisyah',
            'role' => 'Petugas layanan kependudukan',
        ],
    ];
@endphp

<section class="py-20 md:py-28">
    <div class="mx-auto max-w-300 px-5 md:px-8">

        @include('website.partials.section-heading', [
            'eyebrow' => 'Suara warga',
            'title' => 'Diukur dari waktu tunggu, bukan dari brosur.',
        ])

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($voices as $voice)
                <figure class="aos-init bg-paper dark:bg-ink-card border border-hairline dark:border-pine-soft
                               rounded-2xl p-7 flex flex-col">

                    <svg class="w-7 h-7 text-marigold mb-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M9.4 5.5C6.3 7 4.5 9.9 4.5 13.4c0 3.1 1.9 5.1 4.4 5.1 2.3 0 3.9-1.6 3.9-3.8 0-2.1-1.5-3.6-3.4-3.6-.4 0-.8.1-1 .2.4-1.6 1.8-3.1 3.6-4.1l-2.6-1.7Zm9.1 0C15.4 7 13.6 9.9 13.6 13.4c0 3.1 1.9 5.1 4.4 5.1 2.3 0 3.9-1.6 3.9-3.8 0-2.1-1.5-3.6-3.4-3.6-.4 0-.8.1-1 .2.4-1.6 1.8-3.1 3.6-4.1l-2.6-1.7Z"/>
                    </svg>

                    <blockquote class="flex-1 text-[0.9375rem] leading-relaxed text-ink dark:text-bone">
                        {{ $voice['quote'] }}
                    </blockquote>

                    <figcaption class="mt-7 pt-6 border-t border-hairline dark:border-pine-soft flex items-center gap-3.5">
                        <span class="w-10 h-10 rounded-full bg-mineral dark:bg-pine/40 grid place-items-center
                                     t-data text-[0.8125rem] text-moss dark:text-bone-dim shrink-0"
                              aria-hidden="true">
                            {{ Str::of($voice['name'])->explode(' ')->take(2)->map(fn ($w) => Str::substr($w, 0, 1))->implode('') }}
                        </span>
                        <span class="min-w-0">
                            <span class="block text-[0.9375rem] font-medium text-ink dark:text-bone truncate">
                                {{ $voice['name'] }}
                            </span>
                            <span class="block t-micro text-moss dark:text-bone-dim mt-1 truncate">
                                {{ $voice['role'] }}
                            </span>
                        </span>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>
