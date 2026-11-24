<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    @include('partials.fouc-prevention')
    @include('partials.meta-base')

    <title>{{ $title ?? $aboutUs->company_name ?? config('app.name', 'Laravel') }}</title>

    @include('partials.favicon')

    {{-- Font: Inter, same as the admin panel --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @include('partials.theme-scripts')
    @include('partials.alpine-cloak')

    @filamentStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: "Inter", ui-sans-serif, system-ui, sans-serif; }
        h1, h2, h3, h4, .auth-display {
            font-family: "Plus Jakarta Sans", ui-sans-serif, system-ui, sans-serif;
            font-weight: 800;
            letter-spacing: -0.035em;
        }

        /* Guilloche rosette: the security print of a certificate, used as a mask so it takes any colour.
           It turns a few degrees with the pointer. */
        .auth-rosette {
            position: absolute;
            aspect-ratio: 1;
            background-color: currentColor;
            -webkit-mask: url("/images/guilloche.svg") center / contain no-repeat;
            mask: url("/images/guilloche.svg") center / contain no-repeat;
            transform: rotate(var(--rot, 0deg));
            transition: transform 1100ms cubic-bezier(.2, .8, .2, 1);
            pointer-events: none;
        }

        /* The framed certificate and the clipboard draw themselves once */
        .auth-draw path, .auth-draw rect.auth-stroke { stroke-dasharray: 1; stroke-dashoffset: 1; animation: auth-draw 1.5s cubic-bezier(.22,.8,.2,1) forwards; }
        .auth-draw path:nth-child(2) { animation-delay: .15s; }
        .auth-draw path:nth-child(3) { animation-delay: .3s; }
        @keyframes auth-draw { to { stroke-dashoffset: 0; } }

        .auth-statement { display: none; }
        @media (min-width: 1280px) and (min-height: 720px) { .auth-statement { display: block; } }

        @media (prefers-reduced-motion: reduce) {
            .auth-rosette { transition: none; }
            .auth-draw path, .auth-draw rect.auth-stroke { animation: none; stroke-dashoffset: 0; }
        }

        /* Visible keyboard focus everywhere inside the auth screen */
        .auth-shell a:focus-visible,
        .auth-shell button:focus-visible,
        .auth-shell input:focus-visible { outline: 2px solid #1b84ff; outline-offset: 2px; }
        .dark .auth-shell a:focus-visible,
        .dark .auth-shell button:focus-visible,
        .dark .auth-shell input:focus-visible { outline-color: #60a5fa; }
        .auth-dark a:focus-visible, .auth-dark button:focus-visible { outline-color: #93c5fd !important; }
    </style>
</head>

<body class="auth-shell min-h-full antialiased bg-[#173684] text-[#c7d4f0] dark:text-[#c7d4f0]">

    {{-- Full-screen ground (admin navy, with rosettes); the form is a card resting on it. --}}
    <div class="relative isolate flex min-h-screen flex-col overflow-hidden" style="background: linear-gradient(to bottom, #2349ad, #1a3e9a, #173684);"
         x-data="{
            rot: 0,
            reduce: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
            tilt(e) {
                if (this.reduce) return;
                const r = this.$el.getBoundingClientRect();
                this.rot = ((e.clientX - r.left) / r.width - 0.5) * 16;
            }
         }"
         @pointermove="tilt($event)">

        <div class="auth-rosette -right-40 -top-48 -z-10 w-[56rem] max-w-none text-white/[0.085]" :style="`--rot: ${rot}deg`" aria-hidden="true"></div>
        <div class="auth-rosette -bottom-72 -left-64 -z-10 w-[40rem] max-w-none text-white/[0.05]" :style="`--rot: ${-rot}deg`" aria-hidden="true"></div>

        {{-- Illustration (original, drawn for this page): an assessor beside a framed certificate,
             holding a clipboard of ticked units and pointing at the sign-in card. Decorative. --}}
        <svg class="pointer-events-none absolute bottom-0 left-0 -z-10 hidden h-[84%] w-1/2 lg:block xl:w-[58%]"
             viewBox="0 0 720 760" preserveAspectRatio="xMidYMax meet" fill="none" aria-hidden="true">

            {{-- Framed certificate on the wall: frame, inner frame, a seal outline. Draws itself once. --}}
            <g class="auth-draw" stroke="#F6F1E7" stroke-width="3" stroke-opacity=".75">
                <path pathLength="1" d="M60 150H340V430H60Z"/>
                <path pathLength="1" d="M78 168H322V412H78Z" stroke-opacity=".6"/>
                <path pathLength="1" d="M110 230H290M110 262H250M110 294H270" stroke-linecap="round" stroke-opacity=".5"/>
            </g>
            <circle cx="262" cy="372" r="30" stroke="#E4472D" stroke-width="3" stroke-dasharray="1 7" stroke-linecap="round"/>
            <circle cx="262" cy="372" r="22" fill="#E4472D"/>
            <path d="M252 372l7 7 13-15" stroke="#1B0B07" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round"/>

            {{-- Ground shadow --}}
            <ellipse cx="500" cy="704" rx="150" ry="11" fill="#000" fill-opacity=".28"/>

            <g transform="translate(80 0)">
                {{-- Legs and shoes --}}
                <rect x="398" y="520" width="28" height="172" rx="12" fill="#1F5C53"/>
                <rect x="430" y="520" width="28" height="172" rx="12" fill="#194C45"/>
                <ellipse cx="410" cy="696" rx="27" ry="10" fill="#0A1614"/>
                <ellipse cx="448" cy="696" rx="29" ry="10" fill="#0A1614"/>

                {{-- Torso: paper shirt with a lanyard --}}
                <rect x="376" y="268" width="92" height="276" rx="30" fill="#F6F1E7"/>
                <path d="M376 520h92v24H376z" fill="#1F5C53"/>
                <path d="M410 276l12 70 12-70" stroke="#E4472D" stroke-width="4" stroke-linejoin="round"/>
                <rect x="414" y="346" width="16" height="22" rx="3" fill="#E4472D"/>

                {{-- Arm holding the clipboard --}}
                <path d="M390 306 L380 374" stroke="#F6F1E7" stroke-width="26" stroke-linecap="round"/>
                <path d="M380 374 L402 410" stroke="#D9A074" stroke-width="20" stroke-linecap="round"/>
                <g transform="rotate(-7 430 440)">
                    <rect x="396" y="392" width="68" height="94" rx="6" fill="#8A5A35"/>
                    <rect x="402" y="402" width="56" height="78" rx="3" fill="#F6F1E7"/>
                    <rect x="416" y="388" width="28" height="12" rx="3" fill="#B7BDB9"/>
                    <path d="M409 418l4 4 7-8M409 438l4 4 7-8M409 458l4 4 7-8" stroke="#E4472D" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
                    <rect x="426" y="416" width="26" height="4" rx="2" fill="#10201D" fill-opacity=".45"/>
                    <rect x="426" y="436" width="26" height="4" rx="2" fill="#10201D" fill-opacity=".45"/>
                    <rect x="426" y="456" width="18" height="4" rx="2" fill="#10201D" fill-opacity=".45"/>
                </g>
                <circle cx="406" cy="414" r="11" fill="#D9A074"/>

                {{-- Neck and head --}}
                <rect x="410" y="238" width="24" height="36" rx="10" fill="#D9A074"/>
                <circle cx="424" cy="204" r="38" fill="#E0A97A"/>
                <path d="M386 200A38 38 0 0 1 462 200C452 184 434 178 418 182C404 186 394 192 386 200Z" fill="#15151E"/>
                <circle cx="444" cy="208" r="2.8" fill="#15151E"/>
                <path d="M438 224q7 6 14 0" stroke="#8A4E2E" stroke-width="2.4" stroke-linecap="round"/>

                {{-- Pointing arm, reaching toward the card --}}
                <path d="M456 304 L516 322" stroke="#F6F1E7" stroke-width="26" stroke-linecap="round"/>
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
                        <circle cx="18" cy="18" r="16" stroke="#E4472D" stroke-width="2.4" stroke-linecap="round" stroke-dasharray="0.1 3.6"/>
                        <circle cx="18" cy="18" r="12.5" fill="#E4472D"/>
                        <path d="M11.8 18.4l4.4 4.4 8.2-9.2" stroke="#1B0B07" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                @endif
                <span class="auth-display text-xl">{{ $aboutUs?->company_name ?? config('app.name') }}</span>
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
            <div class="w-full max-w-[28rem] rounded-2xl border border-[#dce3f7] bg-white p-7 shadow-[0_30px_70px_-24px_rgba(0,0,0,0.55)] dark:border-[#2d3f7a] dark:bg-[#1e1e2d] sm:p-9">
                {{ $slot }}

                <div class="mt-7 border-t border-[#dce3f7] pt-3 dark:border-[#2d3f7a]">
                    <a href="/" class="inline-flex min-h-11 items-center text-sm font-semibold text-[#1a3e9a] underline decoration-transparent underline-offset-4 transition-colors hover:decoration-current dark:text-[#60a5fa]">
                        Kembali ke beranda
                    </a>
                </div>
            </div>
        </main>

        {{-- Quiet statement under the logo, only where there is room for it --}}
        <p class="auth-statement auth-display auth-dark absolute left-12 top-24 max-w-[19rem] text-[1.5rem] leading-tight text-white" style="text-wrap: balance;">
            Satu akun untuk mengikuti uji kompetensi Anda.
        </p>
    </div>

    @include('partials.toast-notifications')

    @fluxScripts
    @filamentScripts
</body>

</html>
