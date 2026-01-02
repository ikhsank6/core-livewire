<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Master Data</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Akses Menu</flux:breadcrumbs.item>
</x-slot>
<div>
    @if($selectedRole)
        <!-- Card Container for Menu Access Configuration -->
        <div
            class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
            <!-- Card Header -->
            <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-800">
                <div class="sm:flex sm:items-center justify-between">
                    <div class="flex items-center gap-4">
                        <button wire:click="backToRoles"
                            class="p-2 text-zinc-400 hover:text-[#1b84ff] hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </button>
                        <div>
                            <h1 class="text-xl font-bold text-zinc-900 dark:text-white">Menu Access:
                                {{ $selectedRole->name }}</h1>
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                Configure which menus this role can access.
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 sm:mt-0 sm:flex-none">
                        <button type="button" wire:click="saveMenuAccess"
                            class="flex items-center gap-2 rounded-lg bg-[#17c653] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#14a847] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card Body - Menu Tree Checkboxes -->
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($menus as $parentMenu)
                        <div
                            class="border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden bg-zinc-50 dark:bg-zinc-800/50">
                            <!-- Parent Menu -->
                            <div class="flex items-center gap-4 px-4 py-3.5 bg-zinc-100 dark:bg-zinc-800">
                                <label class="flex items-center gap-3 cursor-pointer flex-1">
                                    <input type="checkbox" wire:click="toggleMenu({{ $parentMenu->id }})"
                                        @checked(in_array($parentMenu->id, $selectedMenus))
                                        class="w-5 h-5 rounded border-zinc-300 dark:border-zinc-600 text-[#1b84ff] focus:ring-[#1b84ff] focus:ring-offset-0 cursor-pointer">
                                    <div class="flex items-center gap-2">
                                        @if($parentMenu->icon)
                                            <span class="text-zinc-400">{{ $parentMenu->icon }}</span>
                                        @endif
                                        <span class="font-bold text-zinc-900 dark:text-white">{{ $parentMenu->name }}</span>
                                        <code
                                            class="text-xs bg-white dark:bg-zinc-900 px-1.5 py-0.5 rounded text-[#1b84ff] border border-zinc-200 dark:border-zinc-700">{{ $parentMenu->slug }}</code>
                                    </div>
                                </label>
                                @if($parentMenu->route)
                                    <span
                                        class="text-xs text-zinc-500 dark:text-zinc-400 hidden sm:block">{{ $parentMenu->route }}</span>
                                @endif
                            </div>

                            <!-- Child Menus -->
                            @if($parentMenu->children->count() > 0)
                                <div class="divide-y divide-zinc-200 dark:divide-zinc-700 bg-white dark:bg-zinc-900">
                                    @foreach($parentMenu->children as $childMenu)
                                        <div
                                            class="flex items-center gap-4 px-4 py-3 pl-12 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                            <label class="flex items-center gap-3 cursor-pointer flex-1">
                                                <input type="checkbox" wire:click="toggleMenu({{ $childMenu->id }})"
                                                    @checked(in_array($childMenu->id, $selectedMenus))
                                                    class="w-5 h-5 rounded border-zinc-300 dark:border-zinc-600 text-[#1b84ff] focus:ring-[#1b84ff] focus:ring-offset-0 cursor-pointer">
                                                <div class="flex items-center gap-2">
                                                    @if($childMenu->icon)
                                                        <span class="text-zinc-400">{{ $childMenu->icon }}</span>
                                                    @endif
                                                    <span class="text-zinc-600 dark:text-zinc-300">{{ $childMenu->name }}</span>
                                                    <code
                                                        class="text-xs bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded text-[#1b84ff] border border-zinc-200 dark:border-zinc-700">{{ $childMenu->slug }}</code>
                                                </div>
                                            </label>
                                            @if($childMenu->route)
                                                <span
                                                    class="text-xs text-zinc-500 dark:text-zinc-400 hidden sm:block">{{ $childMenu->route }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-zinc-400">
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
        <!-- Card Container for Role Selection -->
        <div
            class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
            <!-- Card Header -->
            <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-800">
                <div class="sm:flex sm:items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-zinc-900 dark:text-white">Menu Access</h1>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                            Select a role to configure its menu access permissions.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-6">
                <!-- Search -->
                <div class="mb-6">
                    <div class="relative w-full max-w-md">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-zinc-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search roles..."
                            class="block w-full pl-10 pr-3 py-2.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white text-sm rounded-lg focus:ring-2 focus:ring-[#1b84ff] focus:border-[#1b84ff] placeholder-zinc-400 dark:placeholder-zinc-500 transition-all">
                    </div>
                </div>

                <!-- Role Cards -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @forelse($roles as $role)
                        <button wire:click="selectRole({{ $role->id }})"
                            class="relative flex items-center gap-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 px-5 py-4 text-left transition-all hover:border-[#1b84ff] hover:shadow-lg hover:shadow-[#1b84ff]/10 group">

                            <div class="flex-shrink-0">
                                <div
                                    class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#1b84ff] to-[#0070f0] flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-[#1b84ff]/25">
                                    {{ strtoupper(substr($role->name, 0, 1)) }}
                                </div>
                            </div>

                            <div class="min-w-0 flex-1">
                                <p
                                    class="text-base font-bold text-zinc-900 dark:text-white group-hover:text-[#1b84ff] transition-colors">
                                    {{ $role->name }}
                                </p>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $role->slug }}</p>
                                <div class="mt-1">
                                    <x-ui.badge variant="info">{{ $role->users_count }} users</x-ui.badge>
                                </div>
                            </div>

                            <div class="text-zinc-400 group-hover:text-[#1b84ff] transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                            </div>
                        </button>
                    @empty
                        <div class="col-span-full">
                            <div class="flex flex-col items-center justify-center py-12 text-zinc-400">
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
            </div>
        </div>
    @endif
</div>