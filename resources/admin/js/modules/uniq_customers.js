import axios from 'axios';

let state = {
    _cutomers: [],
}

let mutations = {
    SET_CUSTOMERS (state, customersData) {
        state._cutomers = customersData;
    },
}

let getters = {
    getCustomers (state) {
        return state._cutomers;
    },
}

let actions = {

    async loadUniqUsersPayments({commit}, customer_data) {

        try {
            commit("SET_PRELOADER_STATUS", true);
            const resp = await axios({
                method: "post",
                url: "http://tribes/api/v2/customers",
                data: customer_data
            })
            console.log('CUSTOMERS: ', resp.data);
            commit("SET_CUSTOMERS", resp.data);
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