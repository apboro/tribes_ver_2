import axios from 'axios';

let state = {
    users : [],
    user_errors : [],
};

let getters = {
    users : state => {
        return state.users
    },
    getuserById : (state) => (id) => {
        return id === 0 ?
            [{
                id : 0, 
                name : '',
                content : '',
                link : '',
                phone : '',
                isPublished : true,
                images : []
            }]
            : state.users.filter(item => item.id === parseInt(id));
    },
    getuser : (state) => (id) => {

        return state.users.filter(item => item.id === id)[0];
    },
    user_store_errors : state => {
        return !!state.user_errors ? state.user_errors : {
            price: null,
            action_price: null,
            name: null,
            article: null,
            user_id: null,
            description: null,
        }
    },
};

let mutations = {
    USERS(state, users){
        state.users = users;
    },
    PUSH_USER(state, user){
        // state.users = user;
        // state.users.pushIfNotExist(user, function(e) {
        //     return e.id === user.id;
        // });
    },
    PUSH_USER_ERRORS(state, errors){
        state.user_errors = errors;
    }
};

let actions = {
    async get_users ({commit}, filter_data) {

        try {
            commit("SET_PRELOADER_STATUS", true);
            const resp = await axios({
                method: "post",
                url: '/api/v2/users',
                data: filter_data
            })
            commit("USERS", resp.data);
            commit("SET_PRELOADER_STATUS", false);
        } catch (error) {
            console.log(error);
            commit("SET_PRELOADER_STATUS", false);
        }
    },

    get_user({commit}, id){

        return new Promise((resolve, reject) => {
            commit("SET_PRELOADER_STATUS", true);
            axios({url: '/api/v2/user', data: {'id' : id}, method: 'POST' })
                .then(resp => {
                    commit('PUSH_USER', resp.data.user);
                    resolve(resp);
                    commit("SET_PRELOADER_STATUS", false);
                })
                .catch(err => {
                    console.log('Err');
                    reject(err);
                    commit("SET_PRELOADER_STATUS", false);
                })
        })
    },

    store_user({commit}, data){
        return new Promise((resolve, reject) => {
            //commit('auth_request');
            axios({url: '/api/common/users/store', data: data, method: 'POST' })
                .then(resp => {
                    commit('PUSH_USER', resp.data.user);
                    commit('PUSH_USER_ERRORS', null);
                    resolve(resp);
                })
                .catch(err => {
                    commit('PUSH_USER_ERRORS', !!err.response.data.errors ? err.response.data.errors : null);
                    reject(err);
                })
        })
    },
    update_user({commit}, user){
        return new Promise((resolve, reject) => {
            //commit('auth_request');
            axios({url: '/api/common/users/' + user.id + '/update', data: user, method: 'POST' })
                .then(resp => {
                    commit('PUSH_USER_ERRORS', null);
                    resolve(resp);
                })
                .catch(err => {
                    commit('PUSH_USER_ERRORS', !!err.response.data.errors ? err.response.data.errors : null);
                    reject(err);
                })
        })
    },
};

export default {
    state,
    getters,
    mutations,
    actions,
};