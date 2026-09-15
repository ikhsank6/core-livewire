@if ($paginator->hasPages())
    @php
        $linkClass = 'min-w-11 h-11 px-3 grid place-items-center rounded-full border text-[0.875rem] transition-colors';
        $idle = 'border-hairline dark:border-pine-soft text-moss dark:text-bone-dim hover:border-marigold';
        $current = 'border-marigold bg-marigold text-ink';
        $disabled = 'border-hairline dark:border-pine-soft text-moss/35 dark:text-bone-dim/35 cursor-not-allowed';
    @endphp

    <nav role="navigation" aria-label="Navigasi halaman"
         class="flex flex-col sm:flex-row items-center justify-between gap-5">

        <p class="t-micro text-moss dark:text-bone-dim" aria-live="polite">
            Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}
            dari {{ $paginator->total() }} catatan
        </p>

        <ul class="flex items-center gap-2">
            {{-- Sebelumnya --}}
            <li>
                @if ($paginator->onFirstPage())
                    <span class="{{ $linkClass }} {{ $disabled }}" aria-disabled="true" aria-label="Halaman sebelumnya">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                             stroke-width="1.8" aria-hidden="true">
                            <path d="M13 8H3M7 4L3 8l4 4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                       class="{{ $linkClass }} {{ $idle }}" aria-label="Halaman sebelumnya">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                             stroke-width="1.8" aria-hidden="true">
                            <path d="M13 8H3M7 4L3 8l4 4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                @endif
            </li>

            {{-- Nomor halaman --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li aria-hidden="true">
                        <span class="{{ $linkClass }} border-transparent text-moss dark:text-bone-dim">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span class="{{ $linkClass }} {{ $current }} t-data" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="{{ $linkClass }} {{ $idle }} t-data"
                                   aria-label="Halaman {{ $page }}">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            {{-- Berikutnya --}}
            <li>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                       class="{{ $linkClass }} {{ $idle }}" aria-label="Halaman berikutnya">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                             stroke-width="1.8" aria-hidden="true">
                            <path d="M3 8h10M9 4l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                @else
                    <span class="{{ $linkClass }} {{ $disabled }}" aria-disabled="true" aria-label="Halaman berikutnya">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                             stroke-width="1.8" aria-hidden="true">
                            <path d="M3 8h10M9 4l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                @endif
            </li>
        </ul>
    </nav>
@endif
