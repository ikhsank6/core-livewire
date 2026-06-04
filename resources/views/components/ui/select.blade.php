{{--
  Global Autocomplete Select Component

  Single:   <x-ui.select model="filterStatus" :options="[...]" placeholder="Semua Status" />
  Multiple: <x-ui.select model="pendingRoles" :options="$roles" :multiple="true" />
  Custom:   <x-ui.select model="cat" :options="$cats" value-key="slug" label-key="title" />
--}}
@props([
    'model'       => '',
    'options'     => [],
    'multiple'    => false,
    'placeholder' => 'Pilih...',
    'searchable'  => true,
    'valueKey'    => 'id',
    'labelKey'    => 'name',
    'disabled'    => false,
])

@php
    $opts = collect($options)->map(function ($opt) use ($valueKey, $labelKey) {
        if (is_array($opt))  return ['value' => (string)($opt['value'] ?? $opt[$valueKey] ?? ''), 'label' => (string)($opt['label'] ?? $opt[$labelKey] ?? '')];
        if (is_object($opt)) return ['value' => (string)($opt->{$valueKey} ?? ''), 'label' => (string)($opt->{$labelKey} ?? '')];
        return ['value' => '', 'label' => ''];
    })->values()->toJson(JSON_UNESCAPED_UNICODE);
@endphp

<div x-data="{
        open: false,
        search: '',
        multiple: {{ $multiple ? 'true' : 'false' }},
        allOptions: {{ $opts }},

        get val() {
            const v = $wire['{{ $model }}'];
            if (this.multiple) return Array.isArray(v) ? v.map(String) : (v ? [String(v)] : []);
            return (v !== null && v !== undefined && v !== '') ? String(v) : '';
        },
        set val(v) { $wire['{{ $model }}'] = v; },

        get filtered() {
            if (!this.search) return this.allOptions;
            const q = this.search.toLowerCase();
            return this.allOptions.filter(o => o.label.toLowerCase().includes(q));
        },

        get selectedOptions() {
            return this.allOptions.filter(o =>
                this.multiple ? this.val.includes(String(o.value)) : String(o.value) === this.val
            );
        },

        get hasValue() { return this.multiple ? this.val.length > 0 : this.val !== ''; },

        isSelected(value) {
            return this.multiple ? this.val.includes(String(value)) : this.val === String(value);
        },

        toggle(value) {
            const v = String(value);
            if (this.multiple) {
                const cur = [...this.val];
                const i = cur.indexOf(v);
                i >= 0 ? cur.splice(i, 1) : cur.push(v);
                this.val = cur;
            } else {
                this.val = this.val === v ? '' : v;
                this.open = false;
                this.search = '';
            }
        },

        remove(value, e) { e && e.stopPropagation(); this.val = this.val.filter(v => v !== String(value)); },
        clear(e)  { e && e.stopPropagation(); this.val = this.multiple ? [] : ''; this.search = ''; },
        close()   { this.open = false; this.search = ''; }
    }"
    @click.outside="close()"
    class="relative w-full"
