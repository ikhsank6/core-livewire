import './bootstrap';
import Highcharts from 'highcharts';

// Expose Highcharts globally so Alpine x-init and inline scripts can use it
window.Highcharts = Highcharts;

// Re-initialize Flowbite components after Livewire navigation
document.addEventListener('livewire:navigated', () => {
    if (typeof initFlowbite === 'function') {
        initFlowbite();
    }
});
