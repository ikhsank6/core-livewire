<div class="space-y-6">
    {{-- Categories --}}
    <div
        class="bg-white dark:bg-dark-card rounded-xl p-6 shadow-sm dark:shadow-none border border-transparent dark:border-dark-border">
        <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-4 flex items-center gap-2">
            <span class="w-1 h-6 bg-primary rounded"></span>
            ALL CATEGORIES
        </h3>
        <ul class="space-y-3">
            @foreach($categories as $category)
                <li>
                    <a href="{{ route('news.index', ['category' => $category->slug]) }}"
                        class="text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary flex items-center justify-between transition-colors">
                        {{ $category->name }}
                        <span class="text-sm text-slate-400 dark:text-slate-500">({{ $category->news_count }})</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Recent Posts --}}
    <div
        class="bg-white dark:bg-dark-card rounded-xl p-6 shadow-sm dark:shadow-none border border-transparent dark:border-dark-border">
        <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-4 flex items-center gap-2">
            <span class="w-1 h-6 bg-primary rounded"></span>
            RECENT POSTS
        </h3>
        <div class="space-y-4">
            @foreach($recentNews as $recent)
                <a href="{{ route('news.show', $recent->slug) }}" class="flex gap-3 group">
                    <div class="w-16 h-16 rounded-lg overflow-hidden shrink-0">
                        @if($recent->image)
                            <img src="{{ Storage::url($recent->image) }}" alt="{{ $recent->title }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-slate-200 dark:bg-dark flex items-center justify-center">
                                <svg class="w-6 h-6 text-slate-400 dark:text-slate-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h4
                            class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-primary line-clamp-2 transition-colors">
                            {{ $recent->title }}
                        </h4>
                        <span
                            class="text-xs text-slate-400 dark:text-slate-500">{{ $recent->published_at?->format('d M Y') }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>