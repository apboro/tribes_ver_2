export default ({
    SET_DATA_LIST(state, dataList) {
        state._dataList = dataList;
    },

    SET_DATA_ITEMS(state, items) {
        state._items = items;
    },

    SET_TOTAL_VALUE(state, totalValue) {
        state._total = totalValue;
    },

    SET_ALL_PAYMENTS(state, allPayments) {
        state._all_payments = allPayments;
    },

    SET_JOINED_DATA(state, joined) {
        state._joined = joined;
    },

    SET_LEFT_DATA(state, left) {
        state._left = left;
    },

    SET_USEFUL_DATA(state, useful) {
        state._useful = useful;
    },

    SET_SUBSCRIPTIONS_DATA(state, subscriptions) {
        state._subscriptions = subscriptions;
    },

    SET_DONATIONS_DATA(state, donations) {
        state._donations = donations;
    },

    SET_MEDIA_DATA(state, media) {
        state._media = media;
    },

    SET_NO_VISIT_VALUE(state, noVisitValue) {
        state._no_visit_chat = noVisitValue;
    },

    SET_NO_ACTIVITY_VALUE(state, noActivityValue) {
        state._no_activity = noActivityValue;
    },

    SET_FILTERS(state, filters) {
        Object.keys(state._filters).forEach((key) => {
            if (filters.hasOwnProperty(key)) {
                state._filters[key] = filters[key];
            }
        });
    },

    SET_META(state, meta) {
        state._meta = meta;
    },
});
