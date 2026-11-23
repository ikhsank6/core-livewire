<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    @include('partials.fouc-prevention')
    @include('partials.meta-base')

    <title>{{ $title ?? $aboutUs->company_name ?? config('app.name', 'Laravel') }}</title>

    @include('partials.favicon')

    {{-- Same pairing as the public portal: Bricolage Grotesque (display) + Plus Jakarta Sans (body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wdth,wght@12..96,75..100,300..800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @include('partials.theme-scripts')
    @include('partials.alpine-cloak')

    @filamentStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: "Plus Jakarta Sans", ui-sans-serif, system-ui, sans-serif; }
        h1, h2, h3, h4, .auth-display {
            font-family: "Bricolage Grotesque", ui-sans-serif, system-ui, sans-serif;
            font-variation-settings: "wdth" 92;
            letter-spacing: -0.02em;
        }

        /* Kawung (batik) motif: faint base layer plus a bright copy revealed around the pointer */
        .auth-kawung, .auth-kawung-lit {
            background-size: 72px 72px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='72' height='72' viewBox='0 0 72 72'%3E%3Cg fill='none' stroke='%23FFB81C' stroke-width='1' stroke-opacity='.13'%3E%3Ccircle cx='0' cy='0' r='36'/%3E%3Ccircle cx='72' cy='0' r='36'/%3E%3Ccircle cx='0' cy='72' r='36'/%3E%3Ccircle cx='72' cy='72' r='36'/%3E%3Ccircle cx='36' cy='36' r='36'/%3E%3C/g%3E%3C/svg%3E");
        }
        .auth-kawung-lit {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='72' height='72' viewBox='0 0 72 72'%3E%3Cg fill='none' stroke='%23FFB81C' stroke-width='1.2' stroke-opacity='.75'%3E%3Ccircle cx='0' cy='0' r='36'/%3E%3Ccircle cx='72' cy='0' r='36'/%3E%3Ccircle cx='0' cy='72' r='36'/%3E%3Ccircle cx='72' cy='72' r='36'/%3E%3Ccircle cx='36' cy='36' r='36'/%3E%3C/g%3E%3C/svg%3E");
            -webkit-mask-image: radial-gradient(circle 20rem at var(--mx, 62%) var(--my, 58%), #000 0%, transparent 72%);
            mask-image: radial-gradient(circle 20rem at var(--mx, 62%) var(--my, 58%), #000 0%, transparent 72%);
        }

        /* The door draws itself once */
        .auth-door path { stroke-dasharray: 1; stroke-dashoffset: 1; animation: auth-draw 1.4s cubic-bezier(.22,.8,.2,1) forwards; }
        .auth-door path:nth-child(2) { animation-delay: .15s; }
        .auth-door path:nth-child(3) { animation-delay: .3s; }
        @keyframes auth-draw { to { stroke-dashoffset: 0; } }

        .auth-statement { display: none; }
        @media (min-width: 1280px) and (min-height: 720px) { .auth-statement { display: block; } }

        @media (prefers-reduced-motion: reduce) {
            .auth-door path { animation: none; stroke-dashoffset: 0; }
        }

        /* Visible keyboard focus everywhere inside the auth screen */
        .auth-shell a:focus-visible,
        .auth-shell button:focus-visible,
        .auth-shell input:focus-visible { outline: 2px solid #1A3E9A; outline-offset: 2px; }
        .dark .auth-shell a:focus-visible,
        .dark .auth-shell button:focus-visible,
        .dark .auth-shell input:focus-visible { outline-color: #8DB4FF; }
        .auth-dark a:focus-visible, .auth-dark button:focus-visible { outline-color: #FFB81C !important; }
    </style>
</head>

<body class="auth-shell min-h-full antialiased bg-[#0B1B4D] text-[#3F485C] dark:text-[#C3CBE0]">

    {{-- One full-screen ground (the door and the kawung motif); the form is a card resting on it. --}}
    <div class="relative isolate flex min-h-screen flex-col overflow-hidden bg-[#0B1B4D]"
         x-data="{
            reduce: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
            light(e) {
                if (this.reduce) return;
                const r = this.$el.getBoundingClientRect();
                this.$refs.lit.style.setProperty('--mx', (e.clientX - r.left) + 'px');
                this.$refs.lit.style.setProperty('--my', (e.clientY - r.top) + 'px');
            }
         }"
         @pointermove="light($event)">

        <div class="auth-kawung absolute inset-0 -z-10" aria-hidden="true"></div>
        <div x-ref="lit" class="auth-kawung-lit absolute inset-0 -z-10" aria-hidden="true"></div>

        {{-- Illustration (original, drawn for this page): a citizen stands in front of the door,
             holding a document folder and pointing at the sign-in card. Decorative. --}}
        <svg class="pointer-events-none absolute bottom-0 left-0 -z-10 hidden h-[84%] w-1/2 lg:block xl:w-[58%]"
             viewBox="0 0 720 760" preserveAspectRatio="xMidYMax meet" fill="none" aria-hidden="true">

            <defs>
                <linearGradient id="door-light" x1="0" y1="1" x2="0" y2="0">
                    <stop offset="0" stop-color="#FFB81C" stop-opacity=".42"/>
                    <stop offset="1" stop-color="#FFB81C" stop-opacity="0"/>
                </linearGradient>
            </defs>

            {{-- The door: three nested arches that draw themselves once --}}
            <g class="auth-door" stroke="#FFB81C" stroke-width="3" stroke-opacity=".8">
                <path pathLength="1" d="M70 700V290A140 140 0 0 1 350 290V700"/>
                <path pathLength="1" d="M110 700V292A100 100 0 0 1 310 292V700"/>
                <path pathLength="1" d="M150 700V294A60 60 0 0 1 270 294V700" fill="url(#door-light)" stroke-opacity=".8"/>
            </g>

            {{-- Ground shadow --}}
            <ellipse cx="500" cy="704" rx="150" ry="11" fill="#000" fill-opacity=".28"/>

            <g transform="translate(80 0)">
                {{-- Legs and shoes --}}
                <rect x="398" y="520" width="28" height="172" rx="12" fill="#2F5BD0"/>
                <rect x="430" y="520" width="28" height="172" rx="12" fill="#2A52C0"/>
                <ellipse cx="410" cy="696" rx="27" ry="10" fill="#0E1424"/>
                <ellipse cx="448" cy="696" rx="29" ry="10" fill="#0E1424"/>

                {{-- Torso --}}
                <rect x="376" y="268" width="92" height="276" rx="30" fill="#F5F6F3"/>
                <path d="M376 520h92v24H376z" fill="#2F5BD0"/>

                {{-- Arm holding the folder --}}
                <path d="M390 306 L380 374" stroke="#F5F6F3" stroke-width="26" stroke-linecap="round"/>
                <path d="M380 374 L402 410" stroke="#D9A074" stroke-width="20" stroke-linecap="round"/>
                <g transform="rotate(-7 430 440)">
                    <rect x="398" y="398" width="64" height="86" rx="6" fill="#FFB81C"/>
                    <path d="M398 410h28l6-12h-34z" fill="#E6A20E"/>
                    <rect x="410" y="428" width="40" height="5" rx="2.5" fill="#0B1B4D" fill-opacity=".55"/>
                    <rect x="410" y="442" width="40" height="5" rx="2.5" fill="#0B1B4D" fill-opacity=".55"/>
                    <rect x="410" y="456" width="26" height="5" rx="2.5" fill="#0B1B4D" fill-opacity=".55"/>
                </g>
                <circle cx="406" cy="414" r="11" fill="#D9A074"/>

                {{-- Neck and head --}}
                <rect x="410" y="238" width="24" height="36" rx="10" fill="#D9A074"/>
                <circle cx="424" cy="204" r="38" fill="#E0A97A"/>
                <path d="M386 200A38 38 0 0 1 462 200C452 184 434 178 418 182C404 186 394 192 386 200Z" fill="#15151E"/>
                <circle cx="444" cy="208" r="2.8" fill="#15151E"/>
                <path d="M438 224q7 6 14 0" stroke="#8A4E2E" stroke-width="2.4" stroke-linecap="round"/>

                {{-- Pointing arm, reaching toward the card --}}
                <path d="M456 304 L516 322" stroke="#F5F6F3" stroke-width="26" stroke-linecap="round"/>
                <path d="M516 322 L580 296" stroke="#D9A074" stroke-width="20" stroke-linecap="round"/>
                <circle cx="590" cy="292" r="12" fill="#D9A074"/>
                <path d="M596 288 L630 278" stroke="#D9A074" stroke-width="8" stroke-linecap="round"/>
            </g>
        </svg>

        {{-- Top bar: brand and theme --}}
        <header class="auth-dark flex items-center justify-between px-6 py-5 text-white sm:px-10 lg:px-12">
            <a href="/" class="group inline-flex min-h-11 items-center gap-3 rounded-md" aria-label="Kembali ke beranda">
                @if($aboutUs?->logo)
                    <img src="{{ Storage::url($aboutUs->logo) }}" alt="" class="h-10 w-auto">
                @else
                    <svg class="h-10 w-10 shrink-0" viewBox="0 0 36 36" fill="none" aria-hidden="true">
                        <path d="M5 33V16C5 8.82 10.82 3 18 3s13 5.82 13 13v17" stroke="#FFB81C" stroke-width="2.4" stroke-linecap="round"/>
                        <path d="M2.5 33h31" stroke="#FFB81C" stroke-width="2.4" stroke-linecap="round"/>
                        <path d="M18 8c4.4 0 8 3.6 8 8v17H10V16c0-4.4 3.6-8 8-8Z" fill="#FFB81C"
                              style="transform-origin: 10px 33px; transition: transform 380ms cubic-bezier(.3,1.2,.4,1);"
                              class="group-hover:[transform:scaleX(0.52)] group-focus-visible:[transform:scaleX(0.52)]"/>
                    </svg>
                @endif
                <span class="auth-display text-xl font-bold">{{ $aboutUs?->company_name ?? config('app.name') }}</span>
            </a>

            <div x-data="{
                get isDark() { return $store.theme ? $store.theme.isDark : (localStorage.getItem('theme') === 'dark') },
                toggle() {
                    if ($store.theme) { $store.theme.toggle(); }
                    else {
                        const t = this.isDark ? 'light' : 'dark';
                        localStorage.setItem('theme', t);
                        document.documentElement.classList.toggle('dark', t === 'dark');
                    }
                }
            }">
                <button type="button" @click="toggle()"
                    :aria-label="isDark ? 'Aktifkan mode terang' : 'Aktifkan mode gelap'"
                    class="grid h-11 w-11 cursor-pointer place-items-center rounded-md text-white/80 transition-colors hover:bg-white/10 hover:text-white">
                    <svg x-show="isDark" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" style="stroke: currentColor !important;" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg x-show="!isDark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" style="stroke: currentColor !important;" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
            </div>
        </header>

        {{-- The card --}}
        <main class="flex flex-1 items-center justify-center px-4 py-8 sm:px-6 lg:justify-end lg:pr-14 xl:pr-24">
            <div class="w-full max-w-[28rem] rounded-2xl border border-[#DADED8] bg-white p-7 shadow-[0_30px_70px_-24px_rgba(0,0,0,0.55)] dark:border-[#223056] dark:bg-[#111B38] sm:p-9">
                {{ $slot }}

                <div class="mt-7 border-t border-[#DADED8] pt-3 dark:border-[#223056]">
                    <a href="/" class="inline-flex min-h-11 items-center text-sm font-semibold text-[#1A3E9A] underline decoration-transparent underline-offset-4 transition-colors hover:decoration-current dark:text-[#8DB4FF]">
                        Kembali ke beranda
                    </a>
                </div>
            </div>
        </main>

        {{-- Quiet statement under the logo, only where there is room for it --}}
        <p class="auth-statement auth-display auth-dark absolute left-12 top-24 max-w-[19rem] text-[1.5rem] font-bold leading-tight text-white" style="text-wrap: balance;">
            Satu akun untuk semua urusan Anda.
        </p>
    </div>

    @include('partials.toast-notifications')

    @fluxScripts
    @filamentScripts
</body>

</html>
