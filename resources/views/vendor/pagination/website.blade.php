@if ($paginator->hasPages())
    @php
        $linkClass = 'grid min-h-11 min-w-11 place-items-center rounded-md px-3 text-[0.9375rem] font-semibold tabular-nums transition-colors';
        $idle = 'text-body hover:bg-sunken hover:text-ink';
        $current = 'bg-ink text-page';
        $disabled = 'text-muted/40 cursor-not-allowed';
    @endphp

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
                    <span class="{{ $linkClass }} {{ $disabled }}" aria-disabled="true">Sebelumnya</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $linkClass }} {{ $idle }}">Sebelumnya</a>
                @endif
            </li>

            {{-- Nomor halaman --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li aria-hidden="true"><span class="{{ $linkClass }} text-muted">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
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
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $linkClass }} {{ $idle }}">Berikutnya</a>
                @else
                    <span class="{{ $linkClass }} {{ $disabled }}" aria-disabled="true">Berikutnya</span>
                @endif
            </li>
        </ul>
    </nav>
@endif
