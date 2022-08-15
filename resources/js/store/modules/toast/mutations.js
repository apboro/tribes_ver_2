export default ({
    add(state, messsage) {
        console.log(message);
    },

    REMOVE(state, removingMessage) {
        const index = state._messages.findIndex((message) => removingMessage.id === message.id);
        state._messages.splice(index, 1);
    },

    INFO(state, message, duration) {
        state._messages.push(new ToastMessage('info', message, duration));
        console.log('INFO');
    }
});

let id = 0;

class ToastMessage {
    constructor(type, message, duration) {
        this.type = type;
        this.message = message;
        this.duration = duration || 3000;
        this.id = id++;
    }
}

/* 
export function info(message, duration) {
} */
