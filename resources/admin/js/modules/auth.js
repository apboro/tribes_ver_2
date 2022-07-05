import axios from 'axios';

let state = {
    _user: {}
}

let getters = {
    GET_USER (state){
        return state._user;
    }
}

let mutations = {
    SET_USER (state, userData) {
        state._user = userData;
    }
}

let actions = {
    async LOAD_USER ({commit}) {        
        try {
            const res = await axios({
                method: "post",
                url: "/api/v2/auth"
            })
            commit("SET_USER", res.data);
        } catch (error) {
            console.log(error);
        }
    }
}
export default {
    state,
    getters,
    mutations,
    actions,
};