@props(['header' => null, 'footer' => null])

<div {{ $attributes->class(['flex flex-col']) }}>
    @if ($header)
        <div class="mb-4">
            {{ $header }}
        </div>
    @endif

    <div
        class="premium-table-container overflow-hidden bg-white dark:bg-[#1e1e2d] border border-[#e8e8e8] dark:border-[#2d2d3a] rounded-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0">
                {{ $slot }}
            </table>
        </div>
    </div>

    @if ($footer)
        <div class="mt-4">
            {{ $footer }}
        </div>
    @endif
</div>