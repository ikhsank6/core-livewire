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
    class="fixed inset-0 z-50"
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
        class="fixed inset-0 bg-black/60 backdrop-blur-sm"
        aria-hidden="true"
    ></div>

    {{-- Modal Container --}}
    <div class="fixed inset-0 flex items-center justify-center p-4 sm:p-6 overflow-y-auto">
        {{-- Modal Panel --}}
        <div
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="modal-panel relative w-full {{ $maxWidthClass }} bg-white dark:bg-[#1e1e2d] rounded-xl shadow-2xl border border-zinc-200 dark:border-zinc-700/50"
        >
            {{-- Header --}}
            @if($title)
                <div class="modal-header">
                    <h3 class="modal-title">{{ $title }}</h3>
                    <button @click="show = false" class="modal-close-btn" type="button">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            {{-- Body --}}
            <div class="modal-body custom-modal-body">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            <div class="modal-footer">
                @if(isset($footer))
                    {{ $footer }}
                @else
                    <button type="button" @click.prevent="show = false" wire:click="{{ $cancelClick }}"
                        class="modal-btn-cancel">
                        Cancel
                    </button>
                    <button type="submit" @if($formId) form="{{ $formId }}" @endif
                        class="modal-btn-submit"
                        wire:loading.attr="disabled">
                        <svg wire:loading class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove>Save Changes</span>
                        <span wire:loading>Saving...</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* ========================================
   MODAL BASE STYLES - CONSISTENT LAYOUT
   ======================================== */

.modal-panel {
    display: flex;
    flex-direction: column;
    max-height: 90vh;
    overflow: hidden;
}

/* Header - Fixed 64px height */
.modal-header {
    height: 64px;
    min-height: 64px;
    max-height: 64px;
    padding: 0 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #e5e7eb;
    background: white;
    flex-shrink: 0;
}
.dark .modal-header {
    border-bottom-color: #374151;
    background: #1e1e2d;
}

.modal-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #111827;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.dark .modal-title {
    color: white;
}

.modal-close-btn {
    padding: 8px;
    margin-right: -8px;
    color: #9ca3af;
    border-radius: 8px;
    transition: all 0.15s;
    flex-shrink: 0;
}
.modal-close-btn:hover {
    color: #374151;
    background: #f3f4f6;
}
.dark .modal-close-btn:hover {
    color: white;
    background: #374151;
}

/* Body - Flexible, Scrollable */
.modal-body {
    flex: 1;
    overflow-y: auto;
    padding: 24px;
    background: white;
}
.dark .modal-body {
    background: #1e1e2d;
}

/* Footer - Fixed 72px height */
.modal-footer {
    height: 72px;
    min-height: 72px;
    max-height: 72px;
    padding: 0 24px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
    flex-shrink: 0;
}
.dark .modal-footer {
    border-top-color: #374151;
    background: #1a1a27;
}

/* Buttons */
.modal-btn-cancel {
    height: 40px;
    padding: 0 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    font-weight: 600;
    color: #6b7280;
    border-radius: 10px;
    transition: all 0.15s;
    cursor: pointer;
}
.modal-btn-cancel:hover {
    color: #111827;
    background: #e5e7eb;
}
.dark .modal-btn-cancel {
    color: #9ca3af;
}
.dark .modal-btn-cancel:hover {
    color: white;
    background: #374151;
}

.modal-btn-submit {
    height: 40px;
    padding: 0 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    color: white;
    background: #2563eb;
    border-radius: 10px;
    box-shadow: 0 4px 14px -4px rgba(37, 99, 235, 0.5);
    transition: all 0.15s;
    cursor: pointer;
}
.modal-btn-submit:hover {
    background: #1d4ed8;
}
.modal-btn-submit:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
.modal-btn-submit:active {
    transform: scale(0.97);
}

/* ========================================
   FILAMENT FORM OVERRIDES
   ======================================== */
.custom-modal-body .fi-fo-component-ctn,
.custom-modal-body .fi-fo-grid {
    display: flex !important;
    flex-direction: column !important;
    width: 100% !important;
    gap: 1.25rem !important;
    grid-template-columns: none !important;
}

.custom-modal-body .fi-fo-field-wrp {
    width: 100% !important;
}

/* ========================================
   PREMIUM TOGGLE BUTTONS
   ======================================== */
.premium-toggle-group .fi-fo-toggle-buttons {
    display: flex !important;
    gap: 0.5rem !important;
    margin-top: 0.5rem;
}

.premium-toggle-group button {
    height: 44px !important;
    padding: 0 1.25rem !important;
    border-radius: 10px !important;
    font-weight: 600 !important;
    font-size: 0.875rem !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 0.5rem !important;
    border: 2px solid transparent !important;
    transition: all 0.15s !important;
    cursor: pointer !important;
}

/* Default unselected */
.premium-toggle-group button {
    background: #f3f4f6 !important;
    color: #6b7280 !important;
    border-color: #e5e7eb !important;
}
.dark .premium-toggle-group button {
    background: #27272a !important;
    color: #9ca3af !important;
    border-color: #3f3f46 !important;
}

/* Selected Success (Active/Yes) */
.premium-toggle-group button[data-checked="true"][value="1"],
.premium-toggle-group button.fi-active[value="1"],
.premium-toggle-group button[aria-pressed="true"]:first-child {
    background: #10b981 !important;
    color: white !important;
    border-color: #059669 !important;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3) !important;
}

/* Selected Danger (Inactive/No) */
.premium-toggle-group button[data-checked="true"][value="0"],
.premium-toggle-group button.fi-active[value="0"],
.premium-toggle-group button[aria-pressed="true"]:nth-child(2) {
    background: #ef4444 !important;
    color: white !important;
    border-color: #dc2626 !important;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3) !important;
}

.premium-toggle-group button[aria-pressed="true"] svg,
.premium-toggle-group button.fi-active svg,
.premium-toggle-group button[data-checked="true"] svg {
    color: white !important;
}
</style>
