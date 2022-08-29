export default ({
    REMOVE(state, removingMessage) {
        const index = state._messages.findIndex((message) => removingMessage.id === message.id);
        state._messages.splice(index, 1);
    },

    SET_MESSAGE(state, { type, message, duration }) {
        /* if (state._messages.length > 5) {
            state._messages.splice(0, 1);
        } */
        state._messages.push(new ToastMessage(type, message, duration));
    },
});

let id = 0;

class ToastMessage {
    constructor(type, message, duration) {
        this.type = type;
        this.message = message;
        this.duration = duration || 5000;
        this.id = id++;
    }
}
