import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

/* The public portal keeps motion to what answers the visitor's action (the sliding nav marker,
   theme switch, tabs, accordions, carousel) plus the looping tick and seal on the hero certificate.
   All of it lives in CSS and in inline Alpine data, so nothing else is needed here.
   Each of them checks prefers-reduced-motion on its own. */
