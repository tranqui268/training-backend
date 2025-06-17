import Vue from 'vue';
import Vuex from 'vuex';

import userModel from './modules/user';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        userModel
    },

    state: {
        // Global state can be defined here if needed
    },

    mutations: {
        // Global mutations can be defined here if needed
    },

    actions: {
        // Global actions can be defined here if needed
    },

    getters: {
        // Global getters can be defined here if needed
    }
});