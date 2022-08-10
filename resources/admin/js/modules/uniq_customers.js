import axios from 'axios';

let state = {
    _customers: [],
}

let mutations = {
    SET_CUSTOMERS (state, customersData) {
        state._customers = customersData;
    },
}

let getters = {
    getCustomers (state) {
        return state._customers;
    },

    customersHasName (state) {
        return state._customers.filter((customer) => customer.name != null)
    }
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
            commit("SET_CUSTOMERS", resp.data.customers);
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