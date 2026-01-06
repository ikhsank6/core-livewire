<div>
    {{-- Header --}}
    <div class="mb-8 text-center">
        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-800 dark:text-white">
            Sign in to your account
        </h2>
        <p class="mt-3 text-sm sm:text-base text-slate-600 dark:text-slate-400">
            Or
            <a href="{{ route('auth.register') }}" wire:navigate
                class="font-semibold text-orange-500 hover:text-orange-600 dark:text-orange-400 dark:hover:text-orange-300 transition-colors duration-200">
                register for a new account
            </a>
        </p>
    </div>

    <form wire:submit="login" class="space-y-6">
        {{-- Email --}}
        <div class="space-y-2">
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                Email address
            </label>
            <flux:input wire:model="email" type="email" autocomplete="email" required placeholder="Enter your email" />
            <flux:error name="email" />
        </div>

        {{-- Password --}}
        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Password
                </label>
                <a href="{{ route('auth.password.request') }}" wire:navigate
                    class="text-sm font-medium text-orange-500 hover:text-orange-600 dark:text-orange-400 dark:hover:text-orange-300 transition-colors duration-200">
                    Forgot password?
                </a>
            </div>
            <flux:input wire:model="password" type="password" viewable autocomplete="current-password" required
                placeholder="Enter your password" />
            <flux:error name="password" />
        </div>

        {{-- Remember Me --}}
        <div class="flex items-center gap-3">
            <input wire:model="remember" id="remember-me" name="remember-me" type="checkbox"
                class="h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-orange-500 focus:ring-orange-500 focus:ring-offset-0 dark:bg-slate-800 dark:ring-offset-slate-900 cursor-pointer transition-colors">
            <label for="remember-me" class="text-sm text-slate-600 dark:text-slate-400 cursor-pointer select-none">
                Remember me
            </label>
        </div>

        {{-- Submit Button --}}
        <div class="pt-2">
            <button type="submit"
                class="auth-btn-primary w-full flex items-center justify-center gap-2 py-3.5 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg">
                <flux:icon.loading wire:loading class="w-5 h-5 animate-spin" />
                <span wire:loading.remove>Sign in</span>
                <span wire:loading>Signing in...</span>
            </button>
        </div>
    </form>

    {{-- Social Login Divider --}}
    <div class="mt-8">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-200 dark:border-slate-700/50"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span
                    class="px-4 bg-white/80 dark:bg-slate-900/50 text-slate-500 dark:text-slate-500 text-xs uppercase tracking-wider font-medium rounded">
                    Secure Login
                </span>
            </div>
        </div>
        <p class="mt-4 text-center text-xs text-slate-500 dark:text-slate-500">
            Protected by industry-standard encryption
        </p>
    </div>
</div>