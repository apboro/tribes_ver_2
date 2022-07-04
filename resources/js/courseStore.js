import Vue from 'vue'
import Vuex from 'vuex'

import course from './courseModules/course';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        loading : false,
    },
    getters : {
        loading : state => {
            return state.loading
        },
    },
    mutations: {
        loading(state, val){
            state.loading = val;
        },
    },
    actions:{
    },
    modules: {
        course
    },
    mixins: {}
})