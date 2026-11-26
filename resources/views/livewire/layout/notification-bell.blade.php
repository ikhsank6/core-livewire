{{--
    Header bell: the five latest unread notifications of the active role.
    Same anatomy as the account menu (plain disclosure, Esc closes). Nothing pulses or rings: the
    count on the bell is the signal.
--}}
@php
    $unread = $this->unreadCount;
    $focus = 'focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-[#2D68A7] dark:focus-visible:outline-[#4BA5DA]';
    $link = 'text-[#245A94] dark:text-[#7FC1EA]';
@endphp

<div class="relative" x-data="{ open: false }"
     @click.outside="open = false"
     @keydown.escape="if (open) { open = false; $refs.bell.focus() }">

    <button type="button" x-ref="bell" @click="open = !open"
            aria-controls="notification-panel" :aria-expanded="open ? 'true' : 'false'"
            aria-label="{{ $unread > 0 ? "Notifikasi, {$unread} belum dibaca" : 'Notifikasi' }}"
            class="relative grid h-11 w-11 place-items-center rounded-lg text-white/85 transition-colors cursor-pointer hover:bg-white/10 hover:text-white focus-visible:outline-2 focus-visible:outline-white">
        <flux:icon name="bell" class="h-5 w-5" />
        @if($unread > 0)
            <span class="absolute right-1 top-1 grid h-[1.125rem] min-w-[1.125rem] place-items-center rounded-full bg-red-600 px-1 text-[0.6875rem] font-bold leading-none tabular-nums text-white ring-2 ring-[#1e3050]" aria-hidden="true">
                {{ $unread > 9 ? '9+' : $unread }}
            </span>
        @endif
    </button>

    {{-- Phones: pinned to the screen edges, the bell is not at the right edge there --}}
    <div id="notification-panel" x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-150 motion-reduce:transition-none"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100 motion-reduce:transition-none"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-x-2 top-[4.25rem] z-50 overflow-hidden rounded-2xl border border-zinc-200/90 bg-white shadow-[0_20px_44px_-12px_rgba(15,23,42,0.3)] sm:absolute sm:inset-x-auto sm:right-0 sm:top-[calc(100%+6px)] sm:w-[24rem] dark:border-[#2d2d3a] dark:bg-[#1e1e2d]">

        {{-- Header --}}
        <div class="flex items-center justify-between gap-3 border-b border-zinc-200/80 px-4 py-3 dark:border-[#2d2d3a]">
            <div class="flex min-w-0 items-center gap-2.5">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-[#2D68A7]/10 text-[#2D68A7] dark:bg-[#4BA5DA]/15 dark:text-[#7FC1EA]" aria-hidden="true">
                    <flux:icon name="bell" variant="mini" class="h-4 w-4" />
                </span>
                <div class="min-w-0">
                    <h2 class="text-sm font-bold leading-tight text-zinc-900 dark:text-white">Notifikasi</h2>
                    @if($unread > 0)
                        <p class="mt-0.5 text-xs font-medium text-zinc-500 dark:text-zinc-400">{{ $unread }} belum dibaca</p>
                    @else
                        <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">Semua sudah dibaca</p>
                    @endif
                </div>
            </div>

            @if($unread > 0)
                <button type="button" wire:click="markAllAsRead" wire:loading.attr="disabled" wire:target="markAllAsRead"
                        class="inline-flex min-h-9 shrink-0 items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-semibold cursor-pointer transition-colors hover:bg-[#2D68A7]/10 disabled:opacity-50 dark:hover:bg-[#4BA5DA]/15 {{ $link }} {{ $focus }}">
                    <flux:icon name="check" variant="mini" class="h-3.5 w-3.5" />
                    <span>Tandai semua dibaca</span>
                </button>
            @endif
        </div>

        {{-- List --}}
        @if($this->notifications->isNotEmpty())
            <ul class="max-h-[min(22rem,60vh)] divide-y divide-zinc-100 overflow-y-auto dark:divide-[#2d2d3a]">
                @foreach($this->notifications as $notification)
                    <li wire:key="bell-{{ $notification->uuid }}">
                        <button type="button" wire:click="markAsRead('{{ $notification->uuid }}')"
                                wire:loading.class="opacity-50" wire:target="markAsRead('{{ $notification->uuid }}')"
                                class="group flex w-full items-start gap-3 px-4 py-3.5 text-left transition-colors cursor-pointer hover:bg-zinc-50 dark:hover:bg-white/[0.04] {{ $focus }}">
                            <span class="mt-0.5 grid h-8 w-8 shrink-0 place-items-center rounded-xl bg-zinc-100 text-zinc-600 transition-colors group-hover:bg-[#2D68A7]/10 group-hover:text-[#2D68A7] dark:bg-white/[0.06] dark:text-zinc-300 dark:group-hover:bg-[#4BA5DA]/15 dark:group-hover:text-[#7FC1EA]" aria-hidden="true">
                                <flux:icon name="{{ $notification->url ? 'arrow-top-right-on-square' : 'inbox' }}" variant="mini" class="h-4 w-4" />
                            </span>

                            <span class="min-w-0 flex-1">
                                <span class="block text-[0.8125rem] font-medium leading-relaxed text-zinc-800 line-clamp-3 dark:text-zinc-100">{{ $notification->message }}</span>
                                <time datetime="{{ $notification->created_at->toIso8601String() }}"
                                      title="{{ $notification->created_at->locale('id')->translatedFormat('d M Y, H:i') }}"
                                      class="mt-1 inline-flex items-center gap-1 text-xs text-zinc-500 dark:text-zinc-400">
                                    <flux:icon name="clock" variant="mini" class="h-3.5 w-3.5 opacity-70" />
                                    <span>{{ $notification->created_at->locale('id')->diffForHumans() }}</span>
                                </time>
                            </span>

                            <span class="mt-0.5 grid h-7 w-7 shrink-0 place-items-center rounded-full border border-zinc-200 bg-white text-zinc-400 transition-colors group-hover:border-[#2D68A7]/30 group-hover:bg-[#2D68A7]/10 group-hover:text-[#2D68A7] dark:border-white/10 dark:bg-white/5 dark:text-zinc-400 dark:group-hover:border-[#4BA5DA]/30 dark:group-hover:bg-[#4BA5DA]/15 dark:group-hover:text-[#7FC1EA]"
                                  title="{{ $notification->url ? 'Buka' : 'Tandai dibaca' }}">
                                <flux:icon name="{{ $notification->url ? 'arrow-right' : 'check' }}" variant="mini" class="h-3.5 w-3.5" />
                                <span class="sr-only">{{ $notification->url ? 'Buka' : 'Tandai dibaca' }}</span>
                            </span>
                        </button>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="px-4 py-8 text-center">
                <span class="mx-auto mb-2.5 grid h-11 w-11 place-items-center rounded-full bg-zinc-100 text-zinc-400 dark:bg-white/5 dark:text-zinc-500" aria-hidden="true">
                    <flux:icon name="bell-slash" variant="mini" class="h-5 w-5" />
                </span>
                <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-200">Tidak ada notifikasi baru.</p>
                <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">Semua pemberitahuan sudah Anda baca.</p>
            </div>
        @endif

        {{-- Footer --}}
        <a href="{{ route('notifications.index') }}"
           class="flex min-h-11 items-center justify-center gap-1.5 border-t border-zinc-200/80 bg-zinc-50/70 text-xs font-bold transition-colors hover:bg-zinc-100 dark:border-[#2d2d3a] dark:bg-white/[0.02] dark:hover:bg-white/5 {{ $link }} {{ $focus }}">
            <span>Lihat semua notifikasi</span>
            <flux:icon name="arrow-right" variant="mini" class="h-3.5 w-3.5" />
        </a>
    </div>
</div>
