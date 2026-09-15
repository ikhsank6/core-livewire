<aside class="space-y-4 lg:sticky lg:top-24">

    {{-- Kategori --}}
    <nav aria-label="Kategori berita"
         class="bg-paper dark:bg-ink-card border border-hairline dark:border-pine-soft rounded-2xl overflow-hidden">
        <h2 class="t-micro text-marigold-deep dark:text-marigold px-6 py-5 border-b border-hairline dark:border-pine-soft">
            Kategori
        </h2>

        <ul class="p-3">
            <li>
                <a href="{{ route('news.index') }}"
                   @if(! request('category')) aria-current="page" @endif
                   class="flex items-center justify-between gap-3 px-3 py-3 rounded-xl text-[0.9375rem] transition-colors
                          {{ ! request('category')
                                ? 'bg-marigold text-ink'
                                : 'text-moss dark:text-bone-dim hover:bg-mineral dark:hover:bg-pine/30' }}">
                    <span>Semua</span>
                </a>
            </li>

            @foreach($categories as $category)
                @php $isActive = request('category') === $category->slug; @endphp
                <li>
                    <a href="{{ route('news.index', ['category' => $category->slug]) }}"
                       @if($isActive) aria-current="page" @endif
                       class="flex items-center justify-between gap-3 px-3 py-3 rounded-xl text-[0.9375rem] transition-colors
                              {{ $isActive
                                    ? 'bg-marigold text-ink'
                                    : 'text-moss dark:text-bone-dim hover:bg-mineral dark:hover:bg-pine/30' }}">
                        <span class="truncate">{{ $category->name }}</span>
                        <span class="t-data text-[0.6875rem] shrink-0 px-2 py-0.5 rounded-md
                                     {{ $isActive ? 'bg-ink/10 text-ink' : 'bg-mineral dark:bg-pine/40' }}">
                            {{ $category->news_count }}
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    {{-- Terkini --}}
    @if($recentNews->count() > 0)
        <div class="bg-paper dark:bg-ink-card border border-hairline dark:border-pine-soft rounded-2xl overflow-hidden">
            <h2 class="t-micro text-marigold-deep dark:text-marigold px-6 py-5 border-b border-hairline dark:border-pine-soft">
                Terkini
            </h2>

            <ul class="divide-y divide-hairline dark:divide-pine-soft">
                @foreach($recentNews as $recent)
                    <li>
                        <a href="{{ route('news.show', $recent->slug) }}"
                           class="group flex gap-4 px-6 py-4 hover:bg-mineral dark:hover:bg-pine/30 transition-colors">
                            <span class="t-data text-[0.6875rem] text-moss/60 dark:text-bone-dim/60 shrink-0 pt-0.5">
                                {{ 'REG-'.str_pad((string) $recent->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                            <span class="min-w-0">
                                <span class="block text-[0.9375rem] leading-snug text-ink dark:text-bone
                                             group-hover:text-marigold-deep dark:group-hover:text-marigold-deep dark:hover:text-marigold transition-colors line-clamp-2">
                                    {{ $recent->title }}
                                </span>
                                <time datetime="{{ ($recent->published_at ?? $recent->created_at)?->toDateString() }}"
                                      class="block t-data text-[0.6875rem] text-moss/70 dark:text-bone-dim/70 mt-1.5">
                                    {{ ($recent->published_at ?? $recent->created_at)?->translatedFormat('d M Y') }}
                                </time>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Kontak --}}
    <div class="bg-ink dark:bg-ink-card text-bone border border-transparent
                dark:border-pine-soft rounded-2xl p-6">
        <h2 class="t-card text-bone">Ada pertanyaan?</h2>
        <p class="mt-2.5 text-[0.9375rem] leading-relaxed text-bone-dim">
            Kanal kontak dan jadwal layanan tercantum di halaman profil.
        </p>
        <a href="{{ route('about') }}"
           class="mt-5 inline-flex items-center justify-center gap-2.5 w-full h-12 rounded-full
                  bg-marigold text-ink hover:bg-marigold-dim transition-colors t-micro">
            Hubungi kami
            <svg class="w-3.5 h-3.5" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                 stroke-width="1.8" aria-hidden="true">
                <path d="M3 8h10M9 4l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
    </div>
</aside>
