import Vue from 'vue'
import Vuex from 'vuex'

import user from './modules/users';
import auth from './modules/auth';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        loading : false,
    },
    getters : {
        loading : state => {
            return state.loading
        },
        // isAuthenticated: state => !!state.user,
    },
    mutations: {
        loading(state, val){
            state.loading = val;
        },
    },
    actions:{
    },
    modules: {
        user,
        auth
    },
    mixins: {}
})