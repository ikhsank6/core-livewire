<div class="max-w-2xl mx-auto">
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="min-w-0 flex-1">
            <h2
                class="text-2xl font-bold leading-7 text-zinc-900 dark:text-white sm:truncate sm:text-3xl sm:tracking-tight">
                Change Password
            </h2>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                Update your account password.
            </p>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-800 shadow rounded-lg p-6">
        @if (session('success'))
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
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <form wire:submit="changePassword" class="space-y-6">
            <!-- Current Password -->
            <div>
                <label for="current_password"
                    class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-100">
                    Current Password
                </label>
                <div class="mt-2">
                    <input wire:model="current_password" id="current_password" name="current_password" type="password"
                        autocomplete="current-password" required
                        class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-900/50 dark:text-white dark:ring-zinc-700 dark:focus:ring-indigo-500">
                </div>
                @error('current_password') <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                @enderror
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-100">
                    New Password
                </label>
                <div class="mt-2">
                    <input wire:model="password" id="password" name="password" type="password"
                        autocomplete="new-password" required
                        class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-900/50 dark:text-white dark:ring-zinc-700 dark:focus:ring-indigo-500">
                </div>
                @error('password') <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
            </div>

            <!-- Confirm New Password -->
            <div>
                <label for="password_confirmation"
                    class="block text-sm font-medium leading-6 text-zinc-900 dark:text-zinc-100">
                    Confirm New Password
                </label>
                <div class="mt-2">
                    <input wire:model="password_confirmation" id="password_confirmation" name="password_confirmation"
                        type="password" autocomplete="new-password" required
                        class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-900/50 dark:text-white dark:ring-zinc-700 dark:focus:ring-indigo-500">
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-x-6">
                <a href="{{ route('dashboard') }}"
                    class="text-sm font-semibold leading-6 text-zinc-900 dark:text-zinc-100 hover:text-zinc-700 dark:hover:text-zinc-300">
                    Cancel
                </a>
                <flux:button type="submit" variant="primary">
                    <flux:icon.loading wire:loading class="mr-2" />
                    <span wire:loading.remove>Update Password</span>
                    <span wire:loading>Updating...</span>
                </flux:button>
            </div>
        </form>
    </div>
</div>