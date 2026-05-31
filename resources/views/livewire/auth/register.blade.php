<div>
    @if($registered)
        {{-- Success state --}}
        <div class="py-4 text-center">
            <div class="mx-auto mb-5 flex items-center justify-center w-14 h-14 rounded-full bg-green-100 dark:bg-green-900/30">
                <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Cek Email Anda</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-6">
                Kami telah mengirim link verifikasi ke
                <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $email }}</span>.
                Klik link tersebut untuk mengaktifkan akun Anda.
            </p>
            <div class="rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700/40 p-3 mb-6 text-left">
                <p class="text-xs text-amber-700 dark:text-amber-300">
                    Tidak menerima email? Periksa folder spam atau tunggu beberapa menit.
                </p>
            </div>
            <a href="{{ route('auth.login') }}" wire:navigate
                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14"/>
                </svg>
                Ke Halaman Masuk
            </a>
        </div>
    @else
        {{-- Register form --}}
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Buat akun baru</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Sudah punya akun?
                <a href="{{ route('auth.login') }}" wire:navigate
                    class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-500 dark:hover:text-blue-400 transition-colors">
                    Masuk di sini
                </a>
            </p>
        </div>

        <form wire:submit="register" class="space-y-4" novalidate>

            {{-- Name --}}
            <div>
                <label for="name" class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nama Lengkap
                </label>
                <input id="name" wire:model="name" type="text" autocomplete="name"
                    placeholder="John Doe"
                    @class([
                        'block w-full rounded-lg border px-3.5 py-2.5 text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 transition-colors',
                        'border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500' => !$errors->has('name'),
                        'border-red-400 dark:border-red-500 focus:ring-red-400 bg-red-50 dark:bg-red-900/10' => $errors->has('name'),
                    ])>
                @error('name')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

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
                        <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Password --}}
            <div x-data="{ show: false }">
                <label for="password" class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Kata Sandi
                </label>
                <div class="relative">
                    <input id="password" wire:model.live="password" :type="show ? 'text' : 'password'"
                        autocomplete="new-password" placeholder="••••••••"
                        @class([
                            'block w-full rounded-lg border px-3.5 py-2.5 pr-10 text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 transition-colors',
                            'border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500' => !$errors->has('password'),
                            'border-red-400 dark:border-red-500 focus:ring-red-400 bg-red-50 dark:bg-red-900/10' => $errors->has('password'),
                        ])>
                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror

                {{-- Password strength --}}
                <x-password-strength :strength="$this->passwordStrength" :requirements="$this->passwordRequirements" />
            </div>

            {{-- Confirm Password --}}
            <div x-data="{ show: false }">
                <label for="password_confirmation" class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Konfirmasi Kata Sandi
                </label>
                <div class="relative">
                    <input id="password_confirmation" wire:model="password_confirmation"
                        :type="show ? 'text' : 'password'"
                        autocomplete="new-password" placeholder="••••••••"
                        @class([
                            'block w-full rounded-lg border px-3.5 py-2.5 pr-10 text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 transition-colors',
                            'border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500' => !$errors->has('password_confirmation'),
                            'border-red-400 dark:border-red-500 focus:ring-red-400 bg-red-50 dark:bg-red-900/10' => $errors->has('password_confirmation'),
                        ])>
                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
                @error('password_confirmation')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="pt-1">
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 active:bg-blue-800 px-5 py-2.5 text-sm font-semibold text-white transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-60 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled">
                    <svg wire:loading class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                    <span wire:loading.remove>Buat Akun</span>
                    <span wire:loading>Memproses...</span>
                </button>
            </div>
        </form>

        <p class="mt-5 text-center text-xs text-gray-400 dark:text-gray-500">
            Dengan membuat akun, Anda menyetujui
            <a href="#" class="text-blue-600 dark:text-blue-500 hover:underline">Syarat Layanan</a>
            dan
            <a href="#" class="text-blue-600 dark:text-blue-500 hover:underline">Kebijakan Privasi</a> kami.
        </p>
    @endif
</div>
