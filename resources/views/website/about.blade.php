@extends('layouts.website')

@section('title', 'About Us - ' . ($aboutUs->company_name ?? config('app.name')))

@section('content')
    {{-- Page Header --}}
    @include('website.partials.page-header', ['title' => 'About Us', 'breadcrumb' => 'About Us'])

    {{-- About Content --}}
    <section class="py-12 md:py-20 bg-white dark:bg-dark">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                {{-- Image --}}
                <div class="relative">
                    @if($aboutUs?->logo)
                        <div
                            class="bg-slate-100 dark:bg-dark-card rounded-2xl p-8 flex items-center justify-center border border-transparent dark:border-dark-border">
                            <img src="{{ Storage::url($aboutUs->logo) }}" alt="{{ $aboutUs->company_name }}"
                                class="max-w-full max-h-80 dark:brightness-0 dark:invert">
                        </div>
                    @else
                        <div
                            class="w-full h-[400px] bg-slate-200 dark:bg-dark-card rounded-xl flex items-center justify-center border border-transparent dark:border-dark-border">
                            <div class="text-center">
                                <div class="w-24 h-24 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2L2 12h3v8h6v-6h2v6h6v-8h3L12 2z" />
                                    </svg>
                                </div>
                                <p class="text-2xl font-bold text-slate-900 dark:text-white">
                                    {{ $aboutUs->company_name ?? 'Company' }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Content --}}
                <div>
                    <p class="text-primary font-semibold mb-2 text-sm tracking-widest">ABOUT COMPANY</p>
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-6">
                        {{ $aboutUs->company_name ?? 'About Us' }}</h2>
                    <div class="prose dark:prose-invert text-slate-600 dark:text-slate-300 mb-8 max-w-none">
                        {!! $aboutUs->description ?? '<p>Welcome to our company. We are dedicated to providing the best services and solutions for our clients.</p>' !!}
                    </div>

                    {{-- Stats --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div
                            class="text-center p-4 bg-slate-50 dark:bg-dark-card rounded-lg border border-transparent dark:border-dark-border">
                            <p class="text-3xl font-bold text-primary">10+</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Years Experience</p>
                        </div>
                        <div
                            class="text-center p-4 bg-slate-50 dark:bg-dark-card rounded-lg border border-transparent dark:border-dark-border">
                            <p class="text-3xl font-bold text-primary">500+</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Happy Clients</p>
                        </div>
                        <div
                            class="text-center p-4 bg-slate-50 dark:bg-dark-card rounded-lg border border-transparent dark:border-dark-border">
                            <p class="text-3xl font-bold text-primary">100+</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Projects Done</p>
                        </div>
                        <div
                            class="text-center p-4 bg-slate-50 dark:bg-dark-card rounded-lg border border-transparent dark:border-dark-border">
                            <p class="text-3xl font-bold text-primary">50+</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Team Members</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact Info Section --}}
    <section id="contact" class="py-12 md:py-20 bg-slate-900 dark:bg-dark-card text-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <p class="text-primary font-semibold mb-2 text-sm tracking-widest">CONTACT US</p>
                <h2 class="text-3xl md:text-4xl font-bold">Just Say Hello.</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @if($aboutUs?->phone)
                    <div
                        class="bg-white/5 dark:bg-dark rounded-xl p-6 text-center hover:bg-white/10 dark:hover:bg-dark-border transition-colors border border-transparent dark:border-dark-border">
                        <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-primary mb-2">Call Now</h4>
                        <p class="text-slate-300">{{ $aboutUs->phone }}</p>
                    </div>
                @endif

                @if($aboutUs?->email)
                    <div
                        class="bg-white/5 dark:bg-dark rounded-xl p-6 text-center hover:bg-white/10 dark:hover:bg-dark-border transition-colors border border-transparent dark:border-dark-border">
                        <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-primary mb-2">Email</h4>
                        <p class="text-slate-300">{{ $aboutUs->email }}</p>
                    </div>
                @endif

                @if($aboutUs?->address)
                    <div
                        class="bg-white/5 dark:bg-dark rounded-xl p-6 text-center hover:bg-white/10 dark:hover:bg-dark-border transition-colors border border-transparent dark:border-dark-border">
                        <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-primary mb-2">Address</h4>
                        <p class="text-slate-300 text-sm">{{ $aboutUs->address }}</p>
                    </div>
                @endif

                @if($aboutUs?->whatsapp)
                    <div
                        class="bg-white/5 dark:bg-dark rounded-xl p-6 text-center hover:bg-white/10 dark:hover:bg-dark-border transition-colors border border-transparent dark:border-dark-border">
                        <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-primary mb-2">WhatsApp</h4>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $aboutUs->whatsapp) }}" target="_blank"
                            class="text-slate-300 hover:text-primary transition-colors">Chat with us</a>
                    </div>
                @endif
            </div>

            {{-- Social Media --}}
            @include('website.partials.social-links')
        </div>
    </section>

    {{-- Map Section --}}
    @if($aboutUs?->map_embed)
        <section class="h-[400px] grayscale dark:opacity-75">
            {!! $aboutUs->map_embed !!}
        </section>
    @endif
@endsection