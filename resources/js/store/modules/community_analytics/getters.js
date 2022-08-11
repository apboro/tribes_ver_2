export default ({
    GET_DATA_LIST(state) {
        return state._dataList;
    },

    GET_TABLE_DATA(state) {
        return state._items;
    },

    GET_SUBSCRIBERS_CHART_DATA(state) {
        return {
            total: state._total,
            joined: state._joined,
            left: state._left,
        };
    },

    GET_SUBSCRIBERS_PROGRESS_DATA(state) {
        return {
            total: state._total,
            no_activity: state._no_activity,
            no_visit_chat: state._no_visit_chat,
        };
    },

    GET_MESSAGES_CHART_DATA(state) {
        return {
            total: state._total,
            joined: state._joined,
            useful: state._useful,
        };
    },

    GET_PAYMENTS_CHART_DATA(state) {
        return {
            total: state._total,
            period_total: state._period_total,
            subscriptions: state._subscriptions,
            donations: state._donations,
            media: state._media,
        };
    },

    GET_PAGINATE_DATA(state) {
        return state._meta;
    },

    IS_LOADING(state) {
        return state._isLoading;
    }
});
