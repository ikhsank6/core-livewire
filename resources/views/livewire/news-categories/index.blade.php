<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>CMS</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Kategori Berita</flux:breadcrumbs.item>
</x-slot>
<div>
    <x-ui.card title="Kategori Berita" description="Kelola kategori artikel berita">

        <x-slot name="headerAction">
            <x-ui.button.add label="Tambah" tooltip="Tambah Kategori Baru" />
        </x-slot>

        <x-ui.table :paginator="$categories" :view="$view">
            <x-slot name="header">
                <x-ui.table.header
                    search="search"
                    searchPlaceholder="Cari nama kategori..."
                    :showFilters="false"
                    :showBulk="false"
                    :showColumns="false"
                    :showPageSize="false"
                    :showReload="true"
                    :showViewToggle="true" />
            </x-slot>

            <x-ui.table.thead>
                <x-ui.table.th>Nama</x-ui.table.th>
                <x-ui.table.th>Slug</x-ui.table.th>
                <x-ui.table.th>Deskripsi</x-ui.table.th>
                <x-ui.table.th>Status</x-ui.table.th>
                <x-ui.table.th shrink>Actions</x-ui.table.th>
            </x-ui.table.thead>

            <x-ui.table.tbody>
                @forelse($categories as $category)
                    <x-ui.table.tr>
                        <x-ui.table.td>
                            <span class="font-semibold text-zinc-900 dark:text-white">{{ $category->name }}</span>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <code class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded text-metronic-primary border border-zinc-200 dark:border-zinc-700">{{ $category->slug }}</code>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <span class="text-zinc-500 dark:text-zinc-400">{{ Str::limit($category->description, 50) ?: '-' }}</span>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge :variant="$category->is_active ? 'info' : 'danger'">
                                {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                            </x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td shrink>
                            <div class="flex items-center justify-end gap-1">
                                <x-ui.button.view icon="eye" wire:click="edit('{{ $category->uuid }}')" x-on:click="$dispatch('crud-modal-open')" tooltip="Lihat Detail Kategori" />
                                <x-ui.button.edit :uuid="$category->uuid" tooltip="Edit Kategori" />
                                <x-ui.button.delete :uuid="$category->uuid" :name="$category->name" tooltip="Hapus Kategori"
                                    :message="'Apakah Anda yakin ingin menghapus kategori ' . $category->name . '?'" />
                            </div>
                        </x-ui.table.td>
                    </x-ui.table.tr>
                @empty
                    <x-ui.table.tr>
                        <x-ui.table.td colspan="5">
                            <x-ui.empty-state />
                        </x-ui.table.td>
                    </x-ui.table.tr>
                @endforelse
            </x-ui.table.tbody>

            <x-slot name="board">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($categories as $category)
                        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 hover:shadow-md transition-all group">
                            <div class="flex items-start justify-between mb-4 gap-2">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-xl bg-metronic-primary/10 flex items-center justify-center text-metronic-primary shrink-0">
                                        <flux:icon name="tag" variant="outline" class="w-6 h-6" />
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="font-bold text-zinc-900 dark:text-white truncate">{{ $category->name }}</h3>
                                        <code class="text-[10px] text-metronic-primary font-mono block truncate">{{ $category->slug }}</code>
                                    </div>
                                </div>
                                <x-ui.badge :variant="$category->is_active ? 'info' : 'danger'" class="whitespace-nowrap shrink-0">
                                    {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                </x-ui.badge>
                            </div>
                            <div class="space-y-3 mb-5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-zinc-500">Deskripsi</span>
                                    <span class="text-zinc-700 dark:text-zinc-300 truncate ml-4">{{ Str::limit($category->description ?: '-', 30) }}</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-zinc-500">Dibuat</span>
                                    <span class="text-zinc-700 dark:text-zinc-300">{{ $category->created_at->format('j M Y') }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-end gap-2 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                                <x-ui.button.edit :uuid="$category->uuid" tooltip="Edit Kategori" />
                                <x-ui.button.delete :uuid="$category->uuid" :name="$category->name" tooltip="Hapus Kategori"
                                    :message="'Apakah Anda yakin ingin menghapus kategori ' . $category->name . '?'" />
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full">
                            <x-ui.empty-state />
                        </div>
                    @endforelse
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-ui.pagination :paginator="$categories" perPage="perPage" />
            </x-slot>
        </x-ui.table>
    </x-ui.card>

    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit Kategori' : 'Tambah Kategori'" formId="category-form">
        @if($showModal)
            <form wire:submit="save" id="category-form" novalidate>
                {{ $this->form }}
            </form>
        @else
            <x-ui.modal-skeleton :rows="4" />
        @endif
    </x-ui.modal>
</div>
