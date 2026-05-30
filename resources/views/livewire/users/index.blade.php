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
                    :showViewToggle="true" />
            </x-slot>

            <x-ui.table.thead>
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
                                <x-ui.button.view icon="eye" wire:click="edit('{{ $user->uuid }}')" tooltip="Lihat Detail User" />
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
                                <x-ui.button.view icon="eye" wire:click="edit('{{ $user->uuid }}')" tooltip="Lihat Detail User" />
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

    <!-- Edit/Create Modal -->
    <x-ui.modal wire:model="showModal" :title="$record ? 'Edit User' : 'Tambah User'" formId="user-form">
        <form wire:submit="save" id="user-form" novalidate>
            {{ $this->form }}
        </form>
    </x-ui.modal>
</div>
