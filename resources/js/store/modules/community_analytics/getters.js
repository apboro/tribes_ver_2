export default ({
    GET_DATA_LIST(state) {
        return state._dataList;
    },

    GET_DATA_ITEM(state) {
        return state._dataItem;
    },

    GET_TABLE_DATA(state) {
        return state._dataItem.items;
    },

    GET_SUBSCRIBERS_CHART_DATA(state) {
        return {
            total: state._dataItem.total,
            joined: state._dataItem.joined,
            left: state._dataItem.left,
        };
    },

    GET_SUBSCRIBERS_PROGRESS_DATA(state) {
        return {
            total: state._dataItem.total,
            no_activity: state._dataItem.no_activity,
            no_visit_chat: state._dataItem.no_visit_chat,
        };
    },

    GET_MESSAGES_CHART_DATA(state) {
        return {
            total: state._dataItem.total,
            joined: state._dataItem.joined,
            useful: state._dataItem.useful,
        };
    },

});
