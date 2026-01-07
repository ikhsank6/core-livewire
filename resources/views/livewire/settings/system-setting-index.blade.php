<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Settings</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>System</flux:breadcrumbs.item>
</x-slot>

<div>
    <!-- Card Container -->
    <div
        class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <!-- Card Header -->
        <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-800">
            <div class="sm:flex sm:items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-zinc-900 dark:text-white">System Settings</h1>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        Configure SEO metadata and analytics for your application.
                    </p>
                    <p class="mt-2 text-xs text-zinc-400 dark:text-zinc-500">
                        <strong>Note:</strong> Branding settings (App Name, Logo, Contact Info) are managed in
                        <a href="{{ route('cms.about-us.index') }}" class="text-indigo-500 hover:underline">CMS > About
                            Us</a>.
                    </p>
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <form wire:submit="save" class="space-y-6">
                {{ $this->form }}

                <div class="flex items-center justify-end gap-3 pt-6 border-t border-zinc-200 dark:border-zinc-800">
                    <flux:button type="submit" variant="primary" icon="check-circle" class="px-6">
                        Save Settings
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</div>