<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Master Data</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Menu</flux:breadcrumbs.item>
</x-slot>
<div>
    <!-- Card Container -->
    <div
        class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <!-- Card Header -->
        <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-800">
            <div class="sm:flex sm:items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-zinc-900 dark:text-white">Menus</h1>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Organize your application navigation and
                        hierarchy. Drag items to reorder.</p>
                </div>
                <div class="mt-4 sm:mt-0 sm:flex-none">
                    <button type="button" wire:click="create"
                        class="flex items-center gap-2 rounded-lg bg-[#1b84ff] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#0070f0] transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#1b84ff]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Add Menu
                    </button>
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <div class="mb-4">
                <x-ui.table.header search="search" :showFilters="false" :showBulk="false" :showColumns="false"
                    :showPageSize="false" />
            </div>

            <!-- Drag & Drop Menu List -->
            <div class="premium-table-container overflow-hidden bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-xl"
                x-data="{
                    dragging: null,
                    dragOver: null,
                    items: @js($menus->pluck('id')->toArray()),
                    
                    handleDragStart(e, id) {
                        this.dragging = id;
                        e.dataTransfer.effectAllowed = 'move';
                        e.target.classList.add('opacity-50');
                    },
                    
                    handleDragEnd(e) {
                        e.target.classList.remove('opacity-50');
                        this.dragging = null;
                        this.dragOver = null;
                    },
                    
                    handleDragOver(e, id) {
                        e.preventDefault();
                        if (this.dragging !== id) {
                            this.dragOver = id;
                        }
                    },
                    
                    handleDragLeave(e) {
                        this.dragOver = null;
                    },
                    
                    handleDrop(e, targetId) {
                        e.preventDefault();
                        if (this.dragging === targetId) return;
                        
                        const dragIndex = this.items.indexOf(this.dragging);
                        const targetIndex = this.items.indexOf(targetId);
                        
                        // Reorder array
                        this.items.splice(dragIndex, 1);
                        this.items.splice(targetIndex, 0, this.dragging);
                        
                        // Call Livewire to save order
                        $wire.updateOrder(this.items);
                        
                        this.dragging = null;
                        this.dragOver = null;
                    }
                }">

                <table class="w-full text-left border-separate border-spacing-0">
                    <thead class="bg-zinc-100 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                        <tr>
                            <th
                                class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-zinc-500 dark:text-zinc-400 uppercase whitespace-nowrap bg-transparent w-10">
                            </th>
                            <th
                                class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-zinc-500 dark:text-zinc-400 uppercase whitespace-nowrap bg-transparent">
                                Name</th>
                            <th
                                class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-zinc-500 dark:text-zinc-400 uppercase whitespace-nowrap bg-transparent">
                                Slug</th>
                            <th
                                class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-zinc-500 dark:text-zinc-400 uppercase whitespace-nowrap bg-transparent">
                                Route</th>
                            <th
                                class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-zinc-500 dark:text-zinc-400 uppercase whitespace-nowrap bg-transparent">
                                Parent</th>
                            <th
                                class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-zinc-500 dark:text-zinc-400 uppercase whitespace-nowrap bg-transparent">
                                Order</th>
                            <th
                                class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-zinc-500 dark:text-zinc-400 uppercase whitespace-nowrap bg-transparent">
                                Status</th>
                            <th
                                class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-zinc-500 dark:text-zinc-400 uppercase whitespace-nowrap bg-transparent w-20">
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700 bg-white dark:bg-zinc-900">
                        @forelse($menus as $menu)
                            <tr draggable="true" x-on:dragstart="handleDragStart($event, {{ $menu->id }})"
                                x-on:dragend="handleDragEnd($event)" x-on:dragover="handleDragOver($event, {{ $menu->id }})"
                                x-on:dragleave="handleDragLeave($event)" x-on:drop="handleDrop($event, {{ $menu->id }})"
                                class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors cursor-grab active:cursor-grabbing"
                                :class="{ 'bg-[#1b84ff]/10 border-[#1b84ff] border-2': dragOver === {{ $menu->id }} }">

                                <!-- Drag Handle -->
                                <td class="px-4 py-4 text-sm text-zinc-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 8h16M4 16h16">
                                        </path>
                                    </svg>
                                </td>

                                <td class="px-4 py-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="{{ $menu->parent_id ? 'pl-6 text-zinc-500 dark:text-zinc-400' : 'font-bold text-zinc-900 dark:text-white' }}">
                                            @if($menu->icon)
                                                <span class="mr-2">{{ $menu->icon }}</span>
                                            @endif
                                            {{ $menu->name }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm">
                                    <code
                                        class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded text-[#1b84ff] border border-zinc-200 dark:border-zinc-700">{{ $menu->slug }}</code>
                                </td>
                                <td class="px-4 py-4 text-sm">
                                    <span class="text-zinc-500 dark:text-zinc-400 text-xs">{{ $menu->route ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-4 text-sm">
                                    <span class="text-zinc-500 dark:text-zinc-400">{{ $menu->parent->name ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-4 text-sm">
                                    <x-ui.badge variant="neutral">{{ $menu->order }}</x-ui.badge>
                                </td>
                                <td class="px-4 py-4 text-sm">
                                    <x-ui.badge :variant="$menu->is_active ? 'success' : 'danger'">
                                        {{ $menu->is_active ? 'Active' : 'Inactive' }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-4 py-4 text-sm">
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="edit('{{ $menu->uuid }}')"
                                            class="p-2 text-zinc-400 hover:text-[#1b84ff] hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button 
                                            x-on:click="$dispatch('open-delete-confirm', { 
                                                id: '{{ $menu->uuid }}', 
                                                componentId: '{{ $this->getId() }}',
                                                message: 'Apakah Anda yakin ingin menghapus menu {{ $menu->name }}?'
                                            })"
                                            class="p-2 text-zinc-400 hover:text-[#f8285a] hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <x-ui.empty-state />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <x-ui.pagination :paginator="$menus" />
            </div>
        </div>
    </div>

    <!-- Edit/Create Modal -->
    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit Menu' : 'Create Menu'" formId="menu-form">
        <form wire:submit="save" id="menu-form" novalidate>
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>