@props(['paginator'])

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-4">
    <div class="text-xs font-medium text-[#99a1b7] dark:text-[#6d6d80]">
        Showing <span class="text-[#1b1c22] dark:text-white font-bold">{{ $paginator->firstItem() ?? 0 }}</span> to
        <span class="text-[#1b1c22] dark:text-white font-bold">{{ $paginator->lastItem() ?? 0 }}</span> of <span
            class="text-[#1b1c22] dark:text-white font-bold">{{ $paginator->total() }}</span> results
    </div>

    <div class="flex items-center gap-1">
        @if ($paginator->onFirstPage())
            <span
                class="flex items-center justify-center w-8 h-8 rounded border border-[#e8e8e8] dark:border-[#2d2d3a] bg-[#f9f9f9] dark:bg-[#1e1e2d] text-[#d8d8e5] dark:text-[#6d6d80] cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </span>
        @else
            <button wire:click="previousPage"
                class="flex items-center justify-center w-8 h-8 rounded text-[#4b5675] dark:text-[#a1a5b7] bg-[#f9f9f9] dark:bg-[#252532] hover:bg-[#e8e8e8] dark:hover:bg-[#2d2d3a] transition-colors border border-[#e8e8e8] dark:border-[#2d2d3a]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
        @endif

        @foreach($paginator->getUrlRange(1, min($paginator->lastPage(), 5)) as $page => $url)
            @if ($page == $paginator->currentPage())
                <span
                    class="flex items-center justify-center w-8 h-8 rounded bg-[#1b84ff] text-white font-bold text-xs">{{ $page }}</span>
            @else
                <button wire:click="gotoPage({{ $page }})"
                    class="flex items-center justify-center w-8 h-8 rounded border border-[#e8e8e8] dark:border-[#2d2d3a] bg-[#f9f9f9] dark:bg-[#252532] text-[#4b5675] dark:text-[#6d6d80] hover:bg-[#e8e8e8] dark:hover:bg-[#2d2d3a] transition-colors text-xs font-bold">{{ $page }}</button>
            @endif
        @endforeach

        @if ($paginator->lastPage() > 5)
            <span class="px-1 text-[#99a1b7] text-xs">...</span>
            <button wire:click="gotoPage({{ $paginator->lastPage() }})"
                class="flex items-center justify-center w-8 h-8 rounded border border-[#e8e8e8] dark:border-[#2d2d3a] bg-[#f9f9f9] dark:bg-[#252532] text-[#4b5675] dark:text-[#6d6d80] hover:bg-[#e8e8e8] dark:hover:bg-[#2d2d3a] transition-colors text-xs font-bold">{{ $paginator->lastPage() }}</button>
        @endif

        @if ($paginator->hasMorePages())
            <button wire:click="nextPage"
                class="flex items-center justify-center w-8 h-8 rounded border border-[#e8e8e8] dark:border-[#2d2d3a] bg-[#f9f9f9] dark:bg-[#252532] text-[#4b5675] dark:text-[#a1a5b7] hover:bg-[#e8e8e8] dark:hover:bg-[#2d2d3a] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        @else
            <span
                class="flex items-center justify-center w-8 h-8 rounded border border-[#e8e8e8] dark:border-[#2d2d3a] bg-[#f9f9f9] dark:bg-[#1e1e2d] text-[#d8d8e5] dark:text-[#6d6d80] cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </span>
        @endif
    </div>
</div>