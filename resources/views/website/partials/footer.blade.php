{{-- ============================================================
     Footer — Premium 4-column layout
     ============================================================ --}}
<footer class="relative bg-dark pt-20 pb-8 overflow-hidden">

    {{-- Background pattern --}}
    <div class="absolute inset-0 pointer-events-none opacity-[0.03]"
         style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 28px 28px;"></div>

    {{-- Decorative top wave --}}
    <div class="absolute -top-14 left-0 right-0 overflow-hidden pointer-events-none">
        <svg class="w-full h-16 block" viewBox="0 0 1440 64" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 64V20C240 56 480 64 720 44C960 24 1200 8 1440 0V64H0Z"
                  style="fill: #0d1117;"/>
        </svg>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-12">

            {{-- ── Col 1: Brand ── --}}
            <div class="sm:col-span-2 lg:col-span-1">
                @include('website.partials.logo', [
                    'logoClass' => 'h-8 brightness-0 invert',
                    'textClass' => 'text-white font-heading font-bold text-base',
                ])
                @if($aboutUs?->description)
                    <p class="mt-4 text-dark-text text-sm leading-relaxed">
                        {{ Str::limit(strip_tags($aboutUs->description), 130) }}
                    </p>
                @endif
                {{-- Social links --}}
                <div class="flex items-center gap-2 mt-5">
                    @foreach([
                        ['icon' => 'twitter',   'url' => $aboutUs?->twitter,   'label' => 'X (Twitter)', 'color' => 'hover:bg-sky-500'],
                        ['icon' => 'instagram', 'url' => $aboutUs?->instagram, 'label' => 'Instagram',   'color' => 'hover:bg-pink-500'],
                        ['icon' => 'linkedin',  'url' => $aboutUs?->linkedin,  'label' => 'LinkedIn',    'color' => 'hover:bg-blue-600'],
                        ['icon' => 'youtube',   'url' => $aboutUs?->youtube,   'label' => 'YouTube',     'color' => 'hover:bg-red-600'],
                        ['icon' => 'facebook',  'url' => $aboutUs?->facebook,  'label' => 'Facebook',    'color' => 'hover:bg-blue-700'],
                    ] as $social)
                        @if($social['url'])
                        <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer"
                           aria-label="{{ $social['label'] }}"
                           class="w-9 h-9 rounded-xl bg-white/5 border border-white/8 {{ $social['color'] }} flex items-center justify-center text-slate-400 hover:text-white transition-all duration-200 hover:scale-110 hover:border-transparent">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                @if($social['icon'] === 'twitter')
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                @elseif($social['icon'] === 'instagram')
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                                @elseif($social['icon'] === 'linkedin')
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                @elseif($social['icon'] === 'youtube')
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                @elseif($social['icon'] === 'facebook')
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                @endif
                            </svg>
                        </a>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- ── Col 2: Navigasi ── --}}
            <div>
                <h5 class="text-white font-heading font-bold text-sm mb-5 uppercase tracking-wider">Navigasi</h5>
                <ul class="space-y-3">
                    @foreach([
                        ['url' => '/',                  'label' => 'Beranda'],
                        ['url' => route('news.index'),  'label' => 'Berita'],
                        ['url' => route('about'),       'label' => 'Tentang Kami'],
                        ['url' => route('about').'#contact', 'label' => 'Kontak'],
                        ['url' => route('auth.login'),  'label' => 'Masuk'],
                    ] as $link)
                    <li>
                        <a href="{{ $link['url'] }}"
                           class="text-dark-text text-sm hover:text-white hover:translate-x-1 transition-all duration-200 inline-flex items-center gap-1.5 group">
                            <span class="w-0 group-hover:w-2.5 h-0.5 bg-primary rounded-full transition-all duration-200 overflow-hidden"></span>
                            {{ $link['label'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- ── Col 3: Kontak ── --}}
            <div>
                <h5 class="text-white font-heading font-bold text-sm mb-5 uppercase tracking-wider">Kontak</h5>
                <ul class="space-y-3 text-sm">
                    @if($aboutUs?->email)
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white/6 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-dark-text text-xs mb-0.5">Email</p>
                            <a href="mailto:{{ $aboutUs->email }}" class="text-slate-300 hover:text-white transition-colors">{{ $aboutUs->email }}</a>
                        </div>
                    </li>
                    @endif
                    @if($aboutUs?->phone)
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white/6 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-dark-text text-xs mb-0.5">Telepon</p>
                            <a href="tel:{{ $aboutUs->phone }}" class="text-slate-300 hover:text-white transition-colors">{{ $aboutUs->phone }}</a>
                        </div>
                    </li>
                    @endif
                    @if($aboutUs?->address)
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white/6 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-dark-text text-xs mb-0.5">Alamat</p>
                            <p class="text-slate-300 text-sm leading-relaxed">{{ $aboutUs->address }}</p>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>

            {{-- ── Col 4: Newsletter ── --}}
            <div>
                <h5 class="text-white font-heading font-bold text-sm mb-5 uppercase tracking-wider">Newsletter</h5>
                <p class="text-dark-text text-sm mb-4 leading-relaxed">Dapatkan berita dan informasi terbaru langsung di inbox Anda.</p>
                <form class="flex gap-2" onsubmit="return false;" aria-label="Berlangganan newsletter">
                    <input type="email"
                           placeholder="Email Anda..."
                           class="flex-1 min-w-0 px-4 py-2.5 rounded-xl bg-white/8 border border-white/12 text-white text-sm placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all">
                    <button type="submit"
                            class="px-4 py-2.5 bg-primary hover:bg-primary-dark text-white rounded-xl text-sm font-bold font-heading transition-all duration-200 hover:scale-105 active:scale-95 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </form>
                @if($aboutUs?->whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $aboutUs->whatsapp) }}" target="_blank"
                   class="mt-4 flex items-center gap-2.5 px-4 py-2.5 bg-green-500/10 hover:bg-green-500/20 border border-green-500/20 rounded-xl text-green-400 text-sm font-medium transition-all duration-200 group">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                    </svg>
                    Chat via WhatsApp
                </a>
                @endif
            </div>
        </div>

        {{-- ── Bottom bar ── --}}
        <div class="mt-14 pt-6 border-t border-white/8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-dark-text text-sm">
                    &copy; {{ date('Y') }} <span class="text-white font-semibold">{{ $aboutUs->company_name ?? config('app.name') }}</span>. Hak cipta dilindungi.
                </p>
                <div class="flex items-center gap-5 text-xs text-dark-text">
                    <a href="{{ route('news.index') }}" class="hover:text-white transition-colors">Berita</a>
                    <a href="{{ route('about') }}" class="hover:text-white transition-colors">Tentang</a>
                    <a href="{{ route('about') }}#contact" class="hover:text-white transition-colors">Kontak</a>
                </div>
            </div>
        </div>
    </div>
</footer>
