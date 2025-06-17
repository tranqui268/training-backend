import userService from '@/Services/userService.js'

export default {
    namespaced: true,
    
    state: {
        users: [],
        loading: false,
        error: null
    },

    mutations: {
        SET_USERS(state, users) {
            state.users = users;
        },
        SET_LOADING(state, loading) {
            state.loading = loading;
        },
        SET_ERROR(state, error) {
            state.error = error;
        }
    },
    
    actions: {
        async fetchUsers({ commit }) {
            commit('SET_LOADING', true);
            commit('SET_ERROR', null);
            try {
                const users = await userService.fetchUsers();
                commit('SET_USERS', users.data || users);
            } catch (error) {
                commit('SET_ERROR', error);
            } finally {
                commit('SET_LOADING', false);
            }
        }
    },

    getters: {
        users: state => state.users,
        loading: state => state.loading,
        error: state => state.error
    }
}