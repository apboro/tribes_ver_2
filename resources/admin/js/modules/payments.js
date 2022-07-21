import axios from 'axios';
// import LoadingStatuses from '../components/preloader'

// const LoadingStatuses = {
//     Loading: 'LOADING',
// }

let state = {
    _payment: [],
    isLoading: false,


    loadingStatus: '',
}

let getters = {
    GET_PAYMENTS (state) {
        return state._payment;
    },

    getLoadingStatus (state) {
        return state.isLoading
    }
}

let mutations = {
    SET_PAYMENTS (state, paymentData) {
        state._payment = paymentData;
    },

    setLoadingStatus(state, loadingStatus) {
        state.isLoading = loadingStatus;
    }
}

let actions = {
    async LOAD_PAYMENTS ({commit}, filter_data) {

        try {
            commit("setLoadingStatus", true);
            const resp = await axios({
                method: "post",
                url: "http://tribes/api/v2/payments",
                data: filter_data
            })
            commit("SET_PAYMENTS", resp.data);
            commit("setLoadingStatus", false);
        } catch (error) {
            console.log(error);
            commit("setLoadingStatus", false);
        }
    },
}

export default {
    state,
    getters,
    mutations,
    actions,
}