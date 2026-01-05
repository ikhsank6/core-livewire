<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>CMS</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>News</flux:breadcrumbs.item>
</x-slot>
<div>
    <!-- Card Container -->
    <div
        class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <!-- Card Header -->
        <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-800">
            <div class="sm:flex sm:items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-zinc-900 dark:text-white">News</h1>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Manage news articles and publications.</p>
                </div>
                <div class="mt-4 sm:mt-0 sm:flex-none">
                    <flux:tooltip content="Add New News" position="top">
                        <button type="button" wire:click="create"
                            class="flex items-center gap-2 rounded-lg bg-metronic-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:opacity-90 transition-all active:scale-95 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-metronic-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Add News
                        </button>
                    </flux:tooltip>
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <x-ui.table :paginator="$news" :view="$view">
                <x-slot name="header">
                    <x-ui.table.header search="search" :showFilters="false" :showBulk="false" :showColumns="false"
                        :showViewToggle="true" />
                </x-slot>

                <x-ui.table.thead>
                    <x-ui.table.th>Image</x-ui.table.th>
                    <x-ui.table.th>Title</x-ui.table.th>
                    <x-ui.table.th>Category</x-ui.table.th>
                    <x-ui.table.th>Published</x-ui.table.th>
                    <x-ui.table.th>Active</x-ui.table.th>
                    <x-ui.table.th shrink></x-ui.table.th>
                </x-ui.table.thead>

                <x-ui.table.tbody>
                    @forelse($news as $item)
                        <x-ui.table.tr>
                            <x-ui.table.td>
                                @if($item->image)
                                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}"
                                        class="w-16 h-10 object-cover rounded-lg">
                                @else
                                    <div
                                        class="w-16 h-10 bg-zinc-100 dark:bg-zinc-800 rounded-lg flex items-center justify-center">
                                        <flux:icon name="photo" class="w-4 h-4 text-zinc-400" />
                                    </div>
                                @endif
                            </x-ui.table.td>
                            <x-ui.table.td>
                                <div class="flex flex-col">
                                    <span
                                        class="font-bold text-zinc-900 dark:text-white line-clamp-1">{{ $item->title }}</span>
                                    <span class="text-xs text-zinc-500">{{ Str::limit($item->summary, 40) }}</span>
                                </div>
                            </x-ui.table.td>
                            <x-ui.table.td>
                                <x-ui.badge variant="neutral">{{ $item->category->name ?? 'Uncategorized' }}</x-ui.badge>
                            </x-ui.table.td>
                            <x-ui.table.td>
                                <span class="text-zinc-500">{{ $item->published_at?->format('d M Y') ?? '-' }}</span>
                            </x-ui.table.td>
                            <x-ui.table.td>
                                <x-ui.badge :variant="$item->is_active ? 'success' : 'danger'">
                                    {{ $item->is_active ? 'Yes' : 'No' }}
                                </x-ui.badge>
                            </x-ui.table.td>
                            <x-ui.table.td shrink>
                                <div class="flex items-center justify-end gap-2 text-right">
                                    <flux:tooltip content="Edit News" position="top">
                                        <button wire:click="edit('{{ $item->uuid }}')"
                                            class="p-2 text-zinc-400 hover:text-metronic-primary hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-all active:scale-90">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                    </flux:tooltip>

                                    <flux:tooltip content="Delete News" position="top">
                                        <button x-on:click="$dispatch('open-delete-confirm', { 
                                                                        id: '{{ $item->uuid }}', 
                                                                        componentId: '{{ $this->getId() }}',
                                                                        message: 'Delete news article {{ $item->title }}?'
                                                                    })"
                                            class="p-2 text-zinc-400 hover:text-metronic-danger hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all active:scale-90">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </flux:tooltip>
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
                            <div
                                class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 hover:shadow-md transition-all group">
                                <div class="flex items-start justify-between mb-4 gap-2">
                                    <div class="flex items-center gap-3 min-w-0">
                                        @if($item->image)
                                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}"
                                                class="w-12 h-12 rounded-xl object-cover shrink-0 border border-zinc-200 dark:border-zinc-700">
                                        @else
                                            <div
                                                class="w-12 h-12 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center shrink-0">
                                                <flux:icon name="photo" variant="mini" class="w-6 h-6 text-zinc-400" />
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <h3 class="font-bold text-zinc-900 dark:text-white truncate"
                                                title="{{ $item->title }}">{{ $item->title }}</h3>
                                            <p class="text-xs text-zinc-500 truncate"
                                                title="{{ $item->category->name ?? 'General' }}">
                                                {{ $item->category->name ?? 'General' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="shrink-0">
                                        <x-ui.badge :variant="$item->is_active ? 'success' : 'danger'"
                                            class="whitespace-nowrap px-1.5 py-0">
                                            {{ $item->is_active ? 'Active' : 'Inactive' }}
                                        </x-ui.badge>
                                    </div>
                                </div>
                                <div class="space-y-3 mb-5">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-zinc-500">Summary</span>
                                        <span class="text-zinc-700 dark:text-zinc-300 truncate ml-4"
                                            title="{{ $item->summary }}">{{ Str::limit($item->summary, 40) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-zinc-500">Published</span>
                                        <span
                                            class="text-zinc-700 dark:text-zinc-300">{{ $item->published_at?->format('M d, Y') ?? 'Draft' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="flex items-center justify-end gap-2 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                                    <flux:tooltip content="Edit News" position="top">
                                        <button wire:click="edit('{{ $item->uuid }}')"
                                            class="p-2 text-zinc-400 hover:text-metronic-primary hover:bg-zinc-50 dark:hover:bg-zinc-800 rounded-lg transition-colors">
                                            <flux:icon name="pencil-square" variant="mini" class="w-4 h-4" />
                                        </button>
                                    </flux:tooltip>
                                    <flux:tooltip content="Hapus News" position="top">
                                        <button x-on:click="$dispatch('open-delete-confirm', { 
                                                                id: '{{ $item->uuid }}', 
                                                                componentId: '{{ $this->getId() }}',
                                                                message: 'Delete {{ $item->title }}?'
                                                            })"
                                            class="p-2 text-zinc-400 hover:text-metronic-danger hover:bg-red-50 dark:hover:bg-red-900/10 rounded-lg transition-colors">
                                            <flux:icon name="trash" variant="mini" class="w-4 h-4" />
                                        </button>
                                    </flux:tooltip>
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
                    <x-ui.pagination :paginator="$news" />
                </x-slot>
            </x-ui.table>
        </div>
    </div>

    <!-- Modal Form -->
    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit News' : 'Add News'" formId="news-form">
        <form wire:submit="save" id="news-form" novalidate>
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>