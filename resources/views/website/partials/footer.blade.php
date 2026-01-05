<!-- Footer -->
<footer class="bg-dark border-t border-white/10 text-gray-400 py-12">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-4 gap-8 mb-8">
            <!-- About -->
            <div class="md:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    @if($aboutUs?->logo)
                        <img src="{{ Storage::url($aboutUs->logo) }}" alt="{{ $aboutUs->company_name }}" class="h-10">
                    @else
                        <div class="w-10 h-10 bg-primary rounded flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 12h3v8h6v-6h2v6h6v-8h3L12 2z" />
                            </svg>
                        </div>
                    @endif
                    <span class="text-xl font-bold text-white">{{ $aboutUs->company_name ?? 'COMPANY' }}</span>
                </div>
                <p class="text-sm mb-4">{{ Str::limit(strip_tags($aboutUs->description ?? ''), 150) }}</p>
                <div class="flex gap-3">
                    @if($aboutUs?->facebook)<a href="{{ $aboutUs->facebook }}" target="_blank"
                        class="w-9 h-9 bg-white/10 rounded-full flex items-center justify-center hover:bg-primary transition-colors"><svg
                            class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                    </svg></a>@endif
                    @if($aboutUs?->instagram)<a href="{{ $aboutUs->instagram }}" target="_blank"
                        class="w-9 h-9 bg-white/10 rounded-full flex items-center justify-center hover:bg-primary transition-colors"><svg
                            class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                    </svg></a>@endif
                    @if($aboutUs?->youtube)<a href="{{ $aboutUs->youtube }}" target="_blank"
                        class="w-9 h-9 bg-white/10 rounded-full flex items-center justify-center hover:bg-primary transition-colors"><svg
                            class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                    </svg></a>@endif
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-bold mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/" class="hover:text-primary transition-colors">Home</a></li>
                    <li><a href="{{ route('news.index') }}" class="hover:text-primary transition-colors">News</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-primary transition-colors">About Us</a></li>
                    <li><a href="{{ route('about') }}#contact" class="hover:text-primary transition-colors">Contact</a>
                    </li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="text-white font-bold mb-4">Contact Us</h4>
                <ul class="space-y-2 text-sm">
                    @if($aboutUs?->phone)
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-primary" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>{{ $aboutUs->phone }}</li>
                    @endif
                    @if($aboutUs?->email)
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-primary" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>{{ $aboutUs->email }}</li>
                    @endif
                    @if($aboutUs?->address)
                        <li class="flex items-start gap-2"><svg class="w-4 h-4 text-primary mt-0.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>{{ Str::limit($aboutUs->address, 50) }}</li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10 pt-8 text-center text-sm">
            <p>&copy; {{ date('Y') }} {{ $aboutUs->company_name ?? config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</footer>