<div>
    <div class="mb-8 text-center">
        <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">
            Set new password
        </h1>
        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
            Please enter your new password below.
        </p>
    </div>

    <form wire:submit="resetPassword" class="space-y-6" novalidate>
        <flux:field>
            <flux:label>Email address</flux:label>
            <flux:input wire:model="email" type="email" autocomplete="email" readonly />
            <flux:error name="email" />
        </flux:field>

        <flux:field>
            <flux:label>New Password</flux:label>
            <flux:input wire:model.live="password" type="password" viewable autocomplete="new-password" />

            <flux:error name="password" />

            <x-password-strength :strength="$this->passwordStrength" :requirements="$this->passwordRequirements" />
        </flux:field>

        <flux:field>
            <flux:label>Confirm New Password</flux:label>
            <flux:input wire:model="password_confirmation" type="password" viewable autocomplete="new-password" />
            <flux:error name="password_confirmation" />
        </flux:field>

        <!-- Submit -->
        <button type="submit" wire:loading.attr="disabled"
            class="flex h-12 w-full cursor-pointer items-center justify-center gap-2 rounded-md bg-[#E7BA4B] shadow-[0_3px_0_#B8901F] active:translate-y-[3px] active:shadow-none px-5 text-base font-bold text-[#14213A] transition-[filter] hover:brightness-105 disabled:cursor-not-allowed disabled:opacity-60">
            <span wire:loading.remove>Reset Password</span>
            <span wire:loading>Resetting...</span>
        </button>
    </form>
</div>