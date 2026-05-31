{{-- Toast Notification System - Flowbite style --}}
<div wire:ignore class="fixed top-5 right-5 z-999999 pointer-events-none">
    <div
        x-data="{
            toasts: [],
            add(data) {
                const payload = data.detail || data;
                const text = typeof payload === 'string' ? payload : (payload.text || '');
                const variant = payload.variant || 'success';
                const title = payload.title || null;
                if (!text) return;
                const id = Date.now() + Math.random();
                this.toasts.push({ id, text, variant, title });
                setTimeout(() => this.remove(id), 5000);
            },
            remove(id) {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }
        }"
        x-init="
            @if(session('success')) add({ text: '{{ addslashes(session('success')) }}', variant: 'success' }); @endif
            @if(session('error'))   add({ text: '{{ addslashes(session('error')) }}', variant: 'danger' });  @endif
            @if(session('warning')) add({ text: '{{ addslashes(session('warning')) }}', variant: 'warning' }); @endif
            @if(session('info'))    add({ text: '{{ addslashes(session('info')) }}', variant: 'info' }); @endif
        "
        @notify.window="add($event.detail)"
        class="flex flex-col gap-3 items-end pointer-events-none"
    >
        <template x-for="toast in toasts" :key="toast.id">
            <div
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-x-4 scale-95"
                x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                x-transition:leave-end="opacity-0 translate-x-4 scale-95"
                class="pointer-events-auto w-80 rounded-lg overflow-hidden bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg"
                role="alert"
            >
                <div class="flex items-start p-4 gap-3">

                    {{-- Icon badge --}}
                    <div class="shrink-0 flex items-center justify-center w-8 h-8 rounded-lg"
                        :class="{
                            'bg-green-100 text-green-500 dark:bg-green-800/40 dark:text-green-300': toast.variant === 'success',
                            'bg-red-100 text-red-500 dark:bg-red-800/40 dark:text-red-300': toast.variant === 'danger',
                            'bg-yellow-100 text-yellow-500 dark:bg-yellow-800/40 dark:text-yellow-300': toast.variant === 'warning',
                            'bg-blue-100 text-blue-500 dark:bg-blue-800/40 dark:text-blue-300': toast.variant === 'info'
                        }">

                        {{-- success --}}
                        <template x-if="toast.variant === 'success'">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </template>

                        {{-- danger --}}
                        <template x-if="toast.variant === 'danger'">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </template>

                        {{-- warning --}}
                        <template x-if="toast.variant === 'warning'">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </template>

                        {{-- info --}}
                        <template x-if="toast.variant === 'info'">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </template>
                    </div>

                    {{-- Text --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white"
                            x-text="toast.title || (toast.variant === 'success' ? 'Berhasil' : toast.variant === 'danger' ? 'Terjadi Kesalahan' : toast.variant === 'warning' ? 'Peringatan' : 'Informasi')">
                        </p>
                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400 leading-relaxed" x-text="toast.text"></p>
                    </div>

                    {{-- Close button --}}
                    <button @click="remove(toast.id)"
                        class="shrink-0 -mt-0.5 -mr-0.5 inline-flex items-center justify-center w-7 h-7 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 14 14">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>

                {{-- Progress bar --}}
                <div class="h-0.5 w-full bg-gray-100 dark:bg-gray-700">
                    <div class="h-full animate-shrink-width"
                        :class="{
                            'bg-green-500': toast.variant === 'success',
                            'bg-red-500': toast.variant === 'danger',
                            'bg-yellow-400': toast.variant === 'warning',
                            'bg-blue-500': toast.variant === 'info'
                        }"
                        style="animation-duration: 5s;">
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
