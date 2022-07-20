import axios from 'axios';

let state = {
    _payment: [],
}

let getters = {
    GET_PAYMENTS (state) {
        return state._payment;
    },
}

let mutations = {
    SET_PAYMENTS (state, paymentData) {
        state._payment = paymentData;
    },
}

let actions = {
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
}

export default {
    state,
    getters,
    mutations,
    actions,
}