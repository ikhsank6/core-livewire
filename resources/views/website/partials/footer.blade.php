@php
    $navigasi = [
        ['label' => 'Beranda', 'href' => route('landing')],
        ['label' => 'Layanan', 'href' => route('landing').'#layanan'],
        ['label' => 'Berita',  'href' => route('news.index')],
        ['label' => 'Profil',  'href' => route('about')],
    ];

    $informasi = [
        ['label' => 'Masuk',           'href' => route('login')],
        ['label' => 'Pusat bantuan',   'href' => route('about')],
        ['label' => 'Informasi publik','href' => route('about')],
        ['label' => 'Kebijakan privasi','href' => route('about')],
    ];

    $socials = collect([
        ['key' => 'facebook',  'label' => 'Facebook',  'url' => $aboutUs?->facebook,  'path' => 'M14 8.5V6.8c0-.8.2-1.3 1.4-1.3H17V2.6c-.3 0-1.2-.1-2.2-.1-2.2 0-3.7 1.3-3.7 3.8v2.2H8.6v3h2.5V19H14v-7.5h2.5l.4-3H14Z'],
        ['key' => 'instagram', 'label' => 'Instagram', 'url' => $aboutUs?->instagram, 'path' => 'M10 6.2a3.8 3.8 0 1 0 0 7.6 3.8 3.8 0 0 0 0-7.6Zm0 6.3a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5ZM14 5a.9.9 0 1 0 0 1.8A.9.9 0 0 0 14 5Zm4 5c0-1.7 0-2.6-.3-3.3a4.2 4.2 0 0 0-2.4-2.4C14.6 4 13.7 4 12 4H8c-1.7 0-2.6 0-3.3.3a4.2 4.2 0 0 0-2.4 2.4C2 7.4 2 8.3 2 10v4c0 1.7 0 2.6.3 3.3a4.2 4.2 0 0 0 2.4 2.4c.7.3 1.6.3 3.3.3h4c1.7 0 2.6 0 3.3-.3a4.2 4.2 0 0 0 2.4-2.4c.3-.7.3-1.6.3-3.3v-4Zm-1.5 4.2c0 1.4 0 2.1-.2 2.5a2.7 2.7 0 0 1-1.6 1.6c-.4.2-1.1.2-2.5.2H8c-1.4 0-2.1 0-2.5-.2a2.7 2.7 0 0 1-1.6-1.6c-.2-.4-.2-1.1-.2-2.5V9.8c0-1.4 0-2.1.2-2.5a2.7 2.7 0 0 1 1.6-1.6c.4-.2 1.1-.2 2.5-.2h4.2c1.4 0 2.1 0 2.5.2a2.7 2.7 0 0 1 1.6 1.6c.2.4.2 1.1.2 2.5v4.4Z'],
        ['key' => 'twitter',   'label' => 'X',         'url' => $aboutUs?->twitter,   'path' => 'M15.2 2.9h2.6l-5.7 6.5 6.7 8.8h-5.2L9.5 13l-4.7 5.2H2.2l6.1-7-6.4-8.3h5.4l3.7 4.9 4.2-4.9Zm-.9 13.8h1.4L6.1 4.3H4.6l9.7 12.4Z'],
        ['key' => 'youtube',   'label' => 'YouTube',   'url' => $aboutUs?->youtube,   'path' => 'M18.5 6.2a2.2 2.2 0 0 0-1.6-1.6C15.5 4.2 10 4.2 10 4.2s-5.5 0-6.9.4a2.2 2.2 0 0 0-1.6 1.6C1.1 7.6 1.1 10 1.1 10s0 2.4.4 3.8a2.2 2.2 0 0 0 1.6 1.6c1.4.4 6.9.4 6.9.4s5.5 0 6.9-.4a2.2 2.2 0 0 0 1.6-1.6c.4-1.4.4-3.8.4-3.8s0-2.4-.4-3.8ZM8.3 12.6V7.4l4.6 2.6-4.6 2.6Z'],
        ['key' => 'linkedin',  'label' => 'LinkedIn',  'url' => $aboutUs?->linkedin,  'path' => 'M6.1 17.2H3.2V7.6h2.9v9.6ZM4.6 6.3a1.7 1.7 0 1 1 0-3.4 1.7 1.7 0 0 1 0 3.4Zm12.6 10.9h-2.9v-4.7c0-1.1 0-2.5-1.5-2.5s-1.8 1.2-1.8 2.4v4.8H8.1V7.6H11v1.3h.1c.4-.8 1.4-1.6 2.8-1.6 3 0 3.5 2 3.5 4.5v5.4Z'],
    ])->filter(fn ($s) => filled($s['url']));
