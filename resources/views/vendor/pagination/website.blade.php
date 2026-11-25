@if ($paginator->hasPages())
    @php
        $linkClass = 'grid min-h-11 min-w-11 place-items-center rounded-md px-3 text-[0.9375rem] font-semibold tabular-nums transition-colors';
        $idle = 'text-body hover:bg-sunken hover:text-ink';
        $current = 'bg-ink text-page';
        $disabled = 'text-muted/40 cursor-not-allowed';
    @endphp

    {{-- Phone: previous, "Halaman X dari Y", next. From sm up: previous, the numbers, next. --}}
    <nav role="navigation" aria-label="Navigasi halaman"
         class="flex flex-col items-center justify-between gap-5 border-t border-line pt-8 sm:flex-row">

        <p class="text-sm text-muted" aria-live="polite">
            Menampilkan {{ $paginator->firstItem() }} sampai {{ $paginator->lastItem() }}
            dari {{ $paginator->total() }} berita
        </p>

        <ul class="flex items-center gap-1">
            {{-- Sebelumnya --}}
            <li>
                @if ($paginator->onFirstPage())
                    <span class="{{ $linkClass }} {{ $disabled }}" aria-disabled="true" aria-label="Halaman sebelumnya">
                        <span class="sm:hidden" aria-hidden="true">@svg('heroicon-o-chevron-left', 'h-5 w-5')</span>
                        <span class="hidden sm:inline">Sebelumnya</span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya" class="{{ $linkClass }} {{ $idle }}">
                        <span class="sm:hidden" aria-hidden="true">@svg('heroicon-o-chevron-left', 'h-5 w-5')</span>
                        <span class="hidden sm:inline">Sebelumnya</span>
                    </a>
                @endif
            </li>

            {{-- Phone only --}}
            <li class="px-3 text-sm font-semibold tabular-nums text-ink sm:hidden">
                Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
            </li>

            {{-- Nomor halaman (sm and up) --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="hidden sm:block" aria-hidden="true"><span class="{{ $linkClass }} text-muted">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li class="hidden sm:block">
                            @if ($page == $paginator->currentPage())
                                <span class="{{ $linkClass }} {{ $current }}" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="{{ $linkClass }} {{ $idle }}" aria-label="Halaman {{ $page }}">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            {{-- Berikutnya --}}
            <li>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya" class="{{ $linkClass }} {{ $idle }}">
                        <span class="hidden sm:inline">Berikutnya</span>
                        <span class="sm:hidden" aria-hidden="true">@svg('heroicon-o-chevron-right', 'h-5 w-5')</span>
                    </a>
                @else
                    <span class="{{ $linkClass }} {{ $disabled }}" aria-disabled="true" aria-label="Halaman berikutnya">
                        <span class="hidden sm:inline">Berikutnya</span>
                        <span class="sm:hidden" aria-hidden="true">@svg('heroicon-o-chevron-right', 'h-5 w-5')</span>
                    </span>
                @endif
            </li>
        </ul>
    </nav>
@endif
