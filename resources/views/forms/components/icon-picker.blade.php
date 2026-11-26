@php
    $icons = \App\Support\HeroiconCatalog::all();
    $statePath = $getStatePath();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{
            state: $wire.$entangle(@js($statePath)),
            open: false,
            search: '',
            icons: @js($icons),
            get normalizedState() {
                if (!this.state) return '';
                return String(this.state).replace(/^heroicon-[a-z]-/, '');
            },
            get iconNames() {
                return Object.keys(this.icons);
            },
            get filteredIcons() {
                const q = this.search.trim().toLowerCase();
                if (!q) return this.iconNames;
                return this.iconNames.filter((name) => name.includes(q));
            },
            openPicker() {
                this.open = true;
                this.$nextTick(() => this.$refs.iconSearch?.focus());
            },
            closePicker() {
                this.open = false;
            },
            pick(name) {
                this.state = name;
                this.open = false;
            },
            clear() {
                this.state = null;
            },
            svgOf(name) {
                return this.icons[name] ?? '';
            }
        }"
        @keydown.escape.window="if (open) { $event.stopPropagation(); closePicker(); }"
        {{ $getExtraAttributeBag() }}
    >
        {{-- Trigger control --}}
        <div class="flex items-stretch gap-2">
            <button type="button"
                    @click="openPicker()"
                    class="flex h-10 min-w-0 flex-1 cursor-pointer items-center gap-3 rounded-lg border border-zinc-300 bg-white px-3 text-left text-sm shadow-2xs transition-colors hover:border-blue-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-white/10 dark:bg-white/5 dark:hover:border-blue-400">
                <span class="grid h-7 w-7 shrink-0 place-items-center rounded-md border border-zinc-200 bg-zinc-50 text-zinc-700 dark:border-white/10 dark:bg-white/5 dark:text-zinc-200" aria-hidden="true">
                    <template x-if="normalizedState && svgOf(normalizedState)">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" x-html="svgOf(normalizedState)"></svg>
                    </template>
                    <template x-if="!normalizedState || !svgOf(normalizedState)">
                        <svg class="h-4 w-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                        </svg>
                    </template>
                </span>

                <span class="min-w-0 flex-1 truncate font-medium"
                      :class="normalizedState ? 'text-zinc-900 dark:text-white' : 'text-zinc-400 dark:text-zinc-500'"
                      x-text="normalizedState || 'Klik untuk memilih icon...'"></span>

                <span class="shrink-0 rounded-md bg-zinc-100 px-2.5 py-1 text-xs font-semibold text-zinc-700 dark:bg-white/10 dark:text-zinc-200">
                    Pilih Icon
                </span>
            </button>

            <button type="button"
                    x-show="normalizedState"
                    x-cloak
                    @click="clear()"
                    title="Hapus icon"
                    aria-label="Hapus icon"
                    class="inline-flex h-10 w-10 shrink-0 cursor-pointer items-center justify-center rounded-lg border border-zinc-300 bg-white text-zinc-500 transition-colors hover:border-red-300 hover:bg-red-50 hover:text-red-600 dark:border-white/10 dark:bg-white/5 dark:text-zinc-400 dark:hover:border-red-500/40 dark:hover:bg-red-950/30 dark:hover:text-red-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Teleported Icon Picker Pop-up --}}
        <template x-teleport="body">
            <div x-show="open"
                 x-cloak
                 wire:ignore
                 class="fixed inset-0 z-[80] flex items-center justify-center p-4 sm:p-6"
                 role="dialog"
                 aria-modal="true"
                 aria-label="Pilih Icon Menu">

                {{-- Backdrop --}}
                <div x-show="open"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="closePicker()"
                     class="fixed inset-0 bg-black/60 backdrop-blur-xs"></div>

                {{-- Dialog Panel --}}
                <div x-show="open"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                     @click.stop
                     class="relative flex max-h-[82vh] w-full max-w-2xl flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-2xl dark:border-[#2d2d3a] dark:bg-[#1e1e2d]">

                    {{-- Header --}}
                    <div class="flex items-center justify-between gap-4 border-b border-zinc-200 px-5 py-4 dark:border-[#2d2d3a]">
                        <div>
                            <h3 class="text-base font-bold text-zinc-900 dark:text-white">Pilih Icon Menu</h3>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                Menampilkan <span class="font-semibold text-zinc-700 dark:text-zinc-200" x-text="filteredIcons.length"></span> dari <span x-text="iconNames.length"></span> icon
                            </p>
                        </div>
                        <button type="button"
                                @click="closePicker()"
                                aria-label="Tutup pop-up"
                                class="rounded-full p-2 text-zinc-400 transition-colors hover:bg-zinc-100 hover:text-zinc-700 dark:hover:bg-zinc-800 dark:hover:text-white cursor-pointer">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Search Bar & Quick Tags --}}
                    <div class="space-y-2.5 border-b border-zinc-200 bg-zinc-50/70 px-5 py-3.5 dark:border-[#2d2d3a] dark:bg-zinc-900/40">
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-zinc-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                            </span>
                            <input x-ref="iconSearch"
                                   type="text"
                                   x-model="search"
                                   placeholder="Cari icon... (contoh: home, user, document, cog, folder)"
                                   class="h-10 w-full rounded-xl border border-zinc-300 bg-white pl-9 pr-9 text-sm text-zinc-900 placeholder:text-zinc-400 focus:border-blue-500 focus:outline-none dark:border-zinc-700 dark:bg-[#1e1e2d] dark:text-white dark:placeholder:text-zinc-500">
                            <button type="button"
                                    x-show="search.length > 0"
                                    @click="search = ''; $refs.iconSearch?.focus()"
                                    aria-label="Hapus pencarian"
                                    class="absolute inset-y-0 right-2.5 flex items-center text-zinc-400 hover:text-zinc-700 dark:hover:text-white cursor-pointer">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="flex flex-wrap items-center gap-1.5">
                            <span class="mr-1 text-[11px] font-semibold text-zinc-500 dark:text-zinc-400">Cepat:</span>
                            @foreach(['' => 'Semua', 'home' => 'Home', 'user' => 'User', 'document' => 'Document', 'folder' => 'Folder', 'cog' => 'Setting', 'chart' => 'Chart', 'shield' => 'Shield', 'bell' => 'Bell'] as $keyword => $tagLabel)
                                <button type="button"
                                        @click="search = @js($keyword)"
                                        class="cursor-pointer rounded-full border px-2.5 py-0.5 text-[11px] font-semibold transition-colors"
                                        :class="search.trim().toLowerCase() === @js($keyword)
                                            ? 'border-blue-600 bg-blue-600 text-white'
                                            : 'border-zinc-200 bg-white text-zinc-600 hover:border-zinc-300 hover:text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:text-white'">
                                    {{ $tagLabel }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Grid --}}
                    <div class="style-scrollbar flex-1 overflow-y-auto p-4 sm:p-5">
                        <template x-if="filteredIcons.length === 0">
                            <div class="py-12 text-center">
                                <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Icon tidak ditemukan.</p>
                                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Coba kata kunci lain dalam bahasa Inggris.</p>
                                <button type="button"
                                        @click="search = ''"
                                        class="mt-3 cursor-pointer rounded-full border border-zinc-200 px-4 py-1.5 text-xs font-semibold text-zinc-700 hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800">
                                    Tampilkan semua icon
                                </button>
                            </div>
                        </template>

                        <div x-show="filteredIcons.length > 0" class="grid grid-cols-3 gap-2 sm:grid-cols-5 md:grid-cols-6">
                            <template x-for="name in filteredIcons" :key="name">
                                <button type="button"
                                        @click="pick(name)"
                                        :title="name"
                                        class="group flex cursor-pointer flex-col items-center justify-center gap-1.5 rounded-xl border p-2.5 transition-all hover:-translate-y-0.5"
                                        :class="normalizedState === name
                                            ? 'border-blue-600 bg-blue-50 text-blue-700 ring-2 ring-blue-600/20 dark:border-blue-400 dark:bg-blue-950/50 dark:text-blue-300'
                                            : 'border-zinc-200 bg-white text-zinc-700 hover:border-blue-400 hover:bg-blue-50/40 hover:text-blue-600 dark:border-zinc-700/70 dark:bg-zinc-800/40 dark:text-zinc-300 dark:hover:border-blue-500/60 dark:hover:bg-blue-950/20 dark:hover:text-blue-400'">
                                    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" x-html="svgOf(name)"></svg>
                                    <span class="w-full truncate text-center text-[11px] font-medium" x-text="name"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="flex items-center justify-between gap-3 border-t border-zinc-200 bg-zinc-50/70 px-5 py-3 dark:border-[#2d2d3a] dark:bg-zinc-900/40">
                        <div class="flex min-w-0 items-center gap-2 text-xs text-zinc-600 dark:text-zinc-300">
                            <template x-if="normalizedState && svgOf(normalizedState)">
                                <span class="inline-flex items-center gap-2 truncate">
                                    <span class="grid h-7 w-7 shrink-0 place-items-center rounded-md border border-blue-200 bg-blue-50 text-blue-600 dark:border-blue-800 dark:bg-blue-950/50 dark:text-blue-300">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" x-html="svgOf(normalizedState)"></svg>
                                    </span>
                                    <span>Dipilih: <strong class="font-semibold text-zinc-900 dark:text-white" x-text="normalizedState"></strong></span>
                                </span>
                            </template>
                            <template x-if="!normalizedState">
                                <span class="text-zinc-400 dark:text-zinc-500">Belum ada icon dipilih</span>
                            </template>
                        </div>

                        <div class="flex shrink-0 items-center gap-2">
                            <button type="button"
                                    x-show="normalizedState"
                                    @click="clear()"
                                    class="cursor-pointer rounded-full border border-zinc-200 bg-white px-4 py-1.5 text-xs font-bold text-zinc-600 transition-colors hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700">
                                Kosongkan
                            </button>
                            <button type="button"
                                    @click="closePicker()"
                                    class="cursor-pointer rounded-full bg-blue-600 px-4 py-1.5 text-xs font-bold text-white transition-colors hover:bg-blue-700">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-dynamic-component>
