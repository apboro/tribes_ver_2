export class CreateCommunityData {
    constructor (parent) {
        this.chat_bot = null;

        this.init();
    }

    init() {
        // Подписываемся на событие добавления данных о выборе чат бота
        Emitter.subscribe('createCommunityBot:text', (data) => {
            this.setChatBot = data.chatBot;
        });
    }

    get chatBot() {
        return this.chat_bot;
    }

    set setChatBot(value) {
        this.chat_bot = value;
    }
}
