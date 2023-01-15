import axios from 'axios';

let state = {
    community: [],
}

let mutations = {
    SET_COMMUNITIES (state, communityData) {
        state.community = communityData;
    },
}

let getters = {
    getCommunities (state) {
        return state.community;
    },
}

let actions = {
    async loadCommunities ({commit}, filter_data) {

        try {
            commit("SET_PRELOADER_STATUS", true);
            const resp = await axios({
                method: "post",
                url: "/api/v2/communities",
                data: filter_data
            })
            commit("SET_COMMUNITIES", resp.data);
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