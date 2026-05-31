import './bootstrap';

// Re-initialize Flowbite components after Livewire navigation
// Flowbite is loaded via CDN - initFlowbite() is available globally
document.addEventListener('livewire:navigated', () => {
    if (typeof initFlowbite === 'function') {
        initFlowbite();
    }
});
