<div>
    <!-- Hero Section with Carousel -->
    <section id="home" class="pt-16">
        @if($carousels->count() > 0)
            <div x-data="{ 
                    activeSlide: 0,
                    slides: {{ $carousels->count() }},
                    autoplay: null,
                    init() {
                        this.startAutoplay();
                    },
                    startAutoplay() {
                        this.autoplay = setInterval(() => {
                            this.nextSlide();
                        }, 5000);
                    },
                    stopAutoplay() {
                        clearInterval(this.autoplay);
                    },
                    nextSlide() {
                        this.activeSlide = (this.activeSlide + 1) % this.slides;
                    },
                    prevSlide() {
                        this.activeSlide = (this.activeSlide - 1 + this.slides) % this.slides;
                    },
                    goToSlide(index) {
                        this.activeSlide = index;
                        this.stopAutoplay();
                        this.startAutoplay();
                    }
                }"
                @mouseenter="stopAutoplay()"
                @mouseleave="startAutoplay()"
                class="relative h-[600px] overflow-hidden">
                
                <!-- Slides -->
                @foreach($carousels as $index => $carousel)
                    <div x-show="activeSlide === {{ $index }}"
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 transform scale-105"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="absolute inset-0">
                        <div class="absolute inset-0 bg-linear-to-r from-zinc-900/90 to-zinc-900/40 z-10"></div>
                        <img src="{{ Storage::url($carousel->image) }}" alt="{{ $carousel->title }}"
                            class="w-full h-full object-cover">
                        
                        <div class="absolute inset-0 z-20 flex items-center">
                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                                <div class="max-w-2xl">
                                    <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 leading-tight animate-fade-in-up">
                                        {{ $carousel->title }}
                                    </h1>
                                    @if($carousel->description)
                                        <p class="text-lg md:text-xl text-zinc-300 mb-8 animate-fade-in-up" style="animation-delay: 0.2s">
                                            {{ $carousel->description }}
                                        </p>
                                    @endif
                                    @if($carousel->button_text && $carousel->button_link)
                                        <a href="{{ $carousel->button_link }}"
                                            class="inline-flex items-center gap-2 px-8 py-4 text-lg font-semibold text-white bg-linear-to-r from-indigo-500 to-purple-600 rounded-xl hover:opacity-90 transition-all shadow-2xl shadow-indigo-500/25 animate-fade-in-up"
                                            style="animation-delay: 0.4s">
                                            {{ $carousel->button_text }}
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Navigation Arrows -->
                <button @click="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 z-30 p-3 bg-white/10 backdrop-blur-sm rounded-full text-white hover:bg-white/20 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button @click="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 z-30 p-3 bg-white/10 backdrop-blur-sm rounded-full text-white hover:bg-white/20 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <!-- Dots Indicator -->
                <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-30 flex gap-3">
                    @foreach($carousels as $index => $carousel)
                        <button @click="goToSlide({{ $index }})"
                            :class="activeSlide === {{ $index }} ? 'bg-white w-8' : 'bg-white/40 w-3'"
                            class="h-3 rounded-full transition-all duration-300 hover:bg-white/70">
                        </button>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Default Hero without carousel -->
            <div class="relative h-[600px] bg-linear-to-br from-indigo-600 via-purple-600 to-pink-500">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center max-w-3xl px-4">
                        <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6">
                            Selamat Datang
                        </h1>
                        <p class="text-xl text-white/80 mb-8">
                            Temukan informasi terbaru dan layanan terbaik dari kami
                        </p>
                        <a href="#news" class="inline-flex items-center gap-2 px-8 py-4 text-lg font-semibold text-indigo-600 bg-white rounded-xl hover:bg-white/90 transition-all shadow-2xl">
                            Jelajahi
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </section>

    <!-- Featured News Section -->
    @if($featuredNews->count() > 0)
        <section class="py-20 bg-linear-to-b from-zinc-50 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <span class="inline-block px-4 py-1.5 text-sm font-medium text-indigo-600 bg-indigo-100 rounded-full mb-4">Featured</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900">Berita Pilihan</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($featuredNews as $news)
                        <article class="group bg-white rounded-2xl shadow-lg shadow-zinc-200/50 overflow-hidden hover:shadow-xl hover:shadow-zinc-300/50 transition-all duration-300 hover:-translate-y-1">
                            <div class="aspect-video overflow-hidden">
                                @if($news->image)
                                    <img src="{{ Storage::url($news->image) }}" alt="{{ $news->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-linear-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-6">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="px-3 py-1 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-full">
                                        {{ $news->category?->name ?? 'Uncategorized' }}
                                    </span>
                                    <span class="text-xs text-zinc-400">
                                        {{ $news->published_at?->format('d M Y') }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-zinc-900 mb-2 group-hover:text-indigo-600 transition-colors line-clamp-2">
                                    {{ $news->title }}
                                </h3>
                                <p class="text-zinc-500 line-clamp-3">{{ $news->excerpt }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Latest News Section -->
    <section id="news" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 text-sm font-medium text-purple-600 bg-purple-100 rounded-full mb-4">Latest</span>
                <h2 class="text-3xl md:text-4xl font-bold text-zinc-900">Berita Terbaru</h2>
                <p class="mt-4 text-lg text-zinc-500 max-w-2xl mx-auto">
                    Ikuti perkembangan terbaru dari kami
                </p>
            </div>

            @if($latestNews->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($latestNews as $news)
                        <article class="group flex flex-col bg-zinc-50 rounded-2xl overflow-hidden hover:bg-white hover:shadow-lg transition-all duration-300">
                            <div class="aspect-video overflow-hidden">
                                @if($news->image)
                                    <img src="{{ Storage::url($news->image) }}" alt="{{ $news->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-linear-to-br from-zinc-200 to-zinc-300 flex items-center justify-center">
                                        <svg class="w-10 h-10 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex items-center gap-2 text-sm text-zinc-500 mb-3">
                                    <span class="text-indigo-500 font-medium">{{ $news->category?->name ?? 'Uncategorized' }}</span>
                                    <span>•</span>
                                    <span>{{ $news->published_at?->format('d M Y') }}</span>
                                </div>
                                <h3 class="text-lg font-bold text-zinc-900 mb-2 group-hover:text-indigo-600 transition-colors line-clamp-2">
                                    {{ $news->title }}
                                </h3>
                                <p class="text-zinc-500 text-sm line-clamp-2 flex-1">{{ $news->excerpt }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-zinc-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                    <p class="text-zinc-500">Belum ada berita terbaru</p>
                </div>
            @endif
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="py-20 bg-linear-to-b from-zinc-900 to-zinc-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 text-sm font-medium text-indigo-400 bg-indigo-500/20 rounded-full mb-4">About</span>
                <h2 class="text-3xl md:text-4xl font-bold">Tentang Kami</h2>
            </div>

            @if($aboutUs)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        @if($aboutUs->logo)
                            <img src="{{ Storage::url($aboutUs->logo) }}" alt="{{ $aboutUs->company_name }}"
                                class="w-32 h-32 object-contain mb-6 bg-white rounded-2xl p-4">
                        @endif
                        <h3 class="text-2xl font-bold mb-4">{{ $aboutUs->company_name }}</h3>
                        <div class="prose prose-invert prose-lg max-w-none">
                            {!! $aboutUs->description !!}
                        </div>
                    </div>
                    <div class="space-y-6">
                        <!-- Contact Info -->
                        <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                            <h4 class="font-semibold text-lg mb-4">Informasi Kontak</h4>
                            <ul class="space-y-4">
                                @if($aboutUs->address)
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-indigo-400 mt-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="text-zinc-300">{{ $aboutUs->address }}</span>
                                    </li>
                                @endif
                                @if($aboutUs->phone)
                                    <li class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <a href="tel:{{ $aboutUs->phone }}" class="text-zinc-300 hover:text-white transition-colors">{{ $aboutUs->phone }}</a>
                                    </li>
                                @endif
                                @if($aboutUs->email)
                                    <li class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <a href="mailto:{{ $aboutUs->email }}" class="text-zinc-300 hover:text-white transition-colors">{{ $aboutUs->email }}</a>
                                    </li>
                                @endif
                                @if($aboutUs->whatsapp)
                                    <li class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-green-400 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                        </svg>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $aboutUs->whatsapp) }}" target="_blank" class="text-zinc-300 hover:text-white transition-colors">{{ $aboutUs->whatsapp }}</a>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <!-- Social Media -->
                        @if($aboutUs->facebook || $aboutUs->instagram || $aboutUs->twitter || $aboutUs->youtube || $aboutUs->linkedin)
                            <div class="flex items-center gap-4">
                                @if($aboutUs->facebook)
                                    <a href="{{ $aboutUs->facebook }}" target="_blank" class="p-3 bg-white/10 rounded-xl hover:bg-white/20 transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    </a>
                                @endif
                                @if($aboutUs->instagram)
                                    <a href="{{ $aboutUs->instagram }}" target="_blank" class="p-3 bg-white/10 rounded-xl hover:bg-white/20 transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"/></svg>
                                    </a>
                                @endif
                                @if($aboutUs->twitter)
                                    <a href="{{ $aboutUs->twitter }}" target="_blank" class="p-3 bg-white/10 rounded-xl hover:bg-white/20 transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                    </a>
                                @endif
                                @if($aboutUs->youtube)
                                    <a href="{{ $aboutUs->youtube }}" target="_blank" class="p-3 bg-white/10 rounded-xl hover:bg-white/20 transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                    </a>
                                @endif
                                @if($aboutUs->linkedin)
                                    <a href="{{ $aboutUs->linkedin }}" target="_blank" class="p-3 bg-white/10 rounded-xl hover:bg-white/20 transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-zinc-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <p class="text-zinc-400">Informasi perusahaan belum tersedia</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Map Section -->
    @if($aboutUs && $aboutUs->map_embed)
        <section id="contact" class="py-20 bg-zinc-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <span class="inline-block px-4 py-1.5 text-sm font-medium text-green-600 bg-green-100 rounded-full mb-4">Location</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900">Lokasi Kami</h2>
                </div>

                <div class="bg-white rounded-2xl overflow-hidden shadow-xl">
                    <div class="aspect-21/9">
                        {!! $aboutUs->map_embed !!}
                    </div>
                </div>
            </div>
        </section>
    @endif
</div>
