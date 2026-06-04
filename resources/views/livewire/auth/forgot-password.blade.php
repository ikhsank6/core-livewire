<div>
    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Lupa Kata Sandi?</h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
            Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.
        </p>
    </div>

    {{-- Success alert --}}
    @if (session('status'))
        <div class="mb-5 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700/40 p-3.5 flex items-start gap-3">
            <div class="shrink-0 mt-0.5 flex items-center justify-center w-5 h-5 rounded-full bg-green-500">
                <svg class="w-3 h-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 6.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l7-7a1 1 0 000-1.414z" clip-rule="evenodd"/>
                </svg>
            </div>
            <p class="text-sm text-green-700 dark:text-green-300">{{ session('status') }}</p>
        </div>
    @endif

    <form wire:submit="sendResetLink" class="space-y-5" novalidate>

        {{-- Email --}}
        <div>
            <label for="email" class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                Alamat Email
            </label>
            <input id="email" wire:model="email" type="email" autocomplete="email"
                placeholder="nama@contoh.com"
                @class([
                    'block w-full rounded-lg border px-3.5 py-2.5 text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 transition-colors',
                    'border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500' => !$errors->has('email'),
                    'border-red-400 dark:border-red-500 focus:ring-red-400 bg-red-50 dark:bg-red-900/10' => $errors->has('email'),
                ])>
            @error('email')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Submit --}}
        <button type="submit"
            class="w-full flex items-center justify-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 active:bg-blue-800 px-5 py-2.5 text-sm font-semibold text-white transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-60 disabled:cursor-not-allowed"
            wire:loading.attr="disabled">
            <svg wire:loading class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
            </svg>
            <svg wire:loading.remove class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span wire:loading.remove>Kirim Tautan Reset</span>
            <span wire:loading>Mengirim...</span>
        </button>

        {{-- Back to login --}}
        <div class="text-center pt-1">
            <a href="{{ route('auth.login') }}" wire:navigate
                class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke halaman masuk
            </a>
        </div>
    </form>
</div>
