import Vue from 'vue'
import Vuex from 'vuex'

import toast from './modules/toast/toast';
import knowledge from './modules/knowledge/knowledge';
import course from './modules/course/course';
import community_analytics from './modules/community_analytics/community_analytics';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        toast,
        course,
        knowledge,
        community_analytics,
    }
});
