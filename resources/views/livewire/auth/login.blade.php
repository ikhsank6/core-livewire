<div>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">
            Sign in to your account
        </h2>
        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
            Or
            <a href="{{ route('auth.register') }}" wire:navigate
                class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                register for a new account
            </a>
        </p>
    </div>

    <form wire:submit="login" class="space-y-6">
        <!-- Email -->
        <flux:field>
            <flux:label>Email address</flux:label>
            <flux:input wire:model="email" type="email" autocomplete="email" required />
            <flux:error name="email" />
        </flux:field>

        <!-- Password -->
        <flux:field>
            <div class="flex items-center justify-between">
                <flux:label>Password</flux:label>
                <flux:link href="{{ route('password.request') }}" wire:navigate variant="subtle"
                    class="text-sm font-medium">
                    Forgot password?
                </flux:link>
            </div>
            <flux:input wire:model="password" type="password" viewable autocomplete="current-password" required />
            <flux:error name="password" />
        </flux:field>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input wire:model="remember" id="remember-me" name="remember-me" type="checkbox"
                class="h-4 w-4 rounded border-zinc-300 text-indigo-600 focus:ring-indigo-600 dark:bg-zinc-800 dark:border-zinc-700 dark:ring-offset-zinc-900">
            <label for="remember-me" class="ml-3 block text-sm leading-6 text-zinc-900 dark:text-zinc-300">
                Remember me
            </label>
        </div>

        <!-- Submit -->
        <flux:button type="submit" variant="primary" class="w-full">
            <flux:icon.loading wire:loading class="mr-2" />
            <span wire:loading.remove>Sign in</span>
            <span wire:loading>Signing in...</span>
        </flux:button>
    </form>
</div>