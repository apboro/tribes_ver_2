import axios from 'axios';

let state = {
    _payment: [],
    payment_date: [],
}

let getters = {
    GET_PAYMENTS (state) {
        return state._payment;
    },

    GET_PAYMENT_DATE (state) {
        return state.payment_date;
    }
}

let mutations = {
    SET_PAYMENTS (state, paymentData) {
        state._payment = paymentData;
    },

    PUSH_PAYMENT_DATE (state, dateData) {
        state.payment_date = dateData;
    }
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
        try {
            const resp = await axios({
                method: "post",
                url: "http://tribes/api/v2/payments",
                data: filter_data
            })
            commit("SET_PAYMENTS", resp.data);
        } catch (error) {
            console.log(error);
        }
    },
    // async GET_DATE ({commit}, date) {
    //     try {
    //         const resp = await axios({
    //             method: "post",
    //             url: "http://tribes/api/v2/payments",
    //             data: date
    //         })
    //         console.log(resp.data.data.forEach( function (arrayItem){
    //             let arr = [];
    //             arra
    //             console.log(arrayItem.created_at);
    //         }));
    //         commit("PUSH_PAYMENT_DATE", resp.data.data.forEach( function (arrayItem){
    //             console.log(arrayItem.created_at);
    //         }));
    //     } catch (error) {
    //         console.log(error);
    //     }
    // },
}

export default {
    state,
    getters,
    mutations,
    actions,
}