@extends('layouts.website')

@section('title', ($aboutUs->company_name ?? config('app.name')) . ' - Home')

@section('swiper', true)

@section('content')
    <!-- Hero Carousel -->
    <section class="relative">
        <div class="swiper heroSwiper">
            <div class="swiper-wrapper">
                @forelse($carousels as $carousel)
                    <div class="swiper-slide hero-slide h-[400px] md:h-[500px] lg:h-[600px] relative"
                        style="background-image: linear-gradient(to right, rgba(26,26,46,0.95) 50%, rgba(26,26,46,0.7)), url('{{ $carousel->image ? Storage::url($carousel->image) : 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1920' }}')">
                        <div class="container mx-auto px-4 h-full flex items-center">
                            <div class="max-w-xl text-white">
                                <p class="text-primary font-semibold mb-2 md:mb-4 tracking-widest uppercase text-xs md:text-sm">
                                    {{ $carousel->description ?? 'YOUR DREAM COME TRUE' }}
                                </p>
                                <h1
                                    class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4 md:mb-6 italic">
                                    {{ $carousel->title }}</h1>
                                @if($carousel->button_text)
                                    <a href="{{ $carousel->button_link ?? '#' }}"
                                        class="inline-block bg-primary hover:bg-red-600 text-white px-6 md:px-8 py-3 md:py-4 font-semibold tracking-wider transition-all text-sm md:text-base">{{ $carousel->button_text }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide hero-slide h-[400px] md:h-[500px] lg:h-[600px] relative"
                        style="background-image: linear-gradient(to right, rgba(26,26,46,0.95) 50%, rgba(26,26,46,0.7)), url('https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1920')">
                        <div class="container mx-auto px-4 h-full flex items-center">
                            <div class="max-w-xl text-white">
                                <p class="text-primary font-semibold mb-2 md:mb-4 tracking-widest text-xs md:text-sm">YOUR DREAM
                                    COME TRUE</p>
                                <h1
                                    class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4 md:mb-6 italic">
                                    We're Creating Foundations for Your Dreams</h1>
                                <a href="#"
                                    class="inline-block bg-primary hover:bg-red-600 text-white px-6 md:px-8 py-3 md:py-4 font-semibold tracking-wider transition-all text-sm md:text-base">LET'S
                                    GET STARTED</a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-12 md:py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-start">
                <div>
                    <h2 class="text-2xl md:text-4xl font-bold text-dark mb-4 md:mb-6">
                        {{ $aboutUs->company_name ?? 'About Us' }}</h2>
                    <p class="text-gray-600 mb-6 md:mb-8 text-sm md:text-base">
                        {!! Str::limit(strip_tags($aboutUs->description ?? 'Welcome to our company. We provide excellent services.'), 300) !!}
                    </p>
                    <a href="{{ route('about') }}"
                        class="inline-flex items-center gap-2 text-primary font-semibold hover:gap-3 transition-all">
                        Learn More About Us
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-3 md:gap-6">
                    @php
                        $features = [
                            ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'title' => 'Product Research'],
                            ['icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'title' => 'Business Idea'],
                            ['icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z', 'title' => 'Great Solutions'],
                            ['icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'title' => 'Quality Projects'],
                        ];
                    @endphp
                    @foreach($features as $feature)
                        <div class="bg-white p-4 md:p-6 rounded-lg shadow-sm border hover:shadow-lg transition-all text-center">
                            <div
                                class="w-12 h-12 md:w-16 md:h-16 bg-orange-50 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-4">
                                <svg class="w-6 h-6 md:w-8 md:h-8 text-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $feature['icon'] }}" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-dark mb-1 md:mb-2 text-sm md:text-base">{{ $feature['title'] }}</h3>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- News Section -->
    @if($news->count() > 0)
        <section class="py-12 md:py-20 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-8 md:mb-12">
                    <p class="text-primary font-semibold mb-2 flex items-center justify-center gap-2 text-sm">
                        <span class="w-2 h-2 md:w-3 md:h-3 bg-primary"></span> LATEST NEWS
                    </p>
                    <h2 class="text-2xl md:text-4xl font-bold text-dark">Stay Updated With Our News</h2>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    @foreach($news as $item)
                        <article class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all group">
                            <div class="relative h-48 overflow-hidden">
                                @if($item->image)
                                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                        </svg>
                                    </div>
                                @endif
                                @if($item->category)
                                    <span
                                        class="absolute top-4 left-4 bg-primary text-white text-xs px-3 py-1 rounded font-semibold">{{ $item->category->name }}</span>
                                @endif
                            </div>
                            <div class="p-6">
                                <div class="flex items-center gap-4 text-sm text-gray-500 mb-3">
                                    <span>{{ $item->published_at?->format('d M Y') }}</span>
                                </div>
                                <h3 class="font-bold text-dark text-lg mb-2 group-hover:text-primary transition-colors">
                                    <a href="{{ route('news.show', $item->slug) }}">{{ $item->title }}</a>
                                </h3>
                                <p class="text-gray-600 text-sm mb-4">
                                    {{ Str::limit($item->excerpt ?? strip_tags($item->content), 100) }}</p>
                                <a href="{{ route('news.show', $item->slug) }}"
                                    class="text-primary font-semibold text-sm flex items-center gap-2 hover:gap-4 transition-all">
                                    Read More
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="text-center mt-8">
                    <a href="{{ route('news.index') }}"
                        class="inline-flex items-center gap-2 bg-primary hover:bg-red-600 text-white px-6 py-3 font-semibold rounded-lg transition-all">
                        View All News
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- Contact Section -->
    <section id="contact" class="py-12 md:py-20 bg-dark text-white">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-8 md:gap-12">
                <div>
                    <p class="text-primary font-semibold mb-2 text-sm">GET IN TOUCH</p>
                    <h2 class="text-2xl md:text-4xl font-bold mb-4 md:mb-6">Contact Us</h2>
                    <p class="text-gray-400 mb-6 md:mb-8 text-sm md:text-base">Feel free to reach out to us for any
                        inquiries or questions.</p>
                    @include('website.partials.contact-info')
                </div>
                <div class="bg-white/5 rounded-2xl p-6 md:p-8">
                    @include('website.partials.contact-form')
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        new Swiper('.heroSwiper', {
            loop: true,
            autoplay: { delay: 5000 },
            pagination: { el: '.swiper-pagination', clickable: true },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        });
    </script>
@endpush