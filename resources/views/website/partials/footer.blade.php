@php
    $links = [
        ['label' => 'Beranda', 'href' => route('landing')],
        ['label' => 'Skema sertifikasi', 'href' => route('landing').'#skema'],
        ['label' => 'Alur sertifikasi', 'href' => route('landing').'#alur'],
        ['label' => 'Jadwal uji', 'href' => route('landing').'#jadwal'],
        ['label' => 'Berita', 'href' => route('news.index')],
        ['label' => 'Profil', 'href' => route('about')],
        ['label' => 'Masuk', 'href' => route('login')],
    ];

    $contacts = collect([
        ['label' => 'Alamat',  'value' => $aboutUs?->address, 'href' => null],
        ['label' => 'Telepon', 'value' => $aboutUs?->phone,   'href' => $aboutUs?->phone ? 'tel:'.preg_replace('/\D+/', '', $aboutUs->phone) : null],
        ['label' => 'Surel',   'value' => $aboutUs?->email,   'href' => $aboutUs?->email ? 'mailto:'.$aboutUs->email : null],
    ])->filter(fn ($c) => filled($c['value']));

    $name = $aboutUs->company_name ?? config('app.name');
@endphp

<footer class="on-dark relative isolate overflow-hidden bg-rimba text-white">
    <div class="rosette -bottom-48 -right-40 -z-10 w-[34rem] max-w-none text-white/[0.05]" aria-hidden="true"></div>

    <div class="wrap pt-16 md:pt-20">

        <div class="grid gap-14 md:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)]">

            {{-- Identity and contact --}}
            <div>
                <p class="subtitle max-w-[26ch] text-white">{{ $name }} menyelenggarakan uji kompetensi dan penerbitan sertifikat kompetensi kerja.</p>

                @if($contacts->isNotEmpty())
                    <dl class="mt-10 space-y-5">
                        @foreach($contacts as $contact)
                            <div>
                                <dt class="text-sm text-white/60">{{ $contact['label'] }}</dt>
                                <dd class="mt-1 text-[0.9375rem] font-semibold wrap-break-word">
                                    @if($contact['href'])
                                        <a href="{{ $contact['href'] }}" class="inline-flex min-h-11 items-center underline decoration-white/30 underline-offset-4 transition-colors hover:decoration-cap-soft">{{ $contact['value'] }}</a>
                                    @else
                                        {{ $contact['value'] }}
                                    @endif
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                @endif
            </div>

            {{-- One column of links, set large --}}
            <nav aria-label="Navigasi footer">
                <ul class="md:justify-self-end">
                    @foreach($links as $link)
                        <li>
                            <a href="{{ $link['href'] }}"
                               class="inline-flex min-h-11 items-center text-2xl font-extrabold tracking-tight text-white/85 transition-colors hover:text-cap-soft">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>

        <div class="mt-14 flex flex-col-reverse gap-6 border-t border-white/15 py-7 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-white/60">&copy; {{ now()->year }} {{ $name }}. Hak cipta dilindungi.</p>
            @include('website.partials.social-links', ['dark' => true])
        </div>
    </div>
</footer>
