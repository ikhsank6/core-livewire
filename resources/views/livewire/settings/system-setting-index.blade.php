<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Pengaturan</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Sistem</flux:breadcrumbs.item>
</x-slot>

<x-ui.card title="Pengaturan Sistem" description="Konfigurasi metadata SEO dan analitik untuk aplikasi Anda">

    <x-slot name="headerExtras">
        <p class="mt-2 text-xs text-zinc-400 dark:text-zinc-500">
            <strong>Catatan:</strong> Pengaturan branding (Nama Aplikasi, Logo, Info Kontak) dikelola di
            <a href="{{ route('cms.about-us.index') }}" class="text-indigo-500 hover:underline">CMS > Tentang Kami</a>.
        </p>
    </x-slot>

    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex items-center justify-end gap-3 pt-6 border-t border-zinc-200 dark:border-zinc-800">
            <flux:button type="submit" variant="primary" icon="check-circle" class="px-6">
                Simpan Pengaturan
            </flux:button>
        </div>
    </form>
</x-ui.card>
