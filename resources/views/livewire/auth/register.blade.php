<div>
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
</div>