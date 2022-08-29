import Vue from 'vue'
import Vuex from 'vuex'

import alert from './modules/alert/alert';
import knowledge from './modules/knowledge/knowledge';
import course from './modules/course/course';
import community_analytics from './modules/community_analytics/community_analytics';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        alert,
        course,
        knowledge,
        community_analytics,
    }
});
