<aside class="space-y-10 lg:sticky lg:top-24">

    {{-- Kategori --}}
    <nav aria-label="Kategori berita">
        <h2 class="subtitle">Kategori</h2>

        <ul class="mt-4 border-t border-line">
            <li class="border-b border-line">
                <a href="{{ route('news.index') }}"
                   @if(! request('category')) aria-current="page" @endif
                   class="flex items-center justify-between gap-3 min-h-12 px-1 text-[0.9375rem] transition-colors
                          {{ ! request('category') ? 'font-bold text-ink' : 'text-body hover:text-ink' }}">
                    <span class="inline-flex items-center gap-2.5">
                        <svg class="h-4 w-4 shrink-0 {{ ! request('category') ? 'text-brand' : 'text-muted' }}" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h10"/></svg>
                        Semua
                    </span>
                    @if(! request('category'))<span class="w-1.5 h-1.5 rounded-full bg-gold" aria-hidden="true"></span>@endif
                </a>
            </li>

            @foreach($categories as $category)
                @php $isActive = request('category') === $category->slug; @endphp
                <li class="border-b border-line">
                    <a href="{{ route('news.index', ['category' => $category->slug]) }}"
                       @if($isActive) aria-current="page" @endif
                       class="flex items-center justify-between gap-3 min-h-12 px-1 text-[0.9375rem] transition-colors
                              {{ $isActive ? 'font-bold text-ink' : 'text-body hover:text-ink' }}">
                        <span class="inline-flex min-w-0 items-center gap-2.5">
                            @include('website.partials.news-icon', ['slug' => $category->slug, 'class' => 'h-4 w-4 shrink-0 '.($isActive ? 'text-brand' : 'text-muted')])
                            <span class="truncate">{{ $category->name }}</span>
                        </span>
                        <span class="flex items-center gap-2 shrink-0 tabular-nums text-sm text-muted">
                            {{ $category->news_count }}
                            @if($isActive)<span class="w-1.5 h-1.5 rounded-full bg-gold" aria-hidden="true"></span>@endif
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    {{-- Terkini --}}
    @if($recentNews->count() > 0)
        <div>
            <h2 class="subtitle">Terkini</h2>

            <ul class="mt-4 border-t border-line">
                @foreach($recentNews as $recent)
                    <li class="border-b border-line">
                        <a href="{{ route('news.show', $recent->slug) }}" class="group block py-4 px-1">
                            <span class="block text-[0.9375rem] font-semibold leading-snug text-ink group-hover:text-link transition-colors line-clamp-2">
                                {{ $recent->title }}
                            </span>
                            <time datetime="{{ ($recent->published_at ?? $recent->created_at)?->toDateString() }}"
                                  class="mt-1.5 flex items-center gap-1.5 text-sm text-muted">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 7V3m8 4V3M5 11h14M6 5h12a1 1 0 011 1v13a1 1 0 01-1 1H6a1 1 0 01-1-1V6a1 1 0 011-1z"/></svg>
                                {{ ($recent->published_at ?? $recent->created_at)?->translatedFormat('d M Y') }}
                            </time>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Kontak --}}
    <div class="on-dark rounded-2xl bg-navy p-6 text-white">
        <h2 class="subtitle text-white">Ada pertanyaan?</h2>
        <p class="mt-2 text-[0.9375rem] leading-relaxed text-white/75">
            Kanal kontak dan jadwal layanan ada di halaman profil.
        </p>
        <a href="{{ route('about') }}#kontak" class="btn btn-accent mt-5 w-full">Lihat kontak</a>
    </div>
</aside>
