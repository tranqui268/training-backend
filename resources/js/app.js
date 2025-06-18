import './bootstrap';
import '../css/app.css';
import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/vue3';
import { InertiaProgress } from '@inertiajs/progress';
import Antd from 'ant-design-vue'
import store from './store';

InertiaProgress.init({ color: '#4B5563' });

createInertiaApp({
    resolve: name => require(`./Pages/${name}.vue`),
    title: title => title ? `${title} - ${window.appName}` : window.appName,
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        app.use(plugin);
        app.use(store);
        app.use(Antd);
        app.component('Link', Link);
        app.mixin({
            methods: {
                route: window.route
            }
        });

        app.mount(el);
    },
});
