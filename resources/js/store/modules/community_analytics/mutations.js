export default ({
    SET_DATA_LIST(state, dataList) {
        state._dataList = dataList;
    },

    SET_DATA_ITEM(state, dataItem) {
        state._dataItem = dataItem;
    },

    SET_SUBSCRIBERS_DATA(state, subscribers) {
        state._subscribers = subscribers;
    },

    SET_MESSAGES_DATA(state, messages) {
        state._messages = messages;
    }
});
