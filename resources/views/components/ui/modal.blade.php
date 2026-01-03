@props([
    'name' => null,
    'title' => '',
    'maxWidth' => '2xl',
    'formId' => null,
    'cancelClick' => '$set("showModal", false)'
])

@php
$maxWidthClass = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    '3xl' => 'sm:max-w-3xl',
    '4xl' => 'sm:max-w-4xl',
    '5xl' => 'sm:max-w-5xl',
    '6xl' => 'sm:max-w-6xl',
    '7xl' => 'sm:max-w-7xl',
][$maxWidth] ?? 'sm:max-w-2xl';
@endphp

<div
    x-data="{ 
        show: @if($attributes->wire('model')->value()) @entangle($attributes->wire('model')) @else false @endif 
    }"
    x-on:open-modal.window="if ($event.detail.name === '{{ $name }}') show = true"
    x-on:close-modal.window="if ($event.detail.name === '{{ $name }}') show = false"
    x-show="show"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
    style="display: none;"
>
    {{-- Backdrop --}}
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-[#1b1c22]/80 backdrop-blur-md transition-opacity"
        aria-hidden="true"
    ></div>

    {{-- Modal Panel --}}
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative w-full {{ $maxWidthClass }} max-h-[90vh] flex flex-col transform rounded-2xl bg-white dark:bg-[#1e1e2d] border border-[#e8e8e8] dark:border-[#2d2d3a] shadow-2xl transition-all overflow-hidden"
    >
        {{-- Header --}}
        @if($title)
            <div class="px-6 py-4 border-b border-[#e8e8e8] dark:border-[#2d2d3a] flex items-center justify-between shrink-0 bg-[#f9f9f9] dark:bg-[#252532]/50 rounded-t-2xl">
                <h3 class="text-lg font-bold text-[#1b1c22] dark:text-white">
                    {{ $title }}
                </h3>
                <button @click="show = false" class="text-[#99a1b7] hover:text-[#4b5675] dark:hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Body - Now with proper scrolling --}}
        <div class="flex-1 overflow-y-auto p-8 custom-modal-body">
            <div class="w-full">
                {{ $slot }}
            </div>
        </div>

        {{-- Footer - Always visible at bottom --}}
        <div class="bg-[#f9f9f9] dark:bg-[#252532]/50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-[#e8e8e8] dark:border-[#2d2d3a] shrink-0 rounded-b-2xl">
            @if(isset($footer))
                {{ $footer }}
            @else
                <button type="submit" @if($formId) form="{{ $formId }}" @endif
                    class="inline-flex justify-center items-center gap-2 rounded-lg px-4 py-2 bg-[#1b84ff] text-sm font-bold text-white hover:bg-[#0070f0] focus:outline-none transition-colors disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                    wire:loading.attr="disabled">
                    <svg wire:loading class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove>Save Changes</span>
                    <span wire:loading>Saving...</span>
                </button>
                <button type="button" wire:click="{{ $cancelClick }}"
                    class="inline-flex justify-center rounded-lg px-4 py-2 bg-transparent text-sm font-bold text-[#99a1b7] hover:text-[#4b5675] dark:hover:text-white transition-colors">
                    Cancel
                </button>
            @endif
        </div>
    </div>
</div>

<style>
/* 1. Force Full Width Layout */
.custom-modal-body .fi-fo-component-ctn,
.custom-modal-body .fi-fo-grid {
    display: flex !important;
    flex-direction: column !important;
    width: 100% !important;
    gap: 1.5rem !important;
    grid-template-columns: none !important;
}

.custom-modal-body .fi-fo-field-wrp {
    width: 100% !important;
}

/* 2. Premium Toggle Buttons - Maximum Contrast Fix */
.premium-toggle-group .fi-fo-toggle-buttons {
    display: flex !important;
    gap: 0.75rem !important;
    margin-top: 0.5rem;
}

/* Common button architecture */
.premium-toggle-group button {
    position: relative !important;
    padding: 0.75rem 1.5rem !important;
    border-radius: 0.75rem !important;
    font-weight: 700 !important;
    font-size: 0.875rem !important;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    display: flex !important;
    align-items: center !important;
    gap: 0.625rem !important;
    border: 2px solid transparent !important;
    cursor: pointer !important;
}

/* Default (unselected) state - LIGHT MODE */
.premium-toggle-group button {
    background-color: #f4f4f5 !important; /* zinc-100 */
    color: #71717a !important; /* zinc-500 */
    border-color: #e4e4e7 !important; /* zinc-200 */
}

/* Default (unselected) state - DARK MODE */
.dark .premium-toggle-group button {
    background-color: #27272a !important; /* zinc-800 */
    color: #71717a !important; /* zinc-500 */
    border-color: #3f3f46 !important; /* zinc-700 */
}

/* ===== SELECTED STATES ===== */

/* Success Button Selected (Visible) - Using data-checked attribute from Filament */
.premium-toggle-group button[data-checked="true"][value="1"],
.premium-toggle-group button.fi-active[value="1"],
.premium-toggle-group button[aria-pressed="true"]:first-child,
.premium-toggle-group button[aria-checked="true"]:first-child {
    background-color: #10b981 !important; /* emerald-500 */
    color: #ffffff !important;
    border-color: #059669 !important; /* emerald-600 */
    box-shadow: 0 4px 14px -3px rgba(16, 185, 129, 0.4) !important;
}

/* Danger Button Selected (Hidden) */
.premium-toggle-group button[data-checked="true"][value="0"],
.premium-toggle-group button.fi-active[value="0"],
.premium-toggle-group button[aria-pressed="true"]:nth-child(2),
.premium-toggle-group button[aria-checked="true"]:nth-child(2) {
    background-color: #ef4444 !important; /* red-500 */
    color: #ffffff !important;
    border-color: #dc2626 !important; /* red-600 */
    box-shadow: 0 4px 14px -3px rgba(239, 68, 68, 0.4) !important;
}

/* Force icon/text color white on selected */
.premium-toggle-group button.fi-active span,
.premium-toggle-group button.fi-active svg,
.premium-toggle-group button[data-checked="true"] span,
.premium-toggle-group button[data-checked="true"] svg,
.premium-toggle-group button[aria-pressed="true"] span,
.premium-toggle-group button[aria-pressed="true"] svg {
    color: #ffffff !important;
    fill: currentColor !important;
}

/* Hover on unselected buttons */
.premium-toggle-group button:hover:not(.fi-active):not([data-checked="true"]):not([aria-pressed="true"]) {
    background-color: #e4e4e7 !important;
    border-color: #d4d4d8 !important;
}

.dark .premium-toggle-group button:hover:not(.fi-active):not([data-checked="true"]):not([aria-pressed="true"]) {
    background-color: #3f3f46 !important;
    border-color: #52525b !important;
}

/* Ensure icons inside buttons have proper styling */
.premium-toggle-group button svg {
    width: 1.25rem !important;
    height: 1.25rem !important;
}
</style>

