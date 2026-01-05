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
                    <flux:tooltip content="Tambah User Baru" position="top">
                        <button type="button" wire:click="create"
                            class="flex items-center gap-2 rounded-lg bg-metronic-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:opacity-90 transition-all active:scale-95 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-metronic-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Add User
                        </button>
                    </flux:tooltip>
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <x-ui.table :paginator="$users" :view="$view">
                <x-slot name="header">
                    <x-ui.table.header search="search" :showFilters="false" :showBulk="false" :showColumns="false"
                        :showViewToggle="true" />
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
                                    <x-ui.avatar :name="$user->name" :src="$user->avatar ? Storage::url($user->avatar) : null" size="md" />
                                    <div class="flex flex-col">
                                        <span class="font-bold text-zinc-900 dark:text-white">{{ $user->name }}</span>
                                        <span class="text-xs text-zinc-500 dark:text-zinc-400">Indonesia/Jakarta</span>
                                    </div>
                                </div>
                            </x-ui.table.td>
                            <x-ui.table.td>
                                <a href="mailto:{{ $user->email }}"
                                    class="text-metronic-primary hover:opacity-80 transition-colors">{{ $user->email }}</a>
                            </x-ui.table.td>
                            <x-ui.table.td>
                                <x-ui.badge :variant="$user->is_active ? 'success' : 'danger'">
                                    {{ $user->is_active ? 'Yes' : 'No' }}
                                </x-ui.badge>
                            </x-ui.table.td>
                            <x-ui.table.td shrink>
                                <div class="flex items-center justify-end gap-2 text-right">
                                    @if(!$user->email_verified_at)
                                        <flux:tooltip content="Resend Activation Email" position="top">
                                            <button wire:click="resendActivation('{{ $user->uuid }}')"
                                                wire:loading.attr="disabled" wire:target="resendActivation('{{ $user->uuid }}')"
                                                class="p-2 text-zinc-400 hover:text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-all active:scale-90 disabled:opacity-50">
                                                <svg wire:loading.remove wire:target="resendActivation('{{ $user->uuid }}')"
                                                    class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                <svg wire:loading wire:target="resendActivation('{{ $user->uuid }}')"
                                                    class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                        stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </flux:tooltip>
                                    @endif

                                    <flux:tooltip content="Edit Data User" position="top">
                                        <button wire:click="edit('{{ $user->uuid }}')"
                                            class="p-2 text-zinc-400 hover:text-metronic-primary hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-all active:scale-90">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                    </flux:tooltip>

                                    <flux:tooltip content="Hapus Data User" position="top">
                                        <button x-on:click="$dispatch('open-delete-confirm', { 
                                                                id: '{{ $user->uuid }}', 
                                                                componentId: '{{ $this->getId() }}',
                                                                message: 'Apakah Anda yakin ingin menghapus user {{ $user->name }}?'
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

                <x-slot name="board">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($users as $user)
                            <div
                                class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 hover:shadow-md transition-all group">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <x-ui.avatar :name="$user->name" :src="$user->avatar ? Storage::url($user->avatar) : null" size="lg" />
                                        <div>
                                            <h3 class="font-bold text-zinc-900 dark:text-white">{{ $user->name }}</h3>
                                            <p class="text-xs text-zinc-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <x-ui.badge :variant="strtolower($user->role->name ?? 'user') === 'admin' ? 'admin' : 'user'">
                                        {{ $user->role->name ?? 'User' }}
                                    </x-ui.badge>
                                </div>
                                <div class="space-y-3 mb-5">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-zinc-500">Status</span>
                                        <x-ui.badge :variant="$user->is_active ? 'success' : 'danger'" class="px-1.5 py-0">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </x-ui.badge>
                                    </div>
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-zinc-500">Joined</span>
                                        <span
                                            class="text-zinc-700 dark:text-zinc-300">{{ $user->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                <div
                                    class="flex items-center justify-end gap-2 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                                    <flux:tooltip content="Edit User" position="top">
                                        <button wire:click="edit('{{ $user->uuid }}')"
                                            class="p-2 text-zinc-400 hover:text-metronic-primary hover:bg-zinc-50 dark:hover:bg-zinc-800 rounded-lg transition-colors">
                                            <flux:icon name="pencil-square" variant="mini" class="w-4 h-4" />
                                        </button>
                                        </flux:tooltip>
                                        <flux:tooltip content="Hapus User" position="top">
                                            <button x-on:click="$dispatch('open-delete-confirm', { 
                                                                id: '{{ $user->uuid }}', 
                                                            componentId: '{{ $this->getId() }}',
                                                            message: 'Hapus user {{ $user->name }}?'
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
                    <x-ui.pagination :paginator="$users" />
                </x-slot>
            </x-ui.table>
        </div>
    </div>

    <!-- Edit/Create Modal -->
    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit User' : 'Create User'" formId="user-form">
        <form wire:submit="save" id="user-form" novalidate>
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>