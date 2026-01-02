<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Master Data</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Akses Menu</flux:breadcrumbs.item>
</x-slot>
<div>
    @if($selectedRole)
        <!-- Menu Access Configuration for Selected Role -->
        <div class="sm:flex sm:items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <button wire:click="backToRoles"
                    class="p-2 text-[#99a1b7] hover:text-[#1b84ff] hover:bg-[#f1f1f4] dark:hover:bg-[#252532] rounded-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </button>
                <div>
                    <h1 class="text-2xl font-bold text-[#1b1c22] dark:text-white">Menu Access: {{ $selectedRole->name }}
                    </h1>
                    <p class="mt-1 text-sm text-[#99a1b7] dark:text-[#6d6d80]">
                        Configure which menus this role can access.
                    </p>
                </div>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <button type="button" wire:click="saveMenuAccess"
                    class="flex items-center gap-2 rounded-lg bg-[#17c653] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#14a847] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Changes
                </button>
            </div>
        </div>

        <!-- Menu Tree Checkboxes -->
        <div class="bg-white dark:bg-[#1e1e2d] border border-[#e8e8e8] dark:border-[#2d2d3a] rounded-xl overflow-hidden">
            <div class="p-6">
                <div class="space-y-2">
                    @forelse($menus as $parentMenu)
                        <div class="border border-[#e8e8e8] dark:border-[#2d2d3a] rounded-lg overflow-hidden">
                            <!-- Parent Menu -->
                            <div class="flex items-center gap-4 px-4 py-3 bg-[#f9f9f9] dark:bg-[#252532]">
                                <label class="flex items-center gap-3 cursor-pointer flex-1">
                                    <input type="checkbox" wire:click="toggleMenu({{ $parentMenu->id }})"
                                        @checked(in_array($parentMenu->id, $selectedMenus))
                                        class="w-5 h-5 rounded border-[#e8e8e8] dark:border-[#2d2d3a] text-[#1b84ff] focus:ring-[#1b84ff] cursor-pointer">
                                    <div class="flex items-center gap-2">
                                        @if($parentMenu->icon)
                                            <span class="text-[#99a1b7]">{{ $parentMenu->icon }}</span>
                                        @endif
                                        <span class="font-bold text-[#1b1c22] dark:text-white">{{ $parentMenu->name }}</span>
                                        <code
                                            class="text-xs bg-[#f1f1f4] dark:bg-[#1e1e2d] px-1.5 py-0.5 rounded text-[#1b84ff] border border-[#e8e8e8] dark:border-[#2d2d3a]">{{ $parentMenu->slug }}</code>
                                    </div>
                                </label>
                                @if($parentMenu->route)
                                    <span class="text-xs text-[#99a1b7]">{{ $parentMenu->route }}</span>
                                @endif
                            </div>

                            <!-- Child Menus -->
                            @if($parentMenu->children->count() > 0)
                                <div class="divide-y divide-[#e8e8e8] dark:divide-[#2d2d3a]">
                                    @foreach($parentMenu->children as $childMenu)
                                        <div
                                            class="flex items-center gap-4 px-4 py-3 pl-12 hover:bg-[#f9f9f9] dark:hover:bg-[#252532]/50">
                                            <label class="flex items-center gap-3 cursor-pointer flex-1">
                                                <input type="checkbox" wire:click="toggleMenu({{ $childMenu->id }})"
                                                    @checked(in_array($childMenu->id, $selectedMenus))
                                                    class="w-5 h-5 rounded border-[#e8e8e8] dark:border-[#2d2d3a] text-[#1b84ff] focus:ring-[#1b84ff] cursor-pointer">
                                                <div class="flex items-center gap-2">
                                                    @if($childMenu->icon)
                                                        <span class="text-[#99a1b7]">{{ $childMenu->icon }}</span>
                                                    @endif
                                                    <span class="text-[#4b5675] dark:text-[#a1a5b7]">{{ $childMenu->name }}</span>
                                                    <code
                                                        class="text-xs bg-[#f1f1f4] dark:bg-[#1e1e2d] px-1.5 py-0.5 rounded text-[#1b84ff] border border-[#e8e8e8] dark:border-[#2d2d3a]">{{ $childMenu->slug }}</code>
                                                </div>
                                            </label>
                                            @if($childMenu->route)
                                                <span class="text-xs text-[#99a1b7]">{{ $childMenu->route }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-[#99a1b7]">
                            <svg class="w-12 h-12 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h7"></path>
                            </svg>
                            <p class="text-base font-medium">No menus available</p>
                            <p class="text-sm">Please create menus first in the Menus section.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    @else
        <!-- Role Selection -->
        <div class="sm:flex sm:items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-[#1b1c22] dark:text-white">Menu Access</h1>
                <p class="mt-1 text-sm text-[#99a1b7] dark:text-[#6d6d80]">
                    Select a role to configure its menu access permissions.
                </p>
            </div>
        </div>

        <!-- Search -->
        <div class="mb-6">
            <div class="relative w-full max-w-md">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-[#99a1b7]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search roles..."
                    class="block w-full pl-10 pr-3 py-2.5 bg-[#f9f9f9] dark:bg-[#1b1c22] border border-[#e8e8e8] dark:border-[#2d2d3a] text-[#1b1c22] dark:text-white text-sm rounded-lg focus:ring-2 focus:ring-[#1b84ff] focus:border-[#1b84ff] placeholder-[#99a1b7] dark:placeholder-[#6d6d80] transition-all">
            </div>
        </div>

        <!-- Role Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($roles as $role)
                <button wire:click="selectRole({{ $role->id }})"
                    class="relative flex items-center gap-4 rounded-xl border border-[#e8e8e8] dark:border-[#2d2d3a] bg-white dark:bg-[#1e1e2d] px-5 py-4 text-left transition-all hover:border-[#1b84ff] hover:shadow-lg hover:shadow-[#1b84ff]/10 group">

                    <div class="flex-shrink-0">
                        <div
                            class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#1b84ff] to-[#0070f0] flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-[#1b84ff]/25">
                            {{ strtoupper(substr($role->name, 0, 1)) }}
                        </div>
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="text-base font-bold text-[#1b1c22] dark:text-white group-hover:text-[#1b84ff]">
                            {{ $role->name }}
                        </p>
                        <p class="text-sm text-[#99a1b7] dark:text-[#6d6d80]">{{ $role->slug }}</p>
                        <div class="mt-1">
                            <x-ui.badge variant="info">{{ $role->users_count }} users</x-ui.badge>
                        </div>
                    </div>

                    <div class="text-[#99a1b7] group-hover:text-[#1b84ff] transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </button>
            @empty
                <div class="col-span-full">
                    <div class="flex flex-col items-center justify-center py-12 text-[#99a1b7]">
                        <svg class="w-12 h-12 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <p class="text-base font-medium">No roles found</p>
                    </div>
                </div>
            @endforelse
        </div>
    @endif
</div>