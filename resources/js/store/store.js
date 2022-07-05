import Vue from 'vue'
import Vuex from 'vuex'

import knowledge from './modules/knowledge/knowledge';
import course from './modules/course/course'

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        course,
        knowledge,
    }
});