>

    {{-- ── Trigger ── --}}
    <button
        type="button"
        @click="{{ $disabled ? '' : 'open = !open' }}"
        @keydown.escape="close()"
        :disabled="{{ $disabled ? 'true' : 'false' }}"
        :class="open ? 'border-indigo-400 ring-2 ring-indigo-400/20' : 'hover:border-zinc-300 dark:hover:border-zinc-600'"
        class="relative w-full min-h-10 flex items-center gap-1.5 pl-3 py-2 text-left
               bg-white dark:bg-zinc-900
               border border-zinc-200 dark:border-zinc-700
               rounded-xl transition-all duration-150 select-none
               focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20
               disabled:opacity-50 disabled:cursor-not-allowed"
        style="padding-right: 2.25rem;"
    >
        {{-- Content --}}
        <span class="flex flex-wrap items-center gap-1 flex-1 min-w-0 pointer-events-none">

            {{-- Multiple + has values → badges --}}
            <template x-if="multiple && val.length > 0">
                <span class="flex flex-wrap gap-1">
                    <template x-for="opt in selectedOptions" :key="opt.value">
                        <span class="inline-flex items-center gap-1 pl-2 pr-1 py-0.5 rounded-md
                                     bg-indigo-50 dark:bg-indigo-900/40
                                     text-indigo-700 dark:text-indigo-300
                                     border border-indigo-200 dark:border-indigo-700
                                     text-xs font-medium leading-none pointer-events-auto">
                            <span x-text="opt.label" class="leading-none"></span>
                            <span @click="remove(opt.value, $event)"
                                  class="w-3.5 h-3.5 flex items-center justify-center rounded
                                         hover:bg-indigo-200 dark:hover:bg-indigo-700
                                         text-indigo-400 hover:text-indigo-700 transition-colors cursor-pointer">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </span>
                        </span>
                    </template>
                </span>
            </template>

            {{-- Multiple + empty → placeholder --}}
            <template x-if="multiple && val.length === 0">
                <span class="text-sm text-zinc-400 dark:text-zinc-500">{{ $placeholder }}</span>
            </template>

            {{-- Single + has value → label --}}
            <template x-if="!multiple && hasValue">
                <span class="text-sm text-zinc-800 dark:text-zinc-200 truncate"
                      x-text="selectedOptions[0]?.label ?? ''"></span>
            </template>

            {{-- Single + empty → placeholder --}}
            <template x-if="!multiple && !hasValue">
                <span class="text-sm text-zinc-400 dark:text-zinc-500">{{ $placeholder }}</span>
            </template>

        </span>

        {{-- Right icon: clear OR chevron (never both) --}}
        <span class="absolute right-0 top-0 bottom-0 w-9 flex items-center justify-center pointer-events-none">

            {{-- Clear button — shown only when has value --}}
            <span x-show="hasValue"
                  @click="clear($event)"
                  class="w-5 h-5 flex items-center justify-center rounded-md
                         text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200
                         hover:bg-zinc-100 dark:hover:bg-zinc-800
                         transition-colors cursor-pointer pointer-events-auto"
                  title="Hapus pilihan">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </span>

            {{-- Chevron — shown only when no value (or always for multiple) --}}
            <span x-show="!hasValue || multiple"
                  :class="open ? 'rotate-180' : 'rotate-0'"
                  class="w-5 h-5 flex items-center justify-center
                         text-zinc-400 dark:text-zinc-500
                         transition-transform duration-200 pointer-events-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </span>

        </span>
    </button>

    {{-- ── Dropdown ── --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1"
         style="display:none"
         class="absolute top-full left-0 right-0 z-50 mt-1.5
                bg-white dark:bg-zinc-900
                border border-zinc-200 dark:border-zinc-700
                rounded-xl shadow-lg shadow-black/10 dark:shadow-black/30
                overflow-hidden">

        {{-- Search header (seamless, command-palette style) --}}
        @if($searchable)
        <div class="flex items-center gap-2.5 px-3 border-b border-zinc-100 dark:border-zinc-800"
             x-init="$watch('open', v => v && $nextTick(() => $refs.search?.focus()))">
            <svg class="w-4 h-4 text-zinc-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input x-model="search"
                   x-ref="search"
                   @click.stop
                   @keydown.escape="close()"
                   type="text"
                   placeholder="Cari..."
                   class="w-full py-2.5 text-sm bg-transparent border-0 outline-none
                          text-zinc-800 dark:text-zinc-200
                          placeholder:text-zinc-400 dark:placeholder:text-zinc-500
                          focus:ring-0 focus:outline-none">
        </div>
        @endif

        {{-- Options list --}}
        <div class="max-h-52 overflow-y-auto overscroll-contain py-1">

            <p x-show="filtered.length === 0"
               class="px-3 py-4 text-center text-sm text-zinc-400 dark:text-zinc-500">
                Tidak ada hasil
            </p>

            <template x-for="opt in filtered" :key="opt.value">
                <div @click="toggle(opt.value)"
                     :class="isSelected(opt.value)
                         ? 'bg-indigo-50 dark:bg-indigo-900/25 text-indigo-700 dark:text-indigo-300'
                         : 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800'"
                     class="flex items-center gap-2.5 px-3 py-2.5 cursor-pointer select-none transition-colors text-sm">

                    {{-- Checkbox (multiple only) --}}
                    <span x-show="multiple"
                          :class="isSelected(opt.value)
                              ? 'bg-indigo-600 border-indigo-600'
                              : 'border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900'"
                          class="w-4 h-4 rounded shrink-0 border-2 flex items-center justify-center transition-colors">
                        <svg x-show="isSelected(opt.value)"
                             class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>

                    <span x-text="opt.label" class="font-medium leading-none flex-1"></span>

                    {{-- Checkmark (single only, right side) --}}
                    <span x-show="!multiple && isSelected(opt.value)"
                          class="text-indigo-600 dark:text-indigo-400 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>

                </div>
            </template>
        </div>

        {{-- Footer: clear all (multiple only) --}}
        <template x-if="multiple && val.length > 0">
            <div class="flex items-center justify-between px-3 py-2 border-t border-zinc-100 dark:border-zinc-800">
                <span class="text-xs text-zinc-400 dark:text-zinc-500">
                    <span x-text="val.length"></span> dipilih
                </span>
                <button @click.stop="clear($event)" type="button"
                        class="text-xs font-medium text-red-500 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                    Hapus semua
                </button>
            </div>
        </template>

    </div>
</div>
