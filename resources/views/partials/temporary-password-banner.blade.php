{{-- Shown on every admin page while the account still uses the password that was emailed. --}}
@if(auth()->user()?->must_change_password)
    <div x-data="{ show: true }" x-show="show" x-on:password-changed.window="show = false" role="alert"
         class="mx-auto max-w-7xl px-6 lg:px-8 pt-6">
        <div class="flex flex-wrap items-center gap-x-4 gap-y-3 rounded-2xl border border-amber-300 bg-amber-50 p-4 text-amber-900 shadow-sm dark:border-amber-700 dark:bg-amber-950/40 dark:text-amber-100">
            <flux:icon name="key" variant="mini" class="w-5 h-5 shrink-0" />
            <p class="min-w-0 flex-1 text-sm">
                <span class="font-semibold">Anda masih memakai kata sandi sementara dari email.</span>
                Ganti sekarang agar akun Anda aman.
            </p>
            @unless(request()->routeIs('password.change'))
                <a href="{{ route('password.change') }}" wire:navigate
                   class="inline-flex h-10 items-center gap-2 rounded-full bg-amber-900 px-5 text-sm font-bold text-white transition-all hover:bg-amber-800 active:scale-95 dark:bg-amber-200 dark:text-amber-950 dark:hover:bg-amber-100">
                    <flux:icon name="arrow-right" variant="mini" class="w-4 h-4" />
                    Ganti kata sandi
                </a>
            @endunless
        </div>
    </div>
@endif
