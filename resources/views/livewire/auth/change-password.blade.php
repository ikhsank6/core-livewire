<div>
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-light text-zinc-900 dark:text-white">Change Password</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400">Update your account password.</p>
    </div>

    <!-- Password Settings Card -->
    <div
        class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <!-- Card Header -->
        <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-800">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Password Settings</h2>
        </div>

        <form wire:submit="changePassword">
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Current Password -->
                <flux:field class="md:col-span-2">
                    <flux:label>Current Password</flux:label>
                    <flux:input wire:model="current_password" type="password" viewable placeholder="••••••••" />
                    <flux:error name="current_password" />
                </flux:field>

                <!-- New Password -->
                <flux:field>
                    <flux:label>New Password</flux:label>
                    <flux:input wire:model.live="password" type="password" viewable placeholder="••••••••" />

                    <flux:error name="password" />

                    <x-password-strength :strength="$this->passwordStrength"
                        :requirements="$this->passwordRequirements" />
                </flux:field>

                <!-- Confirm New Password -->
                <flux:field>
                    <flux:label>Confirm Password</flux:label>
                    <flux:input wire:model="password_confirmation" type="password" viewable placeholder="••••••••" />
                    <flux:error name="password_confirmation" />
                </flux:field>
            </div>

            <!-- Buttons -->
            <div
                class="px-6 py-4 bg-zinc-50 dark:bg-zinc-800/50 flex items-center justify-end gap-x-3 border-t border-zinc-200 dark:border-zinc-800">
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold text-zinc-600 dark:text-zinc-400 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 hover:text-zinc-900 dark:hover:text-white border border-zinc-200 dark:border-zinc-700 transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled">
                    <svg wire:loading.remove class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <svg wire:loading class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove>Update Password</span>
                    <span wire:loading>Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>