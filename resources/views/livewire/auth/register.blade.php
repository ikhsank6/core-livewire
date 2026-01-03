<div>
    @if($registered)
        {{-- Registration Success - Show verification message --}}
        <div class="text-center">
            <div
                class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 dark:bg-green-900/30 mb-4">
                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">
                Check Your Email
            </h2>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400 max-w-sm mx-auto">
                We've sent a verification link to <span
                    class="font-semibold text-zinc-900 dark:text-white">{{ $email }}</span>.
                Click the link in the email to activate your account.
            </p>
            <div class="mt-6 p-4 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800">
                <p class="text-sm text-amber-800 dark:text-amber-200">
                    <strong>Didn't receive the email?</strong> Check your spam folder or wait a few minutes.
                </p>
            </div>
            <div class="mt-6">
                <a href="{{ route('auth.login') }}" wire:navigate
                    class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Go to Login
                </a>
            </div>
        </div>
    @else
        {{-- Registration Form --}}
        <div class="mb-8 text-center">
            <h2 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">
                Create a new account
            </h2>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                Or
                <a href="{{ route('auth.login') }}" wire:navigate
                    class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                    sign in to your existing account
                </a>
            </p>
        </div>

        <form wire:submit="register" class="space-y-6">
            <flux:field>
                <flux:label>Full Name</flux:label>
                <flux:input wire:model="name" type="text" autocomplete="name" required />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Email address</flux:label>
                <flux:input wire:model="email" type="email" autocomplete="email" required />
                <flux:error name="email" />
            </flux:field>

            <flux:field>
                <flux:label>Password</flux:label>
                <flux:input wire:model="password" type="password" viewable autocomplete="new-password" required />
                <flux:error name="password" />
            </flux:field>

            <flux:field>
                <flux:label>Confirm Password</flux:label>
                <flux:input wire:model="password_confirmation" type="password" viewable autocomplete="new-password"
                    required />
                <flux:error name="password_confirmation" />
            </flux:field>

            <!-- Submit -->
            <flux:button type="submit" variant="primary" class="w-full">
                <flux:icon.loading wire:loading class="mr-2" />
                <span wire:loading.remove>Register</span>
                <span wire:loading>Creating account...</span>
            </flux:button>
        </form>
    @endif
</div>