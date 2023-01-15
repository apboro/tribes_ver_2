import axios from 'axios';

let state = {
    _feedback: [],
}

let mutations = {
    SET_FEEDBACKS (state, feedbackData) {
        state._feedback = feedbackData;
    },
}

let getters = {
    getFeedbacks (state) {
        return state._feedback;
    },
}

let actions = {
    async loadFeedbackList ({commit}, filter_data) {

        try {
            commit("SET_PRELOADER_STATUS", true);
            const resp = await axios({
                method: "post",
                url: "/api/v2/feedback/list",
                data: filter_data
            })
            commit("SET_FEEDBACKS", resp.data);
            commit("SET_PRELOADER_STATUS", false);
        } catch (error) {
            console.log(error);
            commit("SET_PRELOADER_STATUS", false);
        }
    },
}

export default {
    state,
    actions,
    mutations,
    getters,

}