@props([
    'search' => null,
    'showFilters' => true,
    'showBulk' => true,
    'showColumns' => true,
    'showPageSize' => true,
])

<div {{ $attributes->class(['flex flex-col md:flex-row md:items-center justify-between gap-4 py-4']) }}>
    <div class="flex items-center gap-2">
        <div class="relative w-full md:w-64">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-[#99a1b7]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input 
                type="text" 
                placeholder="Search" 
                {{ $search ? 'wire:model.live.debounce.300ms=' . $search : '' }}
                class="block w-full pl-10 pr-3 py-2 bg-[#f9f9f9] dark:bg-[#1b1c22] border border-[#e8e8e8] dark:border-[#2d2d3a] text-[#1b1c22] dark:text-white text-sm rounded-lg focus:ring-2 focus:ring-[#1b84ff] focus:border-[#1b84ff] placeholder-[#99a1b7] dark:placeholder-[#6d6d80] transition-all"
            >
        </div>
        
        @if($showFilters)
        <button class="flex items-center gap-2 px-3 py-2 bg-white dark:bg-[#1e1e2d] border border-[#e8e8e8] dark:border-[#2d2d3a] rounded-lg text-[#4b5675] dark:text-[#a1a5b7] text-sm hover:bg-[#f9f9f9] dark:hover:bg-[#252532] transition-colors">
            <span>Filters</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
        </button>
        @endif
    </div>

    <div class="flex items-center gap-2">
        @if($showBulk)
        <flux:dropdown>
            <flux:button variant="ghost" class="bg-white! dark:bg-[#1e1e2d]! text-[#4b5675]! dark:text-[#a1a5b7]! border-[#e8e8e8]! dark:border-[#2d2d3a]! hover:bg-[#f9f9f9]! dark:hover:bg-[#252532]!" icon-trailing="chevron-down">Bulk Actions</flux:button>
            <flux:menu>
                <flux:menu.item>Export Selected</flux:menu.item>
                <flux:menu.item variant="danger">Delete Selected</flux:menu.item>
            </flux:menu>
        </flux:dropdown>
        @endif

        @if($showColumns)
        <flux:dropdown>
            <flux:button variant="ghost" class="bg-white! dark:bg-[#1e1e2d]! text-[#4b5675]! dark:text-[#a1a5b7]! border-[#e8e8e8]! dark:border-[#2d2d3a]! hover:bg-[#f9f9f9]! dark:hover:bg-[#252532]!" icon-trailing="chevron-down">Columns</flux:button>
            <flux:menu>
                <flux:menu.checkbox checked>Type</flux:menu.checkbox>
                <flux:menu.checkbox checked>Name</flux:menu.checkbox>
                <flux:menu.checkbox checked>Email</flux:menu.checkbox>
                <flux:menu.checkbox checked>Active</flux:menu.checkbox>
            </flux:menu>
        </flux:dropdown>
        @endif

        @if($showPageSize)
        <div class="flex items-center gap-2">
            <span class="text-xs font-medium text-[#99a1b7] dark:text-[#6d6d80]">Show</span>
            <div class="relative group">
                <select 
                    class="appearance-none block w-16 pl-3 pr-8 py-2 bg-[#f9f9f9] dark:bg-[#1b1c22] border border-[#e8e8e8] dark:border-[#2d2d3a] text-[#1b1c22] dark:text-white text-xs font-bold rounded-lg focus:ring-2 focus:ring-[#1b84ff] focus:border-[#1b84ff] transition-all cursor-pointer"
                >
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-[#99a1b7] group-hover:text-[#4b5675] dark:group-hover:text-[#a1a5b7]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>