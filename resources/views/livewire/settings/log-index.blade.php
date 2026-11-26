<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Pengaturan</flux:breadcrumbs.item>
    <flux:breadcrumbs.item>Log Aplikasi</flux:breadcrumbs.item>
</x-slot>

@php
    $deleteLabel = $deleteIsRemoval ? 'Hapus file log' : 'Kosongkan file log';
    $deleteMessage = $selectedFile
        ? ($deleteIsRemoval
            ? "Hapus {$selectedFile->name} secara permanen?"
            : "Kosongkan isi {$selectedFile->name}? File ini milik server, jadi filenya tidak dihapus.")
        : '';
@endphp

<div>
    <x-ui.card title="Log Aplikasi" description="Lihat dan analisis file log aplikasi">
        <x-slot name="headerAction">
            <div class="flex items-center gap-2">
                <flux:dropdown>
                    <flux:button variant="ghost"
                        class="h-10! px-3! min-w-[200px] rounded-xl! border border-zinc-200! dark:border-zinc-700! text-zinc-800! dark:text-white! font-bold! bg-zinc-50/50! dark:bg-zinc-800/40! shadow-sm!"
                        icon="document-text" icon-trailing="chevron-down">
                        {{ $selectedFile?->name ?? 'Pilih File' }} ({{ $selectedFile?->sizeFormatted() ?? '0 B' }})
                    </flux:button>
                    <flux:menu class="min-w-64">
                        <flux:menu.radio.group wire:model.live="selectedFileIdentifier">
                            @foreach($files as $file)
                                <flux:menu.radio value="{{ $file->identifier }}">
                                    {{ $file->name }} ({{ $file->sizeFormatted() }})
                                </flux:menu.radio>
                            @endforeach
                        </flux:menu.radio.group>
                    </flux:menu>
                </flux:dropdown>
                <flux:button wire:click="downloadLog" icon="arrow-down-tray" size="sm" tooltip="Unduh File Log"
                    class="h-10! w-10! flex items-center justify-center rounded-xl! border border-zinc-200! dark:border-zinc-700! shadow-sm!" />
                @if($canDelete && $selectedFile)
                    <x-ui.button.delete wire:key="delete-log-{{ $selectedFile->identifier }}"
                        :uuid="$selectedFile->identifier" :message="$deleteMessage" :tooltip="$deleteLabel"
                        aria-label="{{ $deleteLabel }}"
                        class="h-10! w-10! flex items-center justify-center rounded-xl! border border-zinc-200! dark:border-zinc-700! text-red-600! dark:text-red-400! shadow-sm! cursor-pointer" />
                @endif
            </div>
        </x-slot>

        <div class="space-y-4">
            @if($tooLarge)
                <div class="flex flex-wrap items-center gap-x-4 gap-y-3 rounded-xl border border-amber-300 bg-amber-50 p-4 text-sm text-amber-900 dark:border-amber-700 dark:bg-amber-950/40 dark:text-amber-100" role="alert">
                    <flux:icon name="exclamation-triangle" variant="mini" class="w-5 h-5 shrink-0" />
                    <p class="min-w-0 flex-1">
                        <span class="font-semibold">{{ $selectedFile->name }} ({{ $selectedFile->sizeFormatted() }}) terlalu besar untuk ditampilkan.</span>
                        Pilih file lain, atau unduh untuk membacanya. Kosongkan atau rotasi file ini agar tidak terus membesar.
                    </p>
                    <button type="button" wire:click="downloadLog"
                            class="inline-flex h-10 items-center gap-2 rounded-full bg-amber-900 px-5 text-sm font-bold text-white transition-all hover:bg-amber-800 active:scale-95 cursor-pointer dark:bg-amber-200 dark:text-amber-950 dark:hover:bg-amber-100">
                        <flux:icon name="arrow-down-tray" variant="mini" class="w-4 h-4" />
                        Unduh
                    </button>
                    @if($canDelete)
                        <button type="button"
                                x-on:click="$dispatch('open-delete-confirm', { id: @js($selectedFile->identifier), componentId: @js($this->getId()), message: @js($deleteMessage) })"
                                class="inline-flex h-10 items-center gap-2 rounded-full bg-red-600 px-5 text-sm font-bold text-white transition-all hover:bg-red-700 active:scale-95 cursor-pointer">
                            <flux:icon name="trash" variant="mini" class="w-4 h-4" />
                            {{ $deleteIsRemoval ? 'Hapus' : 'Kosongkan' }}
                        </button>
                    @endif
                </div>
            @endif

            <x-ui.table :paginator="$logs">
                <x-slot name="header">
                    <x-ui.table.header search="search" searchPlaceholder="Cari pesan log..." :showFilters="false"
                        :showBulk="false" :showColumns="false" :showPageSize="false" :showViewToggle="false" />
                </x-slot>

                <x-ui.table.thead>
                    <x-ui.table.th>Waktu</x-ui.table.th>
                    <x-ui.table.th>Level</x-ui.table.th>
                    <x-ui.table.th>Env</x-ui.table.th>
                    <x-ui.table.th>Pesan</x-ui.table.th>
                </x-ui.table.thead>

                <x-ui.table.tbody>
                    @if($logs)
                        @forelse($logs as $log)
                            <x-ui.table.tr x-data="{ expanded: false }" class="align-top">
                                <x-ui.table.td class="whitespace-nowrap text-xs text-zinc-500">
                                    {{ $log->datetime?->format('Y-m-d H:i:s') }}
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    @php
                                        $severity = strtolower($log->level);
                                        $variant = match ($severity) {
                                            'error', 'critical', 'alert', 'emergency' => 'danger',
                                            'warning' => 'warning',
                                            'notice', 'info' => 'info',
                                            default => 'user',
                                        };
                                    @endphp
                                    <x-ui.badge :variant="$variant" class="uppercase text-[10px]">{{ $log->level }}</x-ui.badge>
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    <span class="text-xs font-medium text-zinc-500 italic uppercase">
                                        {{ $log->extra['environment'] ?? '-' }}
                                    </span>
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    <div class="flex flex-col gap-1">
                                        <div class="text-sm text-zinc-700 dark:text-zinc-300 break-all font-mono"
                                            :class="expanded ? '' : 'line-clamp-2'">
                                            {{ $log->message }}
                                        </div>
                                        @if(strlen($log->getOriginalText()) > 200)
                                            <button @click="expanded = !expanded"
                                                class="text-indigo-500 hover:text-indigo-400 text-[10px] font-bold uppercase text-left">
                                                <span x-show="!expanded">Lihat Full Trace</span>
                                                <span x-show="expanded" x-cloak>Sembunyikan</span>
                                            </button>
                                        @endif
                                        <div x-show="expanded" x-cloak x-data="{ tab: 'stack' }"
                                            class="mt-4 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden bg-white dark:bg-zinc-950 shadow-sm">
                                            <div
                                                class="flex items-center px-4 bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
                                                <button @click="tab = 'stack'"
                                                    :class="tab === 'stack' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'"
                                                    class="py-2.5 px-4 text-xs font-bold uppercase tracking-wider border-b-2 transition-all">
                                                    Stack Trace
                                                </button>
                                                <button @click="tab = 'raw'"
                                                    :class="tab === 'raw' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'"
                                                    class="py-2.5 px-4 text-xs font-bold uppercase tracking-wider border-b-2 transition-all">
                                                    Raw
                                                </button>
                                            </div>
                                            <div class="p-0 max-h-[500px] overflow-y-auto">
                                                <div x-show="tab === 'stack'"
                                                    class="p-4 font-mono text-[11px] leading-relaxed text-zinc-700 dark:text-zinc-400 whitespace-pre-wrap break-word italic">
                                                    {{ $log->message }}
                                                    @if(count($log->context) > 0)
                                                        <hr class="my-3 border-zinc-200 dark:border-zinc-800">
                                                        <div
                                                            class="font-bold mb-1 opacity-50 uppercase tracking-tighter text-[9px]">
                                                            Context:</div>
                                                        {{ json_encode($log->context, JSON_PRETTY_PRINT) }}
                                                    @endif
                                                </div>
                                                <div x-show="tab === 'raw'"
                                                    class="p-4 font-mono text-[11px] leading-relaxed text-zinc-600 dark:text-zinc-500 whitespace-pre-wrap break-all bg-zinc-50/50 dark:bg-zinc-900/50">
                                                    {{ $log->getOriginalText() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </x-ui.table.td>
                            </x-ui.table.tr>
                        @empty
                            <x-ui.table.tr>
                                <x-ui.table.td colspan="4">
                                    <x-ui.empty-state message="Tidak ada log yang cocok dengan pencarian Anda." />
                                </x-ui.table.td>
                            </x-ui.table.tr>
                        @endforelse
                    @else
                        <x-ui.table.tr>
                            <x-ui.table.td colspan="4">
                                <x-ui.empty-state :message="$tooLarge ? 'Isi file tidak ditampilkan.' : 'Pilih file log untuk melihat entri.'" />
                            </x-ui.table.td>
                        </x-ui.table.tr>
                    @endif
                </x-ui.table.tbody>

                @if($logs)
                    <x-slot name="footer">
                        <x-ui.pagination :paginator="$logs" perPage="perPage" />
                    </x-slot>
                @endif
            </x-ui.table>
        </div>
    </x-ui.card>
</div>