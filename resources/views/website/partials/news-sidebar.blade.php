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
                    <span>Semua</span>
                    @if(! request('category'))<span class="w-1.5 h-1.5 rounded-full bg-kunyit" aria-hidden="true"></span>@endif
                </a>
            </li>

            @foreach($categories as $category)
                @php $isActive = request('category') === $category->slug; @endphp
                <li class="border-b border-line">
                    <a href="{{ route('news.index', ['category' => $category->slug]) }}"
                       @if($isActive) aria-current="page" @endif
                       class="flex items-center justify-between gap-3 min-h-12 px-1 text-[0.9375rem] transition-colors
                              {{ $isActive ? 'font-bold text-ink' : 'text-body hover:text-ink' }}">
                        <span class="truncate">{{ $category->name }}</span>
                        <span class="flex items-center gap-2 shrink-0 tabular-nums text-sm text-muted">
                            {{ $category->news_count }}
                            @if($isActive)<span class="w-1.5 h-1.5 rounded-full bg-kunyit" aria-hidden="true"></span>@endif
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
                                  class="mt-1.5 block text-sm text-muted">
                                {{ ($recent->published_at ?? $recent->created_at)?->translatedFormat('d M Y') }}
                            </time>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Kontak --}}
    <div class="on-dark rounded-2xl bg-nila p-6 text-white">
        <h2 class="subtitle text-white">Ada pertanyaan?</h2>
        <p class="mt-2 text-[0.9375rem] leading-relaxed text-white/75">
            Kanal kontak dan jadwal layanan ada di halaman profil.
        </p>
        <a href="{{ route('about') }}#kontak" class="ticket mt-5 w-full">Lihat kontak</a>
    </div>
</aside>
