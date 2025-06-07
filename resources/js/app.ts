import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, DefineComponent, h } from 'vue';
import { createPinia } from 'pinia';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

// Font Awesome imports
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faPenToSquare, faTrash, faMagnifyingGlass } from '@fortawesome/free-solid-svg-icons';

// Adiciona os ícones desejados à biblioteca
library.add(faPenToSquare, faTrash, faMagnifyingGlass);

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const pinia = createPinia();

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) });

        vueApp.component('font-awesome-icon', FontAwesomeIcon); // Registra o componente globalmente
        vueApp.use(plugin);
        vueApp.use(ZiggyVue);
        vueApp.use(pinia);

        vueApp.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
