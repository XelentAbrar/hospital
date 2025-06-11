import './bootstrap';
import '../css/app.css';
import '../css/web.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import VueApexCharts from 'vue3-apexcharts';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .component('apexchart', VueApexCharts);

        // Add your slugify function
        app.config.globalProperties.slugify = text => {
            const wordsToExclude = ["is", "in", "to", "&", "$", "+", "-", " ", "and", "[", "("];
            return text
              .toLowerCase()
              .split(' ')
              .map(word => word.split('').filter(character => !wordsToExclude.includes(character)).join('')) // Remove unwanted characters
              .filter(word => !wordsToExclude.includes(word)) // Exclude unwanted words
              .join('-');
          }


        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
