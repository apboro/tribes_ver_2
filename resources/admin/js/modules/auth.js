import axios from 'axios';

let state = {
    _user: {},
}

let getters = {
    GET_USER (state){
        return state._user;
    },
    isAuthenticated (state) { return !!state._user },
    
    isAuthen (state) { return sessionStorage.getItem('token') != 'null' }
}

let mutations = {
    SET_USER (state, userData) {
        state._user = userData;
    },

    setUser(state, email){
        state._user = email
    },
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
    },
}
export default {
    state,
    getters,
    mutations,
    actions,
};