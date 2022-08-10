export default ({
    LOAD_DATA_LIST({ commit }) {
        const data = {
            subscribers: {
                title: 'Подписчики',
                data: [10, 30, 40, 20, 25, 50, 10, 5, 15, 25, 10, 45],
                infoLeft: {
                    text: 'Прирост',
                    value: '+96'
                },
                infoRight: {
                    text: 'Полезных',
                    value: '+13'    
                }
            },

            messages: {
                title: 'Сообщества',
                data: [3, 7, 25, 36, 42, 23, 31],
                infoLeft: {
                    text: 'Отправлено',
                    value: '+563'
                },
                infoRight: {
                    text: 'Полезных',
                    value: '+233'    
                }
            },

            finance: {
                title: 'Финансы',
                data: [100, 250, 150, 300, 250, 400, 350, 450],
                infoLeft: {
                    text: 'Приход',
                    value: '₽24.3k'
                },
                infoRight: {
                    text: 'Можно вывести',
                    value: '₽20,3k'    
                }
            }
        };

        commit('SET_DATA_LIST', data)
    },

    LOAD_DATA_ITEM({ commit }, name) {
        let data = '';
        switch (name) {
            case 'subscribers':
                data = {
                    items: [
                        {
                            message: 'message text',
                            username: 'username text',
                            date: 'date text',
                            reaction: 'reaction text',
                            answer: 'answer text',
                            utility: 'utility text',
                        },
                        {
                            message: 'message text',
                            username: 'username text',
                            date: 'date text',
                            reaction: 'reaction text',
                            answer: 'answer text',
                            utility: 'utility text',
                        },
                        {
                            message: 'message text',
                            username: 'username text',
                            date: 'date text',
                            reaction: 'reaction text',
                            answer: 'answer text',
                            utility: 'utility text',
                        }
                    ],
                    total: 2356,
                    
                    joined: {
                        items: [500, 200, 100, 60, 155, 80, 220, 300, 100, 400],
                        total: 96,
                    },
                    
                    left: {
                        items: [100, 50, 220, 180, 70, 160, 90, 130, 400, 300],
                        total: 19,
                    },

                    no_visit_chat: 400,
                    no_activity: 650,
                };
            break;

            case 'messages':
                data = { 
                    items: [
                        {
                            message: 'message text',
                            username: 'username text',
                            date: 'date text',
                            reaction: 'reaction text',
                            answer: 'answer text',
                            utility: 'utility text',
                        },
                        {
                            message: 'message text',
                            username: 'username text',
                            date: 'date text',
                            reaction: 'reaction text',
                            answer: 'answer text',
                            utility: 'utility text',
                        },
                        {
                            message: 'message text',
                            username: 'username text',
                            date: 'date text',
                            reaction: 'reaction text',
                            answer: 'answer text',
                            utility: 'utility text',
                        }
                    ],
                    total: 2356,

                    joined: {
                        items: [500, 200, 100, 60, 155, 80, 220, 300, 100, 400],
                        total: 96
                    },

                    useful: {
                        items: [100, 50, 220, 180, 70, 160, 90, 130, 400, 300],
                        total: 219,
                    }

                    /*     
                    common: {
                        text: 'Всего подписчиков в сообществе',
                        value: '2356'
                    },

                    joined: {
                        data: [500, 200, 100, 60, 155, 80, 220, 300, 100, 400],
                        legend: {
                            text: 'Вступили в сообщество',
                            value: '+96',
                        }
                    },

                    useful: {
                        data: [100, 50, 220, 180, 70, 160, 90, 130, 400, 300],
                        legend: {
                            text: 'Полезных сообщений',
                            value: '+219',
                        }
                    } */
                , 
                };
            break;
        }

        commit('SET_DATA_ITEM', data);
    }
});
