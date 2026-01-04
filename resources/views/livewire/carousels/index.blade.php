<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Website</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Carousels</flux:breadcrumbs.item>
</x-slot>
<div>
    <!-- Card Container -->
    <div
        class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <!-- Card Header -->
        <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-800">
            <div class="sm:flex sm:items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-zinc-900 dark:text-white">Carousels</h1>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Manage homepage carousel/slider images.</p>
                </div>
                <div class="mt-4 sm:mt-0 sm:flex-none">
                    <flux:tooltip content="Add New Carousel" position="top">
                        <button type="button" wire:click="create"
                            class="flex items-center gap-2 rounded-lg bg-metronic-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:opacity-90 transition-all active:scale-95 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-metronic-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Add Carousel
                        </button>
                    </flux:tooltip>
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <x-ui.table :paginator="$carousels">
                <x-slot name="header">
                    <x-ui.table.header search="search" :showFilters="false" :showBulk="false" :showColumns="false" />
                </x-slot>

                <x-ui.table.thead>
                    <x-ui.table.th>Image</x-ui.table.th>
                    <x-ui.table.th>Title</x-ui.table.th>
                    <x-ui.table.th>Order</x-ui.table.th>
                    <x-ui.table.th>Status</x-ui.table.th>
                    <x-ui.table.th shrink></x-ui.table.th>
                </x-ui.table.thead>

                <x-ui.table.tbody>
                    @forelse($carousels as $carousel)
                        <x-ui.table.tr>
                            <x-ui.table.td>
                                @if($carousel->image)
                                    <img src="{{ Storage::url($carousel->image) }}" alt="{{ $carousel->title }}"
                                        class="w-24 h-14 object-cover rounded-lg border border-zinc-200 dark:border-zinc-700">
                                @else
                                    <div
                                        class="w-24 h-14 bg-zinc-200 dark:bg-zinc-800 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-zinc-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                            </x-ui.table.td>
                            <x-ui.table.td>
                                <div class="flex flex-col">
                                    <span class="font-bold text-zinc-900 dark:text-white">{{ $carousel->title }}</span>
                                    <span
                                        class="text-xs text-zinc-500 dark:text-zinc-400">{{ Str::limit($carousel->description, 50) }}</span>
                                </div>
                            </x-ui.table.td>
                            <x-ui.table.td>
                                <x-ui.badge variant="info">{{ $carousel->order }}</x-ui.badge>
                            </x-ui.table.td>
                            <x-ui.table.td>
                                <x-ui.badge :variant="$carousel->is_active ? 'success' : 'danger'">
                                    {{ $carousel->is_active ? 'Active' : 'Inactive' }}
                                </x-ui.badge>
                            </x-ui.table.td>
                            <x-ui.table.td shrink>
                                <div class="flex items-center justify-end gap-2">
                                    <flux:tooltip content="Edit Carousel" position="top">
                                        <button wire:click="edit('{{ $carousel->uuid }}')"
                                            class="p-2 text-zinc-400 hover:text-metronic-primary hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-all active:scale-90">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                    </flux:tooltip>

                                    <flux:tooltip content="Delete Carousel" position="top">
                                        <button x-on:click="$dispatch('open-delete-confirm', { 
                                                        id: '{{ $carousel->uuid }}', 
                                                        componentId: '{{ $this->getId() }}',
                                                        message: 'Are you sure you want to delete this carousel?'
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
                            <x-ui.table.td colspan="5">
                                <x-ui.empty-state />
                            </x-ui.table.td>
                        </x-ui.table.tr>
                    @endforelse
                </x-ui.table.tbody>

                <x-slot name="footer">
                    <x-ui.pagination :paginator="$carousels" />
                </x-slot>
            </x-ui.table>
        </div>
    </div>

    <!-- Edit/Create Modal -->
    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit Carousel' : 'Create Carousel'" formId="carousel-form"
        maxWidth="3xl">
        <form wire:submit="save" id="carousel-form" novalidate>
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>