@php
    // Field styling. The error state matches the register form: only the border and focus
    // ring turn red (the background stays as it is), plus a small red message with an icon.
    $input = 'block h-12 w-full rounded-lg border px-3.5 text-[0.9375rem] text-[#0E1424] placeholder-[#667089] transition-colors dark:text-white dark:placeholder-[#6C7899] focus:outline-none focus:ring-2';
    $ok = 'bg-white dark:bg-[#0A1126] border-[#C9CEC6] dark:border-[#2C3B66] focus:ring-[#1A3E9A] focus:border-[#1A3E9A]';
    $bad = 'bg-white dark:bg-[#0A1126] border-red-400 dark:border-red-500 focus:ring-red-400 focus:border-red-400';
    $link = 'inline-block -my-3 py-3 font-semibold text-[#1A3E9A] underline decoration-transparent underline-offset-4 transition-colors hover:decoration-current dark:text-[#8DB4FF]';
@endphp

<div>
    <h1 class="text-[2rem] font-bold leading-[1.05] text-[#0E1424] dark:text-white" style="text-wrap: balance;">
        Masuk ke akun Anda
    </h1>
    <p class="mt-3 text-[0.9375rem]">
        Belum punya akun?
        <a href="{{ route('auth.register') }}" wire:navigate class="{{ $link }}">Daftar sekarang</a>
    </p>

    <form wire:submit="login" class="mt-7 space-y-5" novalidate>

        {{-- Email --}}
        <div>
            <label for="email" class="mb-2 block text-sm font-semibold text-[#0E1424] dark:text-[#F2F5FF]">Alamat email</label>
            <div class="relative">
                <input id="email" wire:model="email" type="email" autocomplete="email" inputmode="email"
                    placeholder="nama@contoh.com"
                    @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                    class="peer {{ $input }} pl-12 {{ $errors->has('email') ? $bad : $ok }}">
                <span class="pointer-events-none absolute inset-y-0 left-0 grid w-12 place-items-center text-[#5B6478] transition-colors peer-focus:text-[#1A3E9A] dark:text-[#94A0BE] dark:peer-focus:text-[#8DB4FF]" aria-hidden="true">
                    @svg('heroicon-o-envelope', 'h-5 w-5')
                </span>
            </div>
            @error('email')
                <p id="email-error" role="alert" class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Password --}}
        <div x-data="{ show: false }">
            <div class="mb-2 flex items-center justify-between gap-4">
                <label for="password" class="text-sm font-semibold text-[#0E1424] dark:text-[#F2F5FF]">Kata sandi</label>
                <a href="{{ route('auth.password.request') }}" wire:navigate class="{{ $link }} text-sm">Lupa kata sandi?</a>
            </div>
            <div class="relative">
                <input id="password" wire:model="password" :type="show ? 'text' : 'password'"
                    autocomplete="current-password" placeholder="Masukkan kata sandi"
                    @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
                    class="peer {{ $input }} pl-12 pr-12 {{ $errors->has('password') ? $bad : $ok }}">
                <span class="pointer-events-none absolute inset-y-0 left-0 grid w-12 place-items-center text-[#5B6478] transition-colors peer-focus:text-[#1A3E9A] dark:text-[#94A0BE] dark:peer-focus:text-[#8DB4FF]" aria-hidden="true">
                    @svg('heroicon-o-lock-closed', 'h-5 w-5')
                </span>
                <button type="button" @click="show = !show"
                    :aria-label="show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                    :aria-pressed="show ? 'true' : 'false'"
                    class="absolute inset-y-0 right-0 grid w-12 cursor-pointer place-items-center text-[#5B6478] transition-colors hover:text-[#0E1424] dark:text-[#94A0BE] dark:hover:text-white">
                    <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p id="password-error" role="alert" class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Remember me --}}
        <label for="remember" class="flex min-h-11 cursor-pointer select-none items-center gap-3 text-[0.9375rem]">
            <input id="remember" wire:model="remember" type="checkbox"
                class="h-5 w-5 cursor-pointer rounded border-[#C9CEC6] bg-white accent-[#1A3E9A] dark:border-[#2C3B66] dark:bg-[#0A1126] dark:accent-[#8DB4FF]">
            Ingat saya di perangkat ini
        </label>

        {{-- Submit: kunyit is the one accent, used here at the key moment --}}
        <button type="submit" wire:loading.attr="disabled"
            class="flex h-12 w-full cursor-pointer items-center justify-center gap-2 rounded-md bg-[#FFB81C] px-5 text-base font-bold text-[#0B1B4D] transition-[filter] hover:brightness-95 active:brightness-90 disabled:cursor-not-allowed disabled:opacity-60">
            <svg wire:loading class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
            </svg>
            <span wire:loading.remove class="inline-flex" aria-hidden="true">@svg('heroicon-o-arrow-right-end-on-rectangle', 'h-5 w-5')</span>
            <span wire:loading.remove>Masuk</span>
            <span wire:loading>Memproses...</span>
        </button>
    </form>
</div>
