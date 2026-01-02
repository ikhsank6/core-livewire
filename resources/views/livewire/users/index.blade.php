<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Master Data</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Users</flux:breadcrumbs.item>
</x-slot>
<div>
    <!-- Card Container -->
    <div
        class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <!-- Card Header -->
        <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-800">
            <div class="sm:flex sm:items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-zinc-900 dark:text-white">Users</h1>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Manage your team members and their account
                        permissions.</p>
                </div>
                <div class="mt-4 sm:mt-0 sm:flex-none">
                    <button type="button" wire:click="create"
                        class="flex items-center gap-2 rounded-lg bg-[#1b84ff] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#0070f0] transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#1b84ff]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Add User
                    </button>
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <x-ui.table :paginator="$users">
                <x-slot name="header">
                    <x-ui.table.header search="search" :showFilters="false" :showBulk="false" :showColumns="false" />
                </x-slot>

                <x-ui.table.thead>
                    <x-ui.table.th>Type</x-ui.table.th>
                    <x-ui.table.th>Name</x-ui.table.th>
                    <x-ui.table.th>Email</x-ui.table.th>
                    <x-ui.table.th>Active</x-ui.table.th>
                    <x-ui.table.th shrink></x-ui.table.th>
                </x-ui.table.thead>

                <x-ui.table.tbody>
                    @forelse($users as $user)
                        <x-ui.table.tr>
                            <x-ui.table.td>
                                <div class="flex flex-col gap-1">
                                    <x-ui.badge :variant="strtolower($user->role->name ?? 'user') === 'admin' ? 'admin' : 'user'">
                                        {{ $user->role->name ?? 'User' }}
                                    </x-ui.badge>
                                    @if($user->roles->count() > 1)
                                        <span
                                            class="text-[10px] text-zinc-500 dark:text-zinc-400 font-medium">+{{ $user->roles->count() - 1 }}
                                            other
                                            roles</span>
                                    @endif
                                </div>
                            </x-ui.table.td>
                            <x-ui.table.td>
                                <div class="flex items-center gap-3">
                                    <x-ui.avatar :name="$user->name" size="md" />
                                    <div class="flex flex-col">
                                        <span class="font-bold text-zinc-900 dark:text-white">{{ $user->name }}</span>
                                        <span class="text-xs text-zinc-500 dark:text-zinc-400">Indonesia/Jakarta</span>
                                    </div>
                                </div>
                            </x-ui.table.td>
                            <x-ui.table.td>
                                <a href="mailto:{{ $user->email }}"
                                    class="text-[#1b84ff] hover:text-[#0070f0] transition-colors">{{ $user->email }}</a>
                            </x-ui.table.td>
                            <x-ui.table.td>
                                <x-ui.badge :variant="$user->is_active ? 'success' : 'danger'">
                                    {{ $user->is_active ? 'Yes' : 'No' }}
                                </x-ui.badge>
                            </x-ui.table.td>
                            <x-ui.table.td shrink>
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="edit({{ $user->id }})"
                                        class="p-2 text-zinc-400 hover:text-[#1b84ff] hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button wire:confirm="Are you sure?" wire:click="delete({{ $user->id }})"
                                        class="p-2 text-zinc-400 hover:text-[#f8285a] hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </x-ui.table.td>
                        </x-ui.table.tr>
                    @empty
                        <x-ui.table.tr>
                            <x-ui.table.td colspan="8" class="text-center py-12">
                                <div class="flex flex-col items-center justify-center text-zinc-400">
                                    <svg class="w-12 h-12 mb-4 opacity-20" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                    <p class="text-base font-medium">No users found</p>
                                    <p class="text-sm">Try adjusting your search or filters.</p>
                                </div>
                            </x-ui.table.td>
                        </x-ui.table.tr>
                    @endforelse
                </x-ui.table.tbody>

                <x-slot name="footer">
                    <x-ui.pagination :paginator="$users" />
                </x-slot>
            </x-ui.table>
        </div>
    </div>

    <!-- Modal -->
    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit User' : 'Create User'" formId="user-form">
        <form wire:submit="save" id="user-form">
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>