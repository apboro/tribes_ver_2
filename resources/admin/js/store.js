import Vue from 'vue'
import Vuex from 'vuex'

import user from './modules/users';
import auth from './modules/auth';
import payment from './modules/payments';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        loading : false,
        isPreloader: false,
        GetUser: sessionStorage.getItem('store') ? JSON.parse(sessionStorage.getItem("store")) : null
    },

    getters : {
        loading : state => {
            return state.loading
        },

        isLogged: state => !!state.userU,

        getPreloaderStatus (state) {
            return state.isPreloader
        }
    },

    mutations: {
        loading(state, val){
            state.loading = val;
        },

        SET_PRELOADER_STATUS (state, preloaderStatus) {
            state.isPreloader = preloaderStatus;
        }
    },

    modules: {
        user,
        auth,
        payment
    },

    mixins: {}
})