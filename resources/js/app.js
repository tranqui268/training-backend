require('./bootstrap');
import { createInertiaApp } from "@inertiajs/inertia-vue";
import { InertiaProgress } from "@inertiajs/progress";
import store from './store';
import Vue from 'vue';

InertiaProgress.init({ color: '#4B5563'});

createInertiaApp({
    resolve: (name) => {
        try {
            return require(`./Pages/${name}.vue`);
        } catch (error) {
            console.error(`Error loading page ${name}:`, error);
        }
    },
    setup({ el, App, props, plugin }) {
        Vue.use(plugin);

        Vue.mixin({
            methods:{
                route: window.route
            },
        })

        Vue.component('Link', require('@inertiajs/inertia-vue').InertiaLink);

        new Vue({
            render: h => h(App, props),
            store
        }).$mount(el);
    },
    title: title => title ? `${title} - ${window.appName}` : window.appName,
});
