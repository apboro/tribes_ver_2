import axios from 'axios';

let state = {
    _payment: {},
}

let getters = {
    getPayments (state) {
        return state._payment;
    }
}

let mutations = {
    SET_PAYMENT (state, paymentData) {
        state._payment = paymentData;
    },
}

let actions = {
    // LOAD_PAYMENTS({commit}, filter_data){
    //     return new Promise((resolve, reject) => {
    //         //commit('auth_request');
    //         axios({url: 'http://tribes/api/v2/payments', data: filter_data, method: 'POST' })
    //             .then(resp => {
    //                 commit('SET_PAYMENT', resp.data);
    //                 resolve(resp);
    //             })
    //             .catch(err => {
    //                 reject(err);
    //             })
    //     })
    // },
    async LOAD_PAYMENTS ({commit}, filter_data) {
        console.log(commit);
        console.log(filter_data);
        try {
            const resp = await axios({
                method: "post",
                url: "http://tribes/api/v2/payments",
                data: filter_data
            })
            commit("SET_PAYMENT", resp.data);
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
}