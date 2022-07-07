import axios from 'axios';

let state = {
    _user: {},
}

let getters = {
    GET_USER (state){
        return state._user;
    },
    isAuthenticated: (state) => !!state._user,
}

let mutations = {
    SET_USER (state, userData) {
        state._user = userData;
    },

    setUser(state, email){
        state._user = email
    },

    // auth_request(state){
    //     state.status = 'loading'
    // },
    // auth_success(state, token, user){
    //     state.status = 'success'
    //     state.token = token
    //     state.user = user
    // },
    // auth_error(state){
    //     state.status = 'error'
    // },
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
    async LogIn({commit}, _user) {
        await axios.post("login", _user);
        await commit("setUser", _user.get("email"));
      },
    // login({commit}, user){
    //     return new Promise((resolve, reject) => {
    //         commit('auth_request')
    //         axios({
    //             url: '/api/login', 
    //             data: user, 
    //             method: 'POST' 
    //         })
    //         .then(resp => {
    //             const token = resp.data.token
    //             const user = resp.data.user
    //             console.log(user, token);
    //             localStorage.setItem('token', token)
    //             axios.defaults.headers.common['Authorization'] = token
    //             commit('auth_success', token, user)
    //             resolve(resp)
    //         })
    //         .catch(err => {
    //         commit('auth_error')
    //         localStorage.removeItem('token')
    //         reject(err)
    //         })
    //     })
    // },

    // async Register({dispatch}, form) {
    //     await axios.post('register', form)
    //     let UserForm = new FormData()
    //     UserForm.append('username', form.username)
    //     UserForm.append('password', form.password)
    //     await dispatch('LogIn', UserForm)
    // },

    


    // async LogIn({commit}, User) {
    //     await axios.post('login', User)
    //     await commit('setUser', User.get('username'))
    // },
}
export default {
    state,
    getters,
    mutations,
    actions,
};