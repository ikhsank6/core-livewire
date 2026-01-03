<div>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">
            Reset your password
        </h2>
        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
            Enter your email address and we'll send you a link to reset your password.
        </p>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-md bg-green-50 p-4 dark:bg-green-900/50">
            <div class="flex">
                <div class="shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        {{ session('status') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit="sendResetLink" class="space-y-6">
        <flux:field>
            <flux:label>Email address</flux:label>
            <flux:input wire:model="email" type="email" autocomplete="email" required />
            <flux:error name="email" />
        </flux:field>

        <!-- Submit -->
        <flux:button type="submit" variant="primary" class="w-full">
            <flux:icon.loading wire:loading class="mr-2" />
            <span wire:loading.remove>Send Password Reset Link</span>
            <span wire:loading>Sending...</span>
        </flux:button>

        <div class="text-sm text-center">
            <a href="{{ route('auth.login') }}" wire:navigate
                class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                Back to login
            </a>
        </div>
    </form>
</div>