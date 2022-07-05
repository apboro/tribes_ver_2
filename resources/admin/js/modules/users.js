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
    users(state, users){
        state.users = users;
    },
    push_user(state, user){
        // state.users.pushIfNotExist(user, function(e) {
        //     return e.id === user.id;
        // });
    },
    push_user_errors(state, errors){
        state.user_errors = errors;
    }
};

let actions = {

    get_users({commit}, filter_data){
        return new Promise((resolve, reject) => {
            //commit('auth_request');
            axios({url: '/api/v2/users', data: filter_data, method: 'POST' })
                .then(resp => {
                    commit('users', resp.data);
                    resolve(resp);
                })
                .catch(err => {
                    reject(err);
                })
        })
    },
    get_user({commit}, id){
        return new Promise((resolve, reject) => {
            //commit('auth_request');
            axios({url: '/api/common/users/' + id + '/get', method: 'GET' })
                .then(resp => {
                    console.log('success');
                    commit('push_user', resp.data.user);
                    resolve(resp);
                })
                .catch(err => {
                    console.log('Err');
                    reject(err);
                })
        })
    },
    store_user({commit}, data){
        return new Promise((resolve, reject) => {
            //commit('auth_request');
            axios({url: '/api/common/users/store', data: data, method: 'POST' })
                .then(resp => {
                    commit('push_user', resp.data.user);
                    commit('push_user_errors', null);
                    resolve(resp);
                })
                .catch(err => {
                    commit('push_user_errors', !!err.response.data.errors ? err.response.data.errors : null);
                    reject(err);
                })
        })
    },
    update_user({commit}, user){
        return new Promise((resolve, reject) => {
            //commit('auth_request');
            axios({url: '/api/common/users/' + user.id + '/update', data: user, method: 'POST' })
                .then(resp => {
                    commit('push_user_errors', null);
                    resolve(resp);
                })
                .catch(err => {
                    commit('push_user_errors', !!err.response.data.errors ? err.response.data.errors : null);
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