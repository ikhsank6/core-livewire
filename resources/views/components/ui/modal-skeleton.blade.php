@props(['rows' => 4])

<div {{ $attributes->class(['animate-pulse space-y-5']) }} aria-hidden="true">
    @for($i = 0; $i < $rows; $i++)
        <div class="space-y-2">
            <div class="h-3 w-24 rounded bg-zinc-200 dark:bg-zinc-700/60"></div>
            <div class="h-10 w-full rounded-lg bg-zinc-100 dark:bg-zinc-800/60"></div>
        </div>
    @endfor
</div>
