<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>CMS</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Berita</flux:breadcrumbs.item>
</x-slot>
<div>
    <x-ui.card title="Berita" description="Kelola artikel dan publikasi berita">

        <x-slot name="headerAction">
            <x-ui.button.add label="Tambah" tooltip="Buat Artikel Baru" />
        </x-slot>

        <x-ui.table :paginator="$news" :view="$view">
            <x-slot name="header">
                <x-ui.table.header
                    search="search"
                    searchPlaceholder="Cari judul berita..."
                    :showFilters="false"
                    :showBulk="false"
                    :showColumns="false"
                    :showPageSize="false"
                    :showReload="true"
                    :showViewToggle="true" />
            </x-slot>

            <x-ui.table.thead>
                <x-ui.table.th>Gambar</x-ui.table.th>
                <x-ui.table.th>Judul</x-ui.table.th>
                <x-ui.table.th>Kategori</x-ui.table.th>
                <x-ui.table.th>Dipublikasikan</x-ui.table.th>
                <x-ui.table.th>Status</x-ui.table.th>
                <x-ui.table.th shrink>Actions</x-ui.table.th>
            </x-ui.table.thead>

            <x-ui.table.tbody>
                @forelse($news as $item)
                    <x-ui.table.tr>
                        <x-ui.table.td>
                            @if($item->image)
                                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}"
                                    class="w-16 h-10 object-cover rounded-lg">
                            @else
                                <div class="w-16 h-10 bg-zinc-100 dark:bg-zinc-800 rounded-lg flex items-center justify-center">
                                    <flux:icon name="photo" class="w-4 h-4 text-zinc-400" />
                                </div>
                            @endif
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <div class="flex flex-col">
                                <span class="font-semibold text-zinc-900 dark:text-white line-clamp-1">{{ $item->title }}</span>
                                <span class="text-xs text-zinc-500">{{ Str::limit($item->summary, 40) }}</span>
                            </div>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge variant="user">{{ $item->category->name ?? 'Tanpa Kategori' }}</x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <span class="text-zinc-500">{{ $item->published_at?->format('j M Y') ?? '-' }}</span>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge :variant="$item->is_active ? 'info' : 'danger'">
                                {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                            </x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td shrink>
                            <div class="flex items-center justify-end gap-1">
                                <x-ui.button.view icon="eye" wire:click="edit('{{ $item->uuid }}')" x-on:click="$dispatch('crud-modal-open')" tooltip="Lihat Detail Berita" />
                                <x-ui.button.edit :uuid="$item->uuid" tooltip="Edit Berita" />
                                <x-ui.button.delete :uuid="$item->uuid" :name="$item->title" tooltip="Hapus Berita"
                                    :message="'Apakah Anda yakin ingin menghapus berita ' . $item->title . '?'" />
                            </div>
                        </x-ui.table.td>
                    </x-ui.table.tr>
                @empty
                    <x-ui.table.tr>
                        <x-ui.table.td colspan="6">
                            <x-ui.empty-state />
                        </x-ui.table.td>
                    </x-ui.table.tr>
                @endforelse
            </x-ui.table.tbody>

            <x-slot name="board">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($news as $item)
                        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 hover:shadow-md transition-all group">
                            <div class="flex items-start justify-between mb-4 gap-2">
                                <div class="flex items-center gap-3 min-w-0">
                                    @if($item->image)
                                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}"
                                            class="w-12 h-12 rounded-xl object-cover shrink-0 border border-zinc-200 dark:border-zinc-700">
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center shrink-0">
                                            <flux:icon name="photo" variant="mini" class="w-6 h-6 text-zinc-400" />
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <h3 class="font-bold text-zinc-900 dark:text-white truncate">{{ $item->title }}</h3>
                                        <p class="text-xs text-zinc-500 truncate">{{ $item->category->name ?? 'Tanpa Kategori' }}</p>
                                    </div>
                                </div>
                                <x-ui.badge :variant="$item->is_active ? 'info' : 'danger'" class="whitespace-nowrap shrink-0">
                                    {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                </x-ui.badge>
                            </div>
                            <div class="space-y-3 mb-5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-zinc-500">Ringkasan</span>
                                    <span class="text-zinc-700 dark:text-zinc-300 truncate ml-4">{{ Str::limit($item->summary, 40) }}</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-zinc-500">Dipublikasikan</span>
                                    <span class="text-zinc-700 dark:text-zinc-300">{{ $item->published_at?->format('j M Y') ?? 'Draft' }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-end gap-2 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                                <x-ui.button.edit :uuid="$item->uuid" tooltip="Edit Berita" />
                                <x-ui.button.delete :uuid="$item->uuid" :name="$item->title" tooltip="Hapus Berita"
                                    :message="'Apakah Anda yakin ingin menghapus berita ' . $item->title . '?'" />
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
                <x-ui.pagination :paginator="$news" perPage="perPage" />
            </x-slot>
        </x-ui.table>
    </x-ui.card>

    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit Berita' : 'Tambah Berita'" formId="news-form">
        @if($showModal)
            <form wire:submit="save" id="news-form" novalidate>
                {{ $this->form }}
            </form>
        @else
            <x-ui.modal-skeleton :rows="5" />
        @endif
    </x-ui.modal>
</div>
