import axios from 'axios';

let state = {
    _payment: [],
}

let mutations = {
    SET_PAYMENTS (state, paymentData) {
        state._payment = paymentData;
    },
}

let getters = {
    getPayments (state) {
        return state._payment;
    },
}

let actions = {
    async loadPayments ({commit}, filter_data) {

        try {
            commit("SET_PRELOADER_STATUS", true);
            const resp = await axios({
                method: "post",
                url: "http://tribes/api/v2/payments",
                data: filter_data
            })
            commit("SET_PAYMENTS", resp.data);
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