@php
    $contacts = collect([
        ['icon' => 'map-pin',  'label' => 'Alamat',  'value' => $aboutUs?->address, 'href' => null],
        ['icon' => 'phone',    'label' => 'Telepon', 'value' => $aboutUs?->phone,   'href' => $aboutUs?->phone ? 'tel:'.preg_replace('/\D+/', '', $aboutUs->phone) : null],
        ['icon' => 'envelope', 'label' => 'Surel',   'value' => $aboutUs?->email,   'href' => $aboutUs?->email ? 'mailto:'.$aboutUs->email : null],
    ])->filter(fn ($c) => filled($c['value']));

    $name = $aboutUs->company_name ?? config('app.name');
@endphp

<footer class="on-dark relative isolate overflow-hidden bg-navy text-white">
    <div class="rosette -bottom-48 -right-40 -z-10 w-[34rem] max-w-none text-white/[0.05]" aria-hidden="true"></div>

    <div class="wrap pt-14 md:pt-18">

        {{-- Identity, tagline & contacts in one clean column --}}
        <div class="max-w-lg">
            {{-- Logo / name --}}
            <div class="flex items-center gap-3 mb-5">
            @include('website.partials.logo', ['textClass' => 'font-extrabold text-lg tracking-tight text-white'])
            </div>

            <p class="text-sm leading-relaxed text-white/65 max-w-[42ch]">
                Menyelenggarakan uji kompetensi dan penerbitan sertifikat kompetensi kerja sesuai standar nasional.
            </p>

            @if($contacts->isNotEmpty())
                <ul class="mt-7 space-y-3">
                    @foreach($contacts as $contact)
                        <li class="flex items-start gap-3 text-sm text-white/75">
                            <span class="mt-0.5 shrink-0 text-gold/70">
                                @if($contact['icon'] === 'map-pin')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                @elseif($contact['icon'] === 'phone')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                @endif
                            </span>
                            @if($contact['href'])
                                <a href="{{ $contact['href'] }}" class="hover:text-gold-soft transition-colors">{{ $contact['value'] }}</a>
                            @else
                                <span>{{ $contact['value'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="mt-12 flex flex-col-reverse gap-6 border-t border-white/15 py-7 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-white/50">&copy; {{ now()->year }} {{ $name }}. Hak cipta dilindungi.</p>
            @include('website.partials.social-links', ['dark' => true])
        </div>
    </div>
</footer>