@endphp

<footer class="bg-ink text-bone border-t border-transparent dark:border-pine-soft">
    <div class="mx-auto max-w-300 px-5 md:px-8 pt-16 pb-10 md:pt-20">

        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 md:gap-8">

            {{-- Brand + contact --}}
            <div class="md:col-span-5 lg:col-span-6">
                <a href="{{ route('landing') }}" class="inline-block text-bone">
                    @include('website.partials.logo', [
                        'mark' => 'bone',
                        'textClass' => 'font-display font-bold text-lg tracking-tight',
                    ])
                </a>

                <p class="mt-5 max-w-sm text-[0.9375rem] leading-relaxed text-bone-dim">
                    Portal layanan terpadu untuk perizinan, administrasi kependudukan, dan informasi resmi daerah.
                </p>

                <dl class="mt-7 space-y-3 text-[0.9375rem]">
                    @if($aboutUs?->address)
                        <div class="flex gap-3">
                            <dt class="sr-only">Alamat</dt>
                            <svg class="w-4 h-4 mt-1 shrink-0 text-marigold" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                <path d="M12 21s7-5.2 7-10.5a7 7 0 1 0-14 0C5 15.8 12 21 12 21Z" stroke-linejoin="round"/>
                                <circle cx="12" cy="10.5" r="2.5"/>
                            </svg>
                            <dd class="text-bone-dim">{{ $aboutUs->address }}</dd>
                        </div>
                    @endif

                    @if($aboutUs?->phone)
                        <div class="flex gap-3">
                            <dt class="sr-only">Telepon</dt>
                            <svg class="w-4 h-4 mt-1 shrink-0 text-marigold" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                <path d="M5 4h3l1.5 4-2 1.5a12 12 0 0 0 5.5 5.5L14.5 13l4 1.5V18a2 2 0 0 1-2.2 2A15.5 15.5 0 0 1 3.5 6.2 2 2 0 0 1 5 4Z"
                                      stroke-linejoin="round"/>
                            </svg>
                            <dd><a href="tel:{{ preg_replace('/\D+/', '', $aboutUs->phone) }}"
                                   class="t-data text-bone-dim hover:text-bone transition-colors">{{ $aboutUs->phone }}</a></dd>
                        </div>
                    @endif

                    @if($aboutUs?->email)
                        <div class="flex gap-3">
                            <dt class="sr-only">Surel</dt>
                            <svg class="w-4 h-4 mt-1 shrink-0 text-marigold" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                <path d="m3.5 7 8.5 6 8.5-6" stroke-linejoin="round"/>
                            </svg>
                            <dd><a href="mailto:{{ $aboutUs->email }}"
                                   class="text-bone-dim hover:text-bone transition-colors">{{ $aboutUs->email }}</a></dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Navigasi --}}
            <nav class="md:col-span-3 lg:col-span-3" aria-label="Navigasi footer">
                <h2 class="t-micro text-marigold">Navigasi</h2>
                <ul class="mt-5 space-y-3.5">
                    @foreach($navigasi as $link)
                        <li>
                            <a href="{{ $link['href'] }}"
                               class="text-[0.9375rem] text-bone-dim hover:text-bone transition-colors">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            {{-- Informasi --}}
            <nav class="md:col-span-4 lg:col-span-3" aria-label="Informasi">
                <h2 class="t-micro text-marigold">Informasi</h2>
                <ul class="mt-5 space-y-3.5">
                    @foreach($informasi as $link)
                        <li>
                            <a href="{{ $link['href'] }}"
                               class="text-[0.9375rem] text-bone-dim hover:text-bone transition-colors">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>

        {{-- Bottom bar --}}
        <div class="mt-14 pt-7 border-t border-bone/10 flex flex-col sm:flex-row items-center
                    justify-between gap-5">
            <p class="t-micro text-bone-dim text-center sm:text-left">
                &copy; {{ now()->year }} {{ $aboutUs->company_name ?? config('app.name') }} — Hak cipta dilindungi
            </p>

            @if($socials->isNotEmpty())
                <ul class="flex items-center gap-2">
                    @foreach($socials as $social)
                        <li>
                            <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer"
                               aria-label="{{ $social['label'] }}"
                               class="w-11 h-11 rounded-full grid place-items-center border border-bone/12
                                      text-bone-dim hover:text-ink hover:bg-marigold hover:border-marigold
                                      transition-colors">
                                <svg class="w-4.5 h-4.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="{{ $social['path'] }}"/>
                                </svg>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</footer>
