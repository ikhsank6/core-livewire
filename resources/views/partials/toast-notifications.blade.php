{{-- Toast Notification System --}}
<div wire:ignore class="fixed top-5 right-5 pointer-events-none" style="z-index: 999999;">
    <div x-data="{ 
        toasts: [],
        add(data) {
            const payload = data.detail || data;
            const text = typeof payload === 'string' ? payload : (payload.text || '');
            const variant = payload.variant || 'success';
            
            if (!text) return;
            
            const id = Date.now() + Math.random();
            this.toasts.push({ id, text, variant });
            
            setTimeout(() => {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }, 5000);
        }
    }" x-init="
        @if(session('success')) add({ text: '{{ session('success') }}', variant: 'success' }); @endif
        @if(session('error')) add({ text: '{{ session('error') }}', variant: 'danger' }); @endif
    " @notify.window="add($event.detail)" class="flex flex-col gap-3 items-end">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="translate-x-8 opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-8"
                class="pointer-events-auto w-[380px] rounded-xl shadow-2xl overflow-hidden bg-zinc-900 text-white">

                {{-- Main Content --}}
                <div class="p-4 flex items-start gap-3">
                    {{-- Icon --}}
                    <template x-if="toast.variant === 'success'">
                        <div class="shrink-0 w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </template>
                    <template x-if="toast.variant === 'danger'">
                        <div class="shrink-0 w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </template>

                    {{-- Text --}}
                    <div class="flex-1 pt-0.5">
                        <p class="text-sm font-semibold text-white" x-text="toast.text"></p>
                    </div>

                    {{-- Close Button --}}
                    <button @click="toasts = toasts.filter(t => t.id !== toast.id)"
                        class="shrink-0 w-7 h-7 rounded-lg flex items-center justify-center text-zinc-400 hover:text-white hover:bg-zinc-700/50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-zinc-500/50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Progress Bar --}}
                <div class="h-1 w-full bg-zinc-800">
                    <div class="h-full animate-shrink-width"
                        :class="toast.variant === 'success' ? 'bg-green-500' : 'bg-red-500'"
                        style="animation-duration: 5s;"></div>
                </div>
            </div>
        </template>
    </div>
</div>