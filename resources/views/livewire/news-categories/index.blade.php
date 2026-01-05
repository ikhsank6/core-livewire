<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Website</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>News Categories</flux:breadcrumbs.item>
</x-slot>
<div>
    <!-- Card Container -->
    <div
        class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <!-- Card Header -->
        <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-800">
            <div class="sm:flex sm:items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-zinc-900 dark:text-white">News Categories</h1>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Manage news article categories.</p>
                </div>
                <div class="mt-4 sm:mt-0 sm:flex-none">
                    <flux:tooltip content="Add New Category" position="top">
                        <button type="button" wire:click="create"
                            class="flex items-center gap-2 rounded-lg bg-metronic-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:opacity-90 transition-all active:scale-95 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-metronic-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Add Category
                        </button>
                    </flux:tooltip>
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <x-ui.table :paginator="$categories">
                <x-slot name="header">
                    <x-ui.table.header search="search" :showFilters="false" :showBulk="false" :showColumns="false"
                        :showViewToggle="true" />
                </x-slot>

                <x-ui.table.thead>
                    <x-ui.table.th>Name</x-ui.table.th>
                    <x-ui.table.th>Slug</x-ui.table.th>
                    <x-ui.table.th>Description</x-ui.table.th>
                    <x-ui.table.th>Status</x-ui.table.th>
                    <x-ui.table.th shrink></x-ui.table.th>
                </x-ui.table.thead>

                <x-ui.table.tbody>
                    @forelse($categories as $category)
                        <x-ui.table.tr>
                            <x-ui.table.td>
                                <span class="font-bold text-zinc-900 dark:text-white">{{ $category->name }}</span>
                            </x-ui.table.td>
                            <x-ui.table.td>
                                <code
                                    class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded text-metronic-primary border border-zinc-200 dark:border-zinc-700">{{ $category->slug }}</code>
                            </x-ui.table.td>
                            <x-ui.table.td>
                                <span
                                    class="text-zinc-500 dark:text-zinc-400">{{ Str::limit($category->description, 50) }}</span>
                            </x-ui.table.td>
                            <x-ui.table.td>
                                <x-ui.badge :variant="$category->is_active ? 'success' : 'danger'">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </x-ui.badge>
                            </x-ui.table.td>
                            <x-ui.table.td shrink>
                                <div class="flex items-center justify-end gap-2">
                                    <flux:tooltip content="Edit Category" position="top">
                                        <button wire:click="edit('{{ $category->uuid }}')"
                                            class="p-2 text-zinc-400 hover:text-metronic-primary hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-all active:scale-90">
                                            <flux:icon name="pencil-square" variant="mini" class="w-4 h-4" />
                                        </button>
                                    </flux:tooltip>

                                    <flux:tooltip content="Hapus Category" position="top">
                                        <button x-on:click="$dispatch('open-delete-confirm', { 
                                                                id: '{{ $category->uuid }}', 
                                                                componentId: '{{ $this->getId() }}',
                                                                message: 'Are you sure you want to delete this category?'
                                                            })"
                                            class="p-2 text-zinc-400 hover:text-metronic-danger hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all active:scale-90">
                                            <flux:icon name="trash" variant="mini" class="w-4 h-4" />
                                        </button>
                                    </flux:tooltip>
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
                            <div
                                class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 hover:shadow-md transition-all group">
                                <div class="flex items-start justify-between mb-4 gap-2">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-metronic-primary/10 flex items-center justify-center text-metronic-primary shrink-0">
                                            <flux:icon name="tag" variant="outline" class="w-6 h-6" />
                                        </div>
                                        <div class="min-w-0">
                                            <h3 class="font-bold text-zinc-900 dark:text-white truncate"
                                                title="{{ $category->name }}">{{ $category->name }}</h3>
                                            <code
                                                class="text-[10px] text-metronic-primary font-mono uppercase tracking-wider block truncate">{{ $category->slug }}</code>
                                        </div>
                                    </div>
                                    <div class="shrink-0">
                                        <x-ui.badge :variant="$category->is_active ? 'success' : 'danger'"
                                            class="whitespace-nowrap px-1.5 py-0">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </x-ui.badge>
                                    </div>
                                </div>
                                <div class="space-y-3 mb-5">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-zinc-500">Description</span>
                                        <span class="text-zinc-700 dark:text-zinc-300 truncate ml-4"
                                            title="{{ $category->description }}">{{ Str::limit($category->description ?: 'No description', 30) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-zinc-500">Created</span>
                                        <span
                                            class="text-zinc-700 dark:text-zinc-300">{{ $category->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                <div
                                    class="flex items-center justify-end gap-2 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                                    <flux:tooltip content="Edit Category" position="top">
                                        <button wire:click="edit('{{ $category->uuid }}')"
                                            class="p-2 text-zinc-400 hover:text-metronic-primary hover:bg-zinc-50 dark:hover:bg-zinc-800 rounded-lg transition-colors">
                                            <flux:icon name="pencil-square" variant="mini" class="w-4 h-4" />
                                        </button>
                                    </flux:tooltip>
                                    <flux:tooltip content="Hapus Category" position="top">
                                        <button x-on:click="$dispatch('open-delete-confirm', { 
                                                                    id: '{{ $category->uuid }}', 
                                                                    componentId: '{{ $this->getId() }}',
                                                                    message: 'Hapus kategori {{ $category->name }}?'
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
                    <x-ui.pagination :paginator="$categories" />
                </x-slot>
            </x-ui.table>
        </div>
    </div>

    <!-- Edit/Create Modal -->
    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit Category' : 'Create Category'" formId="category-form">
        <form wire:submit="save" id="category-form" novalidate>
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>