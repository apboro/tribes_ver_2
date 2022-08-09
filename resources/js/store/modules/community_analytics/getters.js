export default ({
    GET_DATA_LIST(state) {
        return state._dataList;
    },

    GET_DATA_ITEM(state) {
        return state._dataItem;
    },

    GET_DATA(state) {
        return state._dataItem.data;
    },

    GET_SUBSCRIBERS_DATA(state) {
        return state._subscribers;
    },

    GET_MESSAGES_DATA(state) {
        return state._messages;
    },
});
