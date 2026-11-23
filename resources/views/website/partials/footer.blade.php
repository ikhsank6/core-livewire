@php
    $links = [
        ['label' => 'Beranda', 'href' => route('landing')],
        ['label' => 'Layanan', 'href' => route('landing').'#layanan'],
        ['label' => 'Berita',  'href' => route('news.index')],
        ['label' => 'Profil',  'href' => route('about')],
        ['label' => 'FAQ',     'href' => route('landing').'#faq'],
        ['label' => 'Masuk',   'href' => route('login')],
    ];

    $contacts = collect([
        ['label' => 'Alamat',  'value' => $aboutUs?->address, 'href' => null],
        ['label' => 'Telepon', 'value' => $aboutUs?->phone,   'href' => $aboutUs?->phone ? 'tel:'.preg_replace('/\D+/', '', $aboutUs->phone) : null],
        ['label' => 'Surel',   'value' => $aboutUs?->email,   'href' => $aboutUs?->email ? 'mailto:'.$aboutUs->email : null],
    ])->filter(fn ($c) => filled($c['value']));

    $name = $aboutUs->company_name ?? config('app.name');
@endphp

<footer class="on-dark bg-nila text-white">
    <div class="wrap pt-16 md:pt-24">

        <div class="grid gap-14 md:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)]">

            {{-- Contact --}}
            <div>
                <p class="subtitle max-w-[24ch] text-white">Portal layanan terpadu untuk perizinan, kependudukan, dan informasi resmi daerah.</p>

                @if($contacts->isNotEmpty())
                    <dl class="mt-10 space-y-5">
                        @foreach($contacts as $contact)
                            <div>
                                <dt class="text-sm text-white/60">{{ $contact['label'] }}</dt>
                                <dd class="mt-1 text-[0.9375rem] font-medium wrap-break-word">
                                    @if($contact['href'])
                                        <a href="{{ $contact['href'] }}" class="inline-flex min-h-11 items-center underline decoration-white/30 underline-offset-4 hover:decoration-kunyit transition-colors">{{ $contact['value'] }}</a>
                                    @else
                                        {{ $contact['value'] }}
                                    @endif
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                @endif
            </div>

            {{-- One column of large links: the site is small enough not to need four --}}
            <nav aria-label="Navigasi footer">
                <ul class="md:justify-self-end">
                    @foreach($links as $link)
                        <li>
                            <a href="{{ $link['href'] }}"
                               class="inline-flex items-center min-h-12 font-display text-3xl font-bold tracking-tight text-white/85 hover:text-kunyit transition-colors"
                               style="font-variation-settings: 'wdth' 90;">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>

        <div class="mt-16 flex flex-col-reverse gap-6 border-t border-white/15 py-7 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-white/60">&copy; {{ now()->year }} {{ $name }}. Hak cipta dilindungi.</p>
            @include('website.partials.social-links', ['dark' => true])
        </div>
    </div>
</footer>
