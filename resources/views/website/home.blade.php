@extends('layouts.website')

@section('title', ($aboutUs->company_name ?? config('app.name')) . ' - Home')

@section('swiper', true)

@section('content')
    {{-- ==================== HERO CAROUSEL ==================== --}}
    @if($carousels->count() > 0)
    <section class="relative h-screen">
        <div class="swiper heroCarousel h-full">
            <div class="swiper-wrapper">
                @foreach($carousels as $carousel)
                <div class="swiper-slide">
                    <div class="relative h-screen flex items-center"
                         style="background-image: linear-gradient(to right, rgba(10,10,15,0.9) 0%, rgba(10,10,15,0.6) 50%, rgba(10,10,15,0.3) 100%), url('{{ $carousel->image ? Storage::url($carousel->image) : 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1920' }}'); background-size: cover; background-position: center;">
                        <div class="max-w-7xl mx-auto px-4 w-full">
                            <div class="max-w-2xl">
                                @if($carousel->description)
                                <p class="text-primary font-medium mb-4 tracking-wide text-sm md:text-base">
                                    {{ $carousel->description }}
                                </p>
                                @endif
                                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-extrabold text-white leading-tight mb-6">
                                    {{ $carousel->title }}
                                </h1>
                                @if($carousel->button_text)
                                <a href="{{ $carousel->button_link ?? '#' }}" 
                                   class="inline-flex items-center gap-2 px-8 py-4 text-base md:text-lg font-bold text-white bg-accent rounded-xl hover:bg-orange-600 shadow-lg shadow-orange-500/30 transition-all">
                                    {{ $carousel->button_text }}
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            {{-- Navigation --}}
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            {{-- Pagination --}}
            <div class="swiper-pagination"></div>
        </div>
    </section>
    @endif

    {{-- ==================== HERO SECTION ==================== --}}
    <section class="relative bg-gradient-to-b from-teal-50 via-white to-white dark:from-dark dark:via-dark dark:to-dark overflow-hidden grid-pattern">
        {{-- Floating Headshots Left --}}
        <div class="absolute left-0 top-32 hidden xl:block">
            <div class="space-y-4 opacity-90 dark:opacity-50">
                <div class="flex gap-2 ml-8">
                    <div class="w-16 h-16 rounded-xl overflow-hidden shadow-lg border-2 border-white dark:border-dark-border bg-blue-200"><img src="https://i.pravatar.cc/100?img=1" class="w-full h-full object-cover" alt=""></div>
                    <div class="w-12 h-12 rounded-xl overflow-hidden shadow-lg border-2 border-white dark:border-dark-border bg-pink-200 mt-4"><img src="https://i.pravatar.cc/100?img=5" class="w-full h-full object-cover" alt=""></div>
                </div>
                <div class="flex gap-2">
                    <div class="w-14 h-14 rounded-xl overflow-hidden shadow-lg border-2 border-white dark:border-dark-border bg-green-200"><img src="https://i.pravatar.cc/100?img=10" class="w-full h-full object-cover" alt=""></div>
                    <div class="w-20 h-20 rounded-xl overflow-hidden shadow-lg border-2 border-white dark:border-dark-border bg-yellow-200"><img src="https://i.pravatar.cc/100?img=15" class="w-full h-full object-cover" alt=""></div>
                </div>
                <div class="w-16 h-16 rounded-xl overflow-hidden shadow-lg border-2 border-white dark:border-dark-border bg-purple-200 ml-12"><img src="https://i.pravatar.cc/100?img=20" class="w-full h-full object-cover" alt=""></div>
            </div>
        </div>

        {{-- Floating Headshots Right --}}
        <div class="absolute right-0 top-32 hidden xl:block">
            <div class="space-y-4 opacity-90 dark:opacity-50">
                <div class="flex gap-2 justify-end mr-8">
                    <div class="w-12 h-12 rounded-xl overflow-hidden shadow-lg border-2 border-white dark:border-dark-border bg-indigo-200 mt-4"><img src="https://i.pravatar.cc/100?img=25" class="w-full h-full object-cover" alt=""></div>
                    <div class="w-16 h-16 rounded-xl overflow-hidden shadow-lg border-2 border-white dark:border-dark-border bg-red-200"><img src="https://i.pravatar.cc/100?img=30" class="w-full h-full object-cover" alt=""></div>
                </div>
                <div class="flex gap-2 justify-end">
                    <div class="w-20 h-20 rounded-xl overflow-hidden shadow-lg border-2 border-white dark:border-dark-border bg-teal-200"><img src="https://i.pravatar.cc/100?img=35" class="w-full h-full object-cover" alt=""></div>
                    <div class="w-14 h-14 rounded-xl overflow-hidden shadow-lg border-2 border-white dark:border-dark-border bg-orange-200"><img src="https://i.pravatar.cc/100?img=40" class="w-full h-full object-cover" alt=""></div>
                </div>
                <div class="w-16 h-16 rounded-xl overflow-hidden shadow-lg border-2 border-white dark:border-dark-border bg-cyan-200 mr-12"><img src="https://i.pravatar.cc/100?img=45" class="w-full h-full object-cover" alt=""></div>
            </div>
        </div>

        {{-- Hero Content --}}
        <div class="max-w-4xl mx-auto px-4 pt-12 pb-20 lg:pt-20 lg:pb-28 text-center relative z-10">
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-sm font-medium mb-6 border border-green-200 dark:border-green-800">
                <span class="text-lg">✨</span>
                New! We just upgraded our platform.
            </div>

            {{-- Subtitle --}}
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-4">The #1 AI Platform for Professional Business</p>

            {{-- Main Headline --}}
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 dark:text-white leading-tight mb-6">
                Professional business solutions,<br>
                <span class="text-slate-400 dark:text-slate-500">without the complexity.</span>
            </h1>

            {{-- Description --}}
            <p class="text-lg text-slate-500 dark:text-slate-400 mb-8 max-w-2xl mx-auto">
                Get professional business tools in minutes with our AI platform. Upload your data, pick your styles & receive 100+ results.
            </p>

            {{-- CTA Button --}}
            <a href="{{ route('auth.register') }}" class="inline-flex items-center gap-2 px-8 py-4 text-lg font-bold text-white bg-accent rounded-xl hover:bg-orange-600 shadow-lg shadow-orange-500/30 transition-all mb-8">
                Get started now
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>

            {{-- Secondary CTA --}}
            <div class="mb-8">
                <a href="#features" class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium transition-colors">
                    Learn more
                    <span class="block text-xl mt-1">↓</span>
                </a>
            </div>

            {{-- Trust Badges --}}
            <div class="flex flex-wrap items-center justify-center gap-4 mb-8">
                <div class="flex items-center gap-1 bg-green-600 px-2 py-1 rounded">
                    @for($i=0; $i<5; $i++)<svg class="w-4 h-4 text-white" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                </div>
                <span class="text-sm font-medium text-slate-600 dark:text-slate-400">★ Trustpilot</span>
                <div class="flex -space-x-2">
                    <img src="https://i.pravatar.cc/32?img=1" class="w-8 h-8 rounded-full border-2 border-white dark:border-dark" alt="">
                    <img src="https://i.pravatar.cc/32?img=2" class="w-8 h-8 rounded-full border-2 border-white dark:border-dark" alt="">
                    <img src="https://i.pravatar.cc/32?img=3" class="w-8 h-8 rounded-full border-2 border-white dark:border-dark" alt="">
                </div>
                <span class="text-sm text-slate-500 dark:text-slate-400"><span class="font-semibold text-slate-700 dark:text-white">14,228,221</span> results created for <span class="font-semibold text-slate-700 dark:text-white">86,412+</span> happy customers</span>
            </div>

            {{-- Media Logos --}}
            <div class="flex flex-wrap items-center justify-center gap-8 text-slate-400 dark:text-slate-600 text-sm font-semibold">
                <span class="text-xs text-slate-400 dark:text-slate-500">As seen on:</span>
                <span class="text-lg font-black italic">CNN</span>
                <span class="text-lg font-black">MIT TECH REVIEW</span>
                <span class="text-lg font-black italic">VICE</span>
                <span class="text-lg font-black">Bloomberg</span>
                <span class="text-lg font-black italic">FASHIONISTA</span>
            </div>
        </div>

        {{-- 3-Step Process Bar --}}
        <div class="bg-cyan-50 dark:bg-dark-card border-y border-cyan-100 dark:border-dark-border py-6">
            <div class="max-w-4xl mx-auto px-4">
                <div class="flex flex-col md:flex-row items-center justify-center gap-6 md:gap-12">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-100 dark:bg-dark rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <div class="font-bold text-slate-900 dark:text-white">Step 1:</div>
                            <div class="text-sm text-slate-500 dark:text-slate-400">Upload your data</div>
                        </div>
                    </div>
                    <svg class="w-6 h-6 text-slate-300 dark:text-slate-600 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-100 dark:bg-dark rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <div class="font-bold text-slate-900 dark:text-white">Step 2:</div>
                            <div class="text-sm text-slate-500 dark:text-slate-400">Our AI goes to work</div>
                        </div>
                    </div>
                    <svg class="w-6 h-6 text-slate-300 dark:text-slate-600 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-100 dark:bg-dark rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </div>
                        <div>
                            <div class="font-bold text-slate-900 dark:text-white">Step 3:</div>
                            <div class="text-sm text-slate-500 dark:text-slate-400">Download your results</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== ABOUT SECTION ==================== --}}
    <section id="features" class="py-16 bg-white dark:bg-dark-card">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8 items-center">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">All packages include:</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                            <svg class="w-5 h-5 text-green-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Done in 2 hours or less
                        </li>
                        <li class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                            <svg class="w-5 h-5 text-green-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            8x cheaper than traditional
                        </li>
                        <li class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                            <svg class="w-5 h-5 text-green-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Hundreds of results to choose from
                        </li>
                    </ul>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-extrabold text-slate-900 dark:text-white">$29</div>
                    <div class="text-slate-500 dark:text-slate-400 font-medium">2 hours done</div>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Every package includes:</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                            <svg class="w-5 h-5 text-green-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Indistinguishable from real data
                        </li>
                        <li class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                            <svg class="w-5 h-5 text-green-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Business expense-ready invoice
                        </li>
                        <li class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                            <svg class="w-5 h-5 text-green-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Discounts up to 60% for teams
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== NEWS SECTION ==================== --}}
    @if($news->count() > 0)
        <section class="py-20 bg-slate-50 dark:bg-dark">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-12">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary/10 dark:bg-primary/20 text-primary rounded-full text-xs font-semibold mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Latest News
                    </div>
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white mb-4">Stay Updated With Our News</h2>
                    <p class="text-slate-500 dark:text-slate-400 max-w-xl mx-auto">Get the latest updates and insights from our team.</p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($news as $item)
                        <article class="bg-white dark:bg-dark-card rounded-2xl overflow-hidden border border-slate-100 dark:border-dark-border hover:shadow-lg dark:hover:shadow-2xl dark:hover:shadow-primary/5 transition-all group">
                            <div class="relative h-48 overflow-hidden">
                                @if($item->image)
                                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-primary/20 to-accent/20 dark:from-primary/10 dark:to-accent/10 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                    </div>
                                @endif
                                @if($item->category)
                                    <span class="absolute top-4 left-4 bg-primary text-white text-xs px-3 py-1 rounded-full font-semibold">{{ $item->category->name }}</span>
                                @endif
                            </div>
                            <div class="p-6">
                                <div class="flex items-center gap-2 text-sm text-slate-400 dark:text-slate-500 mb-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $item->published_at?->format('d M Y') }}
                                </div>
                                <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-2 group-hover:text-primary transition-colors line-clamp-2">
                                    <a href="{{ route('news.show', $item->slug) }}">{{ $item->title }}</a>
                                </h3>
                                <p class="text-slate-500 dark:text-slate-400 text-sm mb-4 line-clamp-2">{{ Str::limit($item->excerpt ?? strip_tags($item->content), 100) }}</p>
                                <a href="{{ route('news.show', $item->slug) }}" class="inline-flex items-center gap-2 text-primary font-semibold text-sm group-hover:gap-3 transition-all">
                                    Read More
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="text-center mt-12">
                    <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 px-6 py-3 border-2 border-slate-900 dark:border-white text-slate-900 dark:text-white font-semibold rounded-xl hover:bg-slate-900 dark:hover:bg-white hover:text-white dark:hover:text-dark transition-colors">
                        View All News
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ==================== FAQ SECTION ==================== --}}
    <section class="py-20 bg-slate-100 dark:bg-dark relative overflow-hidden">
        {{-- Decorative Arrows --}}
        <div class="absolute left-8 bottom-0 hidden lg:block opacity-60">
            <div class="space-y-3">
                <div class="flex gap-3">
                    <svg class="w-6 h-6 text-teal-400 -rotate-45" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>
                    <svg class="w-4 h-4 text-teal-300 -rotate-45 mt-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>
                </div>
                <div class="flex gap-4 ml-4">
                    <svg class="w-5 h-5 text-teal-400 -rotate-45" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>
                    <svg class="w-7 h-7 text-teal-300 -rotate-45" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>
                </div>
                <div class="flex gap-2 ml-8">
                    <svg class="w-4 h-4 text-teal-400 -rotate-45" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>
                    <svg class="w-6 h-6 text-teal-300 -rotate-45" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>
                </div>
                <div class="flex gap-3 ml-2">
                    <svg class="w-5 h-5 text-teal-400 -rotate-45" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>
                    <svg class="w-4 h-4 text-teal-300 -rotate-45" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-start">
                {{-- Left Side: Title --}}
                <div class="md:sticky md:top-24">
                    <h2 class="text-4xl lg:text-5xl font-extrabold text-slate-900 dark:text-white leading-tight mb-6">
                        Have Any<br>Questions?
                    </h2>
                    <p class="text-slate-500 dark:text-slate-400 max-w-sm">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed facilisis eleifend quam, non efficitur nisi mattis quis.
                    </p>
                </div>

                {{-- Right Side: FAQ Accordion --}}
                <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg dark:shadow-xl dark:shadow-black/20 p-6 md:p-8" x-data="{ activeIndex: null }">
                    @php
                        $faqs = [
                            [
                                'question' => 'How can I set up a FiFaster account?',
                                'answer' => 'Getting started is easy! Simply click the "Get Started" button on our homepage, fill in your details, and verify your email. Your account will be ready in minutes.'
                            ],
                            [
                                'question' => 'How do I schedule a delivery?',
                                'answer' => 'Once logged in, navigate to the Dashboard and click "Schedule Delivery". Select your preferred date, time, and location. You can also set recurring deliveries for convenience.'
                            ],
                            [
                                'question' => 'What are your hours of operation?',
                                'answer' => 'Our customer service team is available Monday through Friday, 9 AM to 6 PM (local time). However, our platform is accessible 24/7 for self-service options.'
                            ],
                            [
                                'question' => 'Do you operate 24/7?',
                                'answer' => 'Yes! Our platform operates around the clock. While live support has specific hours, you can access all features, submit requests, and track orders at any time.'
                            ],
                            [
                                'question' => 'What equipment do you have?',
                                'answer' => 'We maintain a diverse fleet including refrigerated trucks, standard delivery vans, and specialized vehicles for fragile items. All equipment is regularly maintained and GPS-tracked.'
                            ],
                            [
                                'question' => 'Can I track my orders?',
                                'answer' => 'Absolutely! Real-time tracking is available for all orders. You\'ll receive updates via email and SMS, plus you can monitor your delivery through our mobile app or website.'
                            ],
                        ];
                    @endphp

                    <div class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach($faqs as $index => $faq)
                            <div class="py-4 first:pt-0 last:pb-0">
                                {{-- Question Header --}}
                                <button 
                                    @click="activeIndex = activeIndex === {{ $index }} ? null : {{ $index }}"
                                    class="flex items-center justify-between w-full text-left gap-4 group"
                                >
                                    <div class="flex items-center gap-4">
                                        {{-- Question Icon --}}
                                        <div 
                                            class="shrink-0 w-8 h-8 rounded-full flex items-center justify-center border-2 font-bold text-sm transition-all duration-200"
                                            :class="activeIndex === {{ $index }} 
                                                ? 'bg-primary border-primary text-white' 
                                                : 'bg-slate-100 dark:bg-dark border-slate-700 dark:border-slate-400 text-slate-700 dark:text-slate-400 group-hover:border-primary group-hover:text-primary'"
                                        >
                                            ?
                                        </div>
                                        {{-- Question Text --}}
                                        <span 
                                            class="font-medium transition-colors duration-200"
                                            :class="activeIndex === {{ $index }} 
                                                ? 'text-primary' 
                                                : 'text-slate-800 dark:text-slate-200 group-hover:text-primary'"
                                        >
                                            {{ $faq['question'] }}
                                        </span>
                                    </div>
                                    {{-- Chevron Icon --}}
                                    <svg 
                                        class="w-5 h-5 shrink-0 text-slate-400 transition-transform duration-300"
                                        :class="{ 'rotate-180': activeIndex === {{ $index }} }"
                                        fill="none" 
                                        stroke="currentColor" 
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                {{-- Answer Content --}}
                                <div 
                                    x-show="activeIndex === {{ $index }}"
                                    x-collapse
                                    x-cloak
                                >
                                    <div class="pt-4 pl-12 text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                                        {{ $faq['answer'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== CTA SECTION ==================== --}}
    <section class="py-20 lg:py-32 bg-slate-900 dark:bg-dark-card">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-4xl lg:text-5xl font-extrabold text-white mb-6">Get your professional results today</h2>
            <p class="text-xl text-slate-400 mb-10">Join 86,000+ professionals who upgraded their workflow.</p>
            <a href="{{ route('auth.register') }}" class="inline-flex items-center gap-2 px-10 py-5 text-xl font-bold text-white bg-accent rounded-xl hover:bg-orange-600 shadow-lg shadow-orange-500/30 transition-all">
                Get started for $29 →
            </a>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Hero Carousel
        new Swiper('.heroCarousel', {
            loop: true,
            autoplay: { 
                delay: 5000,
                disableOnInteraction: false,
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            pagination: { 
                el: '.swiper-pagination', 
                clickable: true,
                bulletActiveClass: 'swiper-pagination-bullet-active',
            },
            navigation: { 
                nextEl: '.swiper-button-next', 
                prevEl: '.swiper-button-prev' 
            },
            speed: 800,
        });
    </script>
@endpush