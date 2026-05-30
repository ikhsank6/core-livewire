@props(['paginator', 'perPage' => null])

<div class="flex items-center justify-between gap-4 py-3">
    {{-- Left: Per-page selector --}}
    <div class="shrink-0">
        @if ($perPage)
            <flux:select wire:model.live="{{ $perPage }}" size="sm" class="min-w-[130px]!">
                <flux:select.option value="10">10/halaman</flux:select.option>
                <flux:select.option value="25">25/halaman</flux:select.option>
                <flux:select.option value="50">50/halaman</flux:select.option>
                <flux:select.option value="100">100/halaman</flux:select.option>
            </flux:select>
        @endif
    </div>

    {{-- Right: showing info + page buttons + go-to --}}
    <div class="flex items-center gap-4">
        {{-- Showing info --}}
        <span class="text-sm text-zinc-500 dark:text-zinc-400 whitespace-nowrap">
            <span class="font-bold text-zinc-900 dark:text-white">{{ $paginator->firstItem() ?? 0 }}</span>
            <span class="mx-0.5">–</span>
            <span class="font-bold text-zinc-900 dark:text-white">{{ $paginator->lastItem() ?? 0 }}</span>
            <span class="mx-1">dari</span>
            <span class="font-bold text-zinc-900 dark:text-white">{{ $paginator->total() }}</span>
        </span>

        {{-- Page buttons --}}
        <div class="flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <span class="flex items-center justify-center w-8 h-8 rounded text-zinc-300 dark:text-zinc-600 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </span>
            @else
                <button wire:click="previousPage"
                    class="flex items-center justify-center w-8 h-8 rounded text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
            @endif

            @foreach($paginator->getUrlRange(1, min($paginator->lastPage(), 5)) as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-metronic-primary text-white font-bold text-xs">{{ $page }}</span>
                @else
                    <button wire:click="gotoPage({{ $page }})"
                        class="flex items-center justify-center w-8 h-8 rounded-full text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors text-xs font-medium">{{ $page }}</button>
                @endif
            @endforeach

            @if ($paginator->lastPage() > 5)
                <span class="px-1 text-zinc-400 text-xs">...</span>
                <button wire:click="gotoPage({{ $paginator->lastPage() }})"
                    class="flex items-center justify-center w-8 h-8 rounded-full text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors text-xs font-medium">{{ $paginator->lastPage() }}</button>
            @endif

            @if ($paginator->hasMorePages())
                <button wire:click="nextPage"
                    class="flex items-center justify-center w-8 h-8 rounded text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            @else
                <span class="flex items-center justify-center w-8 h-8 rounded text-zinc-300 dark:text-zinc-600 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            @endif
        </div>

        {{-- Separator --}}
        <div class="w-px h-5 bg-zinc-200 dark:bg-zinc-700"></div>

        {{-- Go to page --}}
        <div class="flex items-center gap-2" x-data="{ jumpPage: '' }">
            <span class="text-sm text-zinc-500 dark:text-zinc-400 whitespace-nowrap">Pergi ke</span>
            <input
                type="number"
                x-model="jumpPage"
                min="1"
                max="{{ $paginator->lastPage() }}"
                placeholder="{{ $paginator->currentPage() }}"
                @keydown.enter="
                    const page = parseInt(jumpPage);
                    if (page >= 1 && page <= {{ $paginator->lastPage() }}) {
                        $wire.gotoPage(page);
                    } else {
                        jumpPage = '';
                        $wire.gotoPage(1);
                    }
                "
                class="w-10 h-8 px-1 text-center text-sm font-medium rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white placeholder:text-zinc-400 focus:outline-none focus:ring-1 focus:ring-blue-500 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
            >
        </div>
    </div>
</div>
