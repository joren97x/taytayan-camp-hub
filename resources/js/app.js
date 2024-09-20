import './bootstrap';
// import '../css/app.css';


import '@quasar/extras/roboto-font/roboto-font.css'
import '@quasar/extras/material-icons/material-icons.css'

import { Quasar, Notify } from 'quasar'
import 'quasar/src/css/index.sass'


import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)

            .use(Quasar, {
                plugins: {
                  Notify
                }, // import Quasar plugins and add here
                config: {
                  brand: {
                    primary: '#4CAF50',
                    secondary: '#FF6F61',
                    accent: '#87CEEB',
            
                    dark: '#1d1d1d',
                    'dark-page': '#121212',
            
                    positive: '#21BA45',
                    negative: '#f2223a',
                    info: '#31CCEC',
                    warning: '#F2C037'
                  },
                  notify: {
                    color: 'green',
                    textColor: 'white',
                    message: 'Hi this is a message',
                    html: false,
                    position: 'bottom'
                  }, // default set of options for Notify Quasar plugin
                  // loading: {...},  default set of options for Loading Quasar plugin
                   // loadingBar: { ... }, settings for LoadingBar Quasar plugin
                  // ..and many more (check Installation card on each Quasar component/directive/plugin)
                }
            })

            .mount(el);
    },
    progress: {
        color: '#1976D2',
        
    },
});
