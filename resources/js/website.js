import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

/* The public portal keeps motion to what answers the visitor's action
   (nav lamp, hero light, tabs, accordions) plus one hero entrance, all of
   which live in CSS and in inline Alpine data, so nothing else is needed here.
   Each of them checks prefers-reduced-motion on its own. */
