{{-- Footer - Dark Mode Support --}}
<footer class="bg-slate-900 dark:bg-dark text-white pt-16 pb-8 dark:border-t dark:border-dark-border">
    <div class="max-w-7xl mx-auto px-4">
        {{-- Top Section --}}
        <div class="mb-12">
            <div class="flex items-center gap-2 mb-4">
                @if($aboutUs?->logo)
                    <img src="{{ Storage::url($aboutUs->logo) }}" alt="{{ $aboutUs->company_name }}"
                        class="h-8 brightness-0 invert">
                @else
                    <div class="w-6 h-6 bg-white dark:bg-primary rounded flex items-center justify-center">
                        <svg class="w-4 h-4 text-slate-900 dark:text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                        </svg>
                    </div>
                @endif
                <span class="font-bold text-lg">{{ $aboutUs->company_name ?? config('app.name') }}</span>
            </div>
            <p class="text-slate-400 text-sm mb-4 max-w-md">
                {{ Str::limit(strip_tags($aboutUs->description ?? 'Professional business platform for modern companies.'), 150) }}
            </p>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-1 bg-green-600 px-2 py-1 rounded">
                    @for($i = 0; $i < 5; $i++)<svg class="w-3 h-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>@endfor
                </div>
                <span class="text-sm text-slate-400">★ Trustpilot</span>
                <span class="text-sm text-slate-400">Trusted by <span class="text-white font-medium">86,412+</span>
                    happy customers</span>
            </div>
        </div>

        {{-- Links Grid --}}
        <div
            class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8 py-8 border-t border-slate-800 dark:border-dark-border">
            <div>
                <h4 class="font-semibold text-sm mb-4 text-slate-300">Links</h4>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><a href="/" class="hover:text-white transition-colors">Home</a></li>
                    <li><a href="{{ route('news.index') }}" class="hover:text-white transition-colors">News</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">About Us</a></li>
                    <li><a href="{{ route('about') }}#contact" class="hover:text-white transition-colors">Contact</a>
                    </li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-sm mb-4 text-slate-300">Support</h4>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">FAQs</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Feedback</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-sm mb-4 text-slate-300">Legal</h4>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><a href="#" class="hover:text-white transition-colors">Terms & Conditions</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Refund Policy</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-sm mb-4 text-slate-300">Contact</h4>
                <ul class="space-y-2 text-sm text-slate-400">
                    @if($aboutUs?->phone)
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            {{ $aboutUs->phone }}
                        </li>
                    @endif
                    @if($aboutUs?->email)
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $aboutUs->email }}
                        </li>
                    @endif
                </ul>
            </div>
            <div class="col-span-2 lg:col-span-2">
                <h4 class="font-semibold text-sm mb-4 text-slate-300">Follow Us</h4>
                <div class="flex gap-3">
                    @if($aboutUs?->facebook)
                        <a href="{{ $aboutUs->facebook }}" target="_blank"
                            class="w-9 h-9 bg-slate-800 dark:bg-dark-card hover:bg-primary rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                    @endif
                    @if($aboutUs?->instagram)
                        <a href="{{ $aboutUs->instagram }}" target="_blank"
                            class="w-9 h-9 bg-slate-800 dark:bg-dark-card hover:bg-pink-500 rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                            </svg>
                        </a>
                    @endif
                    @if($aboutUs?->youtube)
                        <a href="{{ $aboutUs->youtube }}" target="_blank"
                            class="w-9 h-9 bg-slate-800 dark:bg-dark-card hover:bg-red-600 rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
                        </a>
                    @endif
                    @if($aboutUs?->twitter)
                        <a href="{{ $aboutUs->twitter }}" target="_blank"
                            class="w-9 h-9 bg-slate-800 dark:bg-dark-card hover:bg-sky-500 rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                    @endif
                    @if($aboutUs?->linkedin)
                        <a href="{{ $aboutUs->linkedin }}" target="_blank"
                            class="w-9 h-9 bg-slate-800 dark:bg-dark-card hover:bg-blue-600 rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div
            class="border-t border-slate-800 dark:border-dark-border pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-slate-500 text-sm">
            <div class="flex items-center gap-4">
                <a href="{{ route('about') }}" class="hover:text-white transition-colors">About us</a>
                <a href="{{ route('news.index') }}" class="hover:text-white transition-colors">Blog</a>
                <a href="#" class="hover:text-white transition-colors">Careers</a>
            </div>
            <p>© 2021 - {{ date('Y') }} {{ $aboutUs->company_name ?? config('app.name') }}. All Rights Reserved</p>
        </div>
    </div>
</footer>