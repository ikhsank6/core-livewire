@php
    $primaryBtn = 'inline-flex h-12 w-full cursor-pointer items-center justify-center gap-2 rounded-md bg-[#E7BA4B] shadow-[0_3px_0_#B8901F] active:translate-y-[3px] active:shadow-none px-6 text-base font-bold text-[#14213A] transition-[filter] hover:brightness-105';
    $goldIconBadge = 'inline-flex h-16 w-16 items-center justify-center rounded-full bg-[#E7BA4B] shadow-[0_3px_0_#B8901F] text-[#14213A] mb-5';
    $passwordNote = 'mt-5 rounded-md border border-amber-300 bg-amber-50 p-3 text-left text-sm leading-relaxed text-amber-900 dark:border-amber-700 dark:bg-amber-950/40 dark:text-amber-100';
@endphp

<div>
    <div class="text-center">
        @if($verified)
            {{-- Successfully Verified --}}
            <div class="{{ $goldIconBadge }}">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-[#1b1c22] dark:text-white">
                Email berhasil diverifikasi
            </h1>
            <p class="mt-2 text-sm text-[#4A5E75] dark:text-gray-400 leading-relaxed">
                Alamat email Anda sudah terverifikasi. Anda bisa masuk ke akun Anda.
            </p>
            @if($mustChangePassword)
                <p class="{{ $passwordNote }}" role="note">
                    Masuk dengan <strong>kata sandi sementara</strong> yang ada di email verifikasi, lalu segera ganti kata sandi Anda.
                </p>
            @endif
            <div class="mt-6">
                <a href="{{ route('auth.login') }}" wire:navigate class="{{ $primaryBtn }}">
                    <span class="inline-flex" aria-hidden="true">@svg('heroicon-o-arrow-right-end-on-rectangle', 'h-5 w-5')</span>
                    <span>Ke halaman masuk</span>
                </a>
            </div>
        @elseif($alreadyVerified)
            {{-- Already Verified --}}
            <div class="{{ $goldIconBadge }}">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-[#1b1c22] dark:text-white">
                Sudah terverifikasi
            </h1>
            <p class="mt-2 text-sm text-[#4A5E75] dark:text-gray-400 leading-relaxed">
                Alamat email ini sudah diverifikasi sebelumnya. Silakan masuk.
            </p>
            @if($mustChangePassword)
                <p class="{{ $passwordNote }}" role="note">
                    Masuk dengan <strong>kata sandi sementara</strong> yang ada di email verifikasi, lalu segera ganti kata sandi Anda.
                </p>
            @endif
            <div class="mt-6">
                <a href="{{ route('auth.login') }}" wire:navigate class="{{ $primaryBtn }}">
                    <span class="inline-flex" aria-hidden="true">@svg('heroicon-o-arrow-right-end-on-rectangle', 'h-5 w-5')</span>
                    <span>Ke halaman masuk</span>
                </a>
            </div>
        @elseif($invalidLink)
            {{-- Invalid Link --}}
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 mb-5">
                <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-[#1b1c22] dark:text-white">
                Tautan verifikasi tidak berlaku
            </h1>
            <p class="mt-2 text-sm text-[#4A5E75] dark:text-gray-400 leading-relaxed">
                Tautan ini tidak valid atau sudah kedaluwarsa. Minta pengelola mengirim ulang email verifikasi.
            </p>
            <div class="mt-6">
                <a href="{{ route('auth.login') }}" wire:navigate class="{{ $primaryBtn }}">
                    <span>Ke halaman masuk</span>
                </a>
            </div>
        @else
            {{-- Loading / Processing --}}
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#E7BA4B]/20 mb-5">
                <svg class="w-8 h-8 text-[#B8901F] dark:text-[#E7BA4B] animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-[#1b1c22] dark:text-white">
                Memverifikasi...
            </h1>
            <p class="mt-2 text-sm text-[#4A5E75] dark:text-gray-400 leading-relaxed">
                Mohon tunggu, email Anda sedang diverifikasi.
            </p>
        @endif
    </div>
</div>
