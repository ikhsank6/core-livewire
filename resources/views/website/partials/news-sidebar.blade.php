{{-- News Sidebar --}}
<aside class="space-y-6 lg:sticky lg:top-28">

    {{-- Categories --}}
    <div class="bg-white dark:bg-dark-card rounded-2xl border border-slate-100 dark:border-dark-border overflow-hidden shadow-sm">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-dark-border">
            <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2.5 text-sm">
                <span class="w-7 h-7 bg-primary/10 dark:bg-primary/20 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </span>
                Kategori
            </h3>
        </div>
        <nav aria-label="Kategori berita" class="p-3">
            <ul class="space-y-0.5">
                {{-- All --}}
                <li>
                    <a href="{{ route('news.index') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ !request('category') ? 'bg-primary/10 dark:bg-primary/20 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                        <span>Semua</span>
                    </a>
                </li>
                @foreach($categories as $category)
                <li>
                    <a href="{{ route('news.index', ['category' => $category->slug]) }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request('category') == $category->slug ? 'bg-primary/10 dark:bg-primary/20 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                        <span>{{ $category->name }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ request('category') == $category->slug ? 'bg-primary/20 text-primary' : 'bg-slate-100 dark:bg-dark text-slate-500 dark:text-slate-400 group-hover:bg-primary/10 group-hover:text-primary' }} transition-colors">
                            {{ $category->news_count }}
                        </span>
                    </a>
                </li>
                @endforeach
            </ul>
        </nav>
    </div>

    {{-- Recent Posts --}}
    @if($recentNews->count() > 0)
    <div class="bg-white dark:bg-dark-card rounded-2xl border border-slate-100 dark:border-dark-border overflow-hidden shadow-sm">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-dark-border">
            <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2.5 text-sm">
                <span class="w-7 h-7 bg-accent/10 dark:bg-accent/20 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                Berita Terkini
            </h3>
        </div>
        <div class="p-3 space-y-1">
            @foreach($recentNews as $recent)
            <a href="{{ route('news.show', $recent->slug) }}"
               class="flex gap-3 p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-white/5 transition-all duration-200 group">
                <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0 bg-slate-100 dark:bg-dark ring-1 ring-slate-100 dark:ring-dark-border group-hover:ring-primary/30 transition-all duration-200">
                    @if($recent->image)
                        <img src="{{ Storage::url($recent->image) }}" alt="{{ $recent->title }}"
                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-slate-400 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-primary line-clamp-2 transition-colors duration-200 leading-snug mb-1">
                        {{ $recent->title }}
                    </h4>
                    <span class="text-xs text-slate-400 dark:text-slate-500">
                        {{ $recent->published_at?->format('d M Y') }}
                    </span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- CTA kontak --}}
    <div class="bg-linear-to-br from-primary to-primary-dark rounded-2xl p-5 text-white">
        <h3 class="font-bold text-base mb-1.5">Ada Pertanyaan?</h3>
        <p class="text-white/80 text-sm mb-4">Tim kami siap membantu Anda.</p>
        <a href="{{ route('about') }}#contact"
           class="inline-flex items-center gap-2 w-full justify-center px-4 py-2.5 bg-white text-primary font-semibold rounded-xl hover:bg-white/90 transition-colors text-sm">
            Hubungi Kami
        </a>
    </div>

</aside>
