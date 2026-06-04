<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Beranda</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Pengguna</flux:breadcrumbs.item>
</x-slot>
<div>
    <x-ui.card title="Pengguna" description="Kelola semua pengguna dan hak akses">

        <x-slot name="headerAction">
            <x-ui.button.add label="Tambah" tooltip="Tambah User Baru" />
        </x-slot>

        <x-ui.table :paginator="$users" :view="$view">
            <x-slot name="header">
                <x-ui.table.header
                    search="search"
                    searchPlaceholder="Cari nama atau email..."
                    :showFilters="false"
                    :showBulk="false"
                    :showColumns="false"
                    :showPageSize="false"
                    :showReload="true"
                    :showViewToggle="true">

                    <x-slot name="searchAction">
                        {{-- Filter button --}}
                        <button wire:click="openFilterModal"
                                class="relative inline-flex items-center gap-2 h-10 px-3 text-sm font-semibold rounded-xl border border-zinc-200 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-600 transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                            </svg>
                            Filter
                            @if($this->activeFilterCount > 0)
                                <span class="absolute -top-1.5 -right-1.5 flex items-center justify-center w-5 h-5 rounded-full bg-indigo-600 text-white text-[10px] font-bold">
                                    {{ $this->activeFilterCount }}
                                </span>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="extraActions">
                        {{-- Bulk resend button — muncul saat ada pilihan --}}
                        <div x-show="$wire.selectedUsers.length > 0"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             style="display:none"
                             class="flex items-center gap-2">

                            {{-- Count badge --}}
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-xs font-semibold border border-indigo-200 dark:border-indigo-700">
                                <span x-text="$wire.selectedUsers.length"></span>
                                <span>dipilih</span>
                            </span>

                            {{-- Kirim Verifikasi --}}
                            <flux:button
                                wire:click="bulkResendActivation"
                                wire:loading.attr="disabled"
                                wire:target="bulkResendActivation"
                                icon="envelope"
                                variant="primary"
                                class="h-10! px-4! rounded-xl! text-sm! font-semibold! shadow-sm!">
                                <span wire:loading.remove wire:target="bulkResendActivation">Kirim Verifikasi</span>
                                <span wire:loading wire:target="bulkResendActivation">Mengirim...</span>
                            </flux:button>

                            {{-- Batalkan pilihan --}}
                            <flux:button
                                wire:click="clearSelection"
                                icon="x-mark"
                                variant="ghost"
                                class="h-10! w-10! rounded-xl! border border-zinc-200! dark:border-zinc-700!"
                                title="Batalkan pilihan">
                            </flux:button>
                        </div>
                    </x-slot>
                </x-ui.table.header>
            </x-slot>

            <x-ui.table.thead>
                {{-- Checkbox select-all --}}
                <th class="w-10 px-4 py-3">
                    <input type="checkbox"
                           wire:model.live="selectAll"
                           class="w-4 h-4 rounded border-zinc-300 dark:border-zinc-600 text-indigo-600 dark:bg-zinc-800 cursor-pointer focus:ring-2 focus:ring-indigo-500 focus:ring-offset-0 transition-colors"
                           title="Pilih semua belum terverifikasi">
                </th>
                <x-ui.table.th>Nama</x-ui.table.th>
                <x-ui.table.th>Email</x-ui.table.th>
                <x-ui.table.th>Role</x-ui.table.th>
                <x-ui.table.th>Status</x-ui.table.th>
                <x-ui.table.th>Bergabung</x-ui.table.th>
                <x-ui.table.th shrink>Actions</x-ui.table.th>
            </x-ui.table.thead>

            <x-ui.table.tbody>
                @forelse($users as $user)
                    @php
                        $roleName = $user->role->name ?? 'User';
                        $roleVariant = in_array(strtolower($roleName), ['admin', 'super admin', 'superadmin', 'super-admin'])
                            ? 'admin'
                            : 'user';

                        if ($user->is_active && $user->email_verified_at) {
                            $statusVariant = 'info';
                            $statusLabel = 'Aktif';
                        } elseif (! $user->is_active) {
                            $statusVariant = 'danger';
                            $statusLabel = 'Ditangguhkan';
                        } else {
                            $statusVariant = 'pending';
                            $statusLabel = 'Pending';
                        }
                    @endphp
                    <x-ui.table.tr>
                        {{-- Row checkbox --}}
                        <td class="w-10 px-4 py-3">
                            <input type="checkbox"
                                   wire:model.live="selectedUsers"
                                   value="{{ $user->uuid }}"
                                   @if($user->email_verified_at) disabled @endif
                                   @class([
                                       'w-4 h-4 rounded border-zinc-300 dark:border-zinc-600 text-indigo-600 dark:bg-zinc-800 transition-colors focus:ring-2 focus:ring-indigo-500 focus:ring-offset-0',
                                       'cursor-pointer' => !$user->email_verified_at,
                                       'opacity-50 cursor-not-allowed bg-zinc-100 dark:bg-zinc-700 dark:disabled:bg-zinc-800' => $user->email_verified_at,
                                   ])>
                        </td>
                        <x-ui.table.td>
                            <div class="flex items-center gap-3">
                                <x-ui.avatar :name="$user->name" :src="$user->avatar ? Storage::url($user->avatar) : null" size="md" />
                                <span class="font-semibold text-zinc-900 dark:text-white">{{ $user->name }}</span>
                            </div>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $user->email }}
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <div class="flex flex-col gap-1">
                                <x-ui.badge :variant="$roleVariant">{{ $roleName }}</x-ui.badge>
                                @if($user->roles->count() > 1)
                                    <span class="text-[10px] text-zinc-500 dark:text-zinc-400 font-medium">
                                        +{{ $user->roles->count() - 1 }} lainnya
                                    </span>
                                @endif
                            </div>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge :variant="$statusVariant">{{ $statusLabel }}</x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            {{ $user->created_at->format('j M Y') }}
                        </x-ui.table.td>
                        <x-ui.table.td shrink>
                            <div class="flex items-center justify-end gap-1">
                                @if(!$user->email_verified_at)
                                    <x-ui.button.resend-activation :uuid="$user->uuid" />
                                @endif
                                <x-ui.button.view icon="eye" wire:click="edit('{{ $user->uuid }}')" x-on:click="$dispatch('crud-modal-open')" tooltip="Lihat Detail User" />
                                <x-ui.button.edit :uuid="$user->uuid" tooltip="Edit Data User" />
                                <x-ui.button.delete :uuid="$user->uuid" :name="$user->name" tooltip="Hapus Data User"
                                    :message="'Apakah Anda yakin ingin menghapus user ' . $user->name . '?'" />
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
                    @forelse($users as $user)
                        @php
                            $roleName = $user->role->name ?? 'User';
                            $roleVariant = in_array(strtolower($roleName), ['admin', 'super admin', 'superadmin', 'super-admin'])
                                ? 'admin'
                                : 'user';

                            if ($user->is_active && $user->email_verified_at) {
                                $statusVariant = 'info';
                                $statusLabel = 'Aktif';
                            } elseif (! $user->is_active) {
                                $statusVariant = 'danger';
                                $statusLabel = 'Ditangguhkan';
                            } else {
                                $statusVariant = 'pending';
                                $statusLabel = 'Pending';
                            }
                        @endphp
                        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 hover:shadow-md transition-all group">
                            <div class="flex items-start justify-between mb-4 gap-2">
                                <div class="flex items-center gap-3 min-w-0">
                                    <x-ui.avatar :name="$user->name" :src="$user->avatar ? Storage::url($user->avatar) : null" size="lg" class="shrink-0" />
                                    <div class="min-w-0">
                                        <h3 class="font-bold text-zinc-900 dark:text-white truncate" title="{{ $user->name }}">{{ $user->name }}</h3>
                                        <p class="text-xs text-zinc-500 truncate" title="{{ $user->email }}">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <x-ui.badge :variant="$roleVariant" class="whitespace-nowrap">{{ $roleName }}</x-ui.badge>
                                </div>
                            </div>
                            <div class="space-y-3 mb-5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-zinc-500">Status</span>
                                    <x-ui.badge :variant="$statusVariant" class="px-1.5 py-0">{{ $statusLabel }}</x-ui.badge>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-zinc-500">Bergabung</span>
                                    <span class="text-zinc-700 dark:text-zinc-300">{{ $user->created_at->format('j M Y') }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-end gap-2 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                                @if(!$user->email_verified_at)
                                    <x-ui.button.resend-activation :uuid="$user->uuid" />
                                @endif
                                <x-ui.button.view icon="eye" wire:click="edit('{{ $user->uuid }}')" x-on:click="$dispatch('crud-modal-open')" tooltip="Lihat Detail User" />
                                <x-ui.button.edit :uuid="$user->uuid" tooltip="Edit Data User" />
                                <x-ui.button.delete :uuid="$user->uuid" :name="$user->name" tooltip="Hapus Data User"
                                    :message="'Apakah Anda yakin ingin menghapus user ' . $user->name . '?'" />
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
                <x-ui.pagination :paginator="$users" perPage="perPage" />
            </x-slot>
        </x-ui.table>
    </x-ui.card>

    <!-- Filter Modal -->
    <x-ui.modal wire:model="showFilterModal" title="Filter Pengguna" :crudEvents="false">
        <div class="space-y-5 py-1">

            {{-- Status --}}
            <div>
                <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-2">Status</label>
                <x-ui.select
                    model="pendingStatus"
                    placeholder="Semua Status"
                    :options="[
                        ['value' => 'active',    'label' => 'Aktif'],
                        ['value' => 'suspended', 'label' => 'Ditangguhkan'],
                        ['value' => 'pending',   'label' => 'Pending'],
                    ]"
                />
            </div>

            {{-- Role (multiple) --}}
            <div>
                <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-2">Role</label>
                <x-ui.select
                    model="pendingRoles"
                    placeholder="Semua Role"
                    :options="$roles"
                    :multiple="true"
                />
            </div>
        </div>

        {{-- Footer --}}
        <x-slot name="footer">
            <div class="flex items-center justify-between w-full">
                {{-- Reset --}}
                <button type="button" wire:click="clearFilters"
                        class="inline-flex items-center gap-1.5 text-sm font-medium text-zinc-400 dark:text-zinc-500
                               hover:text-red-500 dark:hover:text-red-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset Filter
                </button>

                <div class="flex items-center gap-2">
                    {{-- Batal --}}
                    <button type="button" wire:click="$set('showFilterModal', false)"
                            class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium
                                   text-zinc-600 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800
                                   hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Batal
                    </button>

                    {{-- Terapkan --}}
                    <button type="button" wire:click="applyFilters"
                            class="inline-flex items-center gap-1.5 px-5 py-2 text-sm font-semibold
                                   text-white bg-indigo-600 hover:bg-indigo-700
                                   rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                        </svg>
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </x-slot>
    </x-ui.modal>

    <!-- Edit/Create Modal -->
    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit User' : 'Tambah User'" formId="user-form">
        @if($showModal)
            <form wire:submit="save" id="user-form" novalidate>
                {{ $this->form }}
            </form>
        @else
            <x-ui.modal-skeleton :rows="5" />
        @endif
    </x-ui.modal>
</div>
