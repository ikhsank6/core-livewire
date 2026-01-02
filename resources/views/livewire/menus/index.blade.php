<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Master Data</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Menu</flux:breadcrumbs.item>
</x-slot>
<div>
    <div class="sm:flex sm:items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-[#1b1c22] dark:text-white">Menus</h1>
            <p class="mt-1 text-sm text-[#99a1b7] dark:text-[#6d6d80]">Organize your application navigation and
                hierarchy. Drag items to reorder.
            </p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <button type="button" wire:click="create"
                class="flex items-center gap-2 rounded-lg bg-[#1b84ff] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#0070f0] transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#1b84ff]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Menu
            </button>
        </div>
    </div>

    <div class="mb-4">
        <x-ui.table.header search="search" :showFilters="false" :showBulk="false" :showColumns="false"
            :showPageSize="false" />
    </div>

    <!-- Drag & Drop Menu List -->
    <div class="premium-table-container overflow-hidden bg-white dark:bg-[#1e1e2d] border border-[#e8e8e8] dark:border-[#2d2d3a] rounded-xl"
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
            <thead class="bg-[#f9f9f9] dark:bg-[#1e1e2d] border-b border-[#e8e8e8] dark:border-[#2d2d3a]">
                <tr>
                    <th
                        class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-[#99a1b7] dark:text-[#6d6d80] uppercase whitespace-nowrap bg-transparent w-10">
                    </th>
                    <th
                        class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-[#99a1b7] dark:text-[#6d6d80] uppercase whitespace-nowrap bg-transparent">
                        Name</th>
                    <th
                        class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-[#99a1b7] dark:text-[#6d6d80] uppercase whitespace-nowrap bg-transparent">
                        Slug</th>
                    <th
                        class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-[#99a1b7] dark:text-[#6d6d80] uppercase whitespace-nowrap bg-transparent">
                        Route</th>
                    <th
                        class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-[#99a1b7] dark:text-[#6d6d80] uppercase whitespace-nowrap bg-transparent">
                        Parent</th>
                    <th
                        class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-[#99a1b7] dark:text-[#6d6d80] uppercase whitespace-nowrap bg-transparent">
                        Order</th>
                    <th
                        class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-[#99a1b7] dark:text-[#6d6d80] uppercase whitespace-nowrap bg-transparent">
                        Status</th>
                    <th
                        class="px-4 py-4 text-[10px] font-bold tracking-[0.2em] text-[#99a1b7] dark:text-[#6d6d80] uppercase whitespace-nowrap bg-transparent w-20">
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e8e8e8] dark:divide-[#2d2d3a]">
                @forelse($menus as $menu)
                    <tr draggable="true" x-on:dragstart="handleDragStart($event, {{ $menu->id }})"
                        x-on:dragend="handleDragEnd($event)" x-on:dragover="handleDragOver($event, {{ $menu->id }})"
                        x-on:dragleave="handleDragLeave($event)" x-on:drop="handleDrop($event, {{ $menu->id }})"
                        class="hover:bg-[#f9f9f9] dark:hover:bg-[#252532] transition-colors cursor-grab active:cursor-grabbing"
                        :class="{ 'bg-[#1b84ff]/10 border-[#1b84ff] border-2': dragOver === {{ $menu->id }} }">

                        <!-- Drag Handle -->
                        <td class="px-4 py-4 text-sm text-[#99a1b7]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16">
                                </path>
                            </svg>
                        </td>

                        <td class="px-4 py-4 text-sm">
                            <div class="flex items-center gap-2">
                                <span
                                    class="{{ $menu->parent_id ? 'pl-6 text-[#99a1b7]' : 'font-bold text-[#1b1c22] dark:text-white' }}">
                                    @if($menu->icon)
                                        <span class="mr-2">{{ $menu->icon }}</span>
                                    @endif
                                    {{ $menu->name }}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm">
                            <code
                                class="text-xs bg-[#f1f1f4] dark:bg-[#1e1e2d] px-1.5 py-0.5 rounded text-[#1b84ff] border border-[#e8e8e8] dark:border-[#2d2d3a]">{{ $menu->slug }}</code>
                        </td>
                        <td class="px-4 py-4 text-sm">
                            <span class="text-[#99a1b7] text-xs">{{ $menu->route ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-4 text-sm">
                            <span class="text-[#99a1b7]">{{ $menu->parent->name ?? '-' }}</span>
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
                                <button wire:click="edit({{ $menu->id }})"
                                    class="p-2 text-[#99a1b7] hover:text-[#1b84ff] hover:bg-[#f1f1f4] dark:hover:bg-[#252532] rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>
                                <button wire:confirm="Are you sure?" wire:click="delete({{ $menu->id }})"
                                    class="p-2 text-[#99a1b7] hover:text-[#f8285a] hover:bg-[#f8285a]/10 rounded-lg transition-all">
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
                        <td colspan="8" class="text-center py-12">
                            <div class="flex flex-col items-center justify-center text-[#99a1b7]">
                                <svg class="w-12 h-12 mb-4 opacity-20" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h7"></path>
                                </svg>
                                <p class="text-base font-medium">No menus found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <x-ui.pagination :paginator="$menus" />
    </div>

    <!-- Modal -->
    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit Menu' : 'Create Menu'" formId="menu-form">
        <form wire:submit="save" id="menu-form">
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>