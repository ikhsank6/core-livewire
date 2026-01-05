<div class="space-y-6">
    <!-- Categories -->
    <div class="bg-white rounded-xl p-6 shadow-sm">
        <h3 class="font-bold text-dark text-lg mb-4 flex items-center gap-2">
            <span class="w-1 h-6 bg-primary"></span>
            ALL CATEGORIES
        </h3>
        <ul class="space-y-3">
            @foreach($categories as $category)
                <li>
                    <a href="{{ route('news.index', ['category' => $category->slug]) }}"
                        class="text-gray-600 hover:text-primary flex items-center justify-between">
                        {{ $category->name }}
                        <span class="text-sm text-gray-400">({{ $category->news_count }})</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Recent Posts -->
    <div class="bg-white rounded-xl p-6 shadow-sm">
        <h3 class="font-bold text-dark text-lg mb-4 flex items-center gap-2">
            <span class="w-1 h-6 bg-primary"></span>
            RECENT POSTS
        </h3>
        <div class="space-y-4">
            @foreach($recentNews as $recent)
                <a href="{{ route('news.show', $recent->slug) }}" class="flex gap-3 group">
                    <div class="w-16 h-16 rounded overflow-hidden shrink-0">
                        @if($recent->image)
                            <img src="{{ Storage::url($recent->image) }}" alt="{{ $recent->title }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200"></div>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-dark group-hover:text-primary line-clamp-2">
                            {{ $recent->title }}</h4>
                        <span class="text-xs text-gray-400">{{ $recent->published_at?->format('d M Y') }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>