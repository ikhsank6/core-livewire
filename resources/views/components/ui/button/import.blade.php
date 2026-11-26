{{-- Secondary header action, same anatomy as button/add: tooltip, mini icon, label. --}}
@props([
    'label' => 'Impor',
    'tooltip' => null,
    'icon' => 'arrow-up-tray',
])

@php
    $finalTooltip = $tooltip ?? "klik untuk {$label}";
@endphp

<flux:tooltip :content="$finalTooltip" position="top">
    <button {{ $attributes->merge([
        'type' => 'button',
        'class' => 'inline-flex items-center gap-2 rounded-full border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-zinc-700 dark:text-zinc-300 shadow-sm hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-all active:scale-95 cursor-pointer focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-metronic-primary'
    ]) }}>
        <flux:icon :name="$icon" variant="mini" class="w-4 h-4" />
        <span>{{ $label }}</span>
    </button>
</flux:tooltip>
