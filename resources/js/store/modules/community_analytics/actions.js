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
                            name: 'name text',
                            username: 'username text',
                            date: 'date text',
                            messages: 'messages text',
                            reaction_out: 'reaction out text',
                            reaction_in: 'reaction in text',
                            utility: 'utility text',
                        },
                        {
                            name: 'name text',
                            username: 'username text',
                            date: 'date text',
                            messages: 'messages text',
                            reaction_out: 'reaction out text',
                            reaction_in: 'reaction in text',
                            utility: 'utility text',
                        },
                        {
                            name: 'name text',
                            username: 'username text',
                            date: 'date text',
                            messages: 'messages text',
                            reaction_out: 'reaction out text',
                            reaction_in: 'reaction in text',
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

                commit('SET_DATA_ITEMS', data.items);
                commit('SET_JOINED_DATA', data.joined);
                commit('SET_LEFT_DATA', data.left);
                commit('SET_TOTAL_VALUE', data.total);
                commit('SET_NO_VISIT_VALUE', data.no_visit_chat);
                commit('SET_NO_ACTIVITY_VALUE', data.no_activity);
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
                };

                commit('SET_DATA_ITEMS', data.items);
                commit('SET_JOINED_DATA', data.joined);
                commit('SET_TOTAL_VALUE', data.total);
                commit('SET_USEFUL_DATA', data.useful);
             
            break;

            case 'payments':
                data = { 
                    items: [
                        {
                            name: 'name text',
                            username: 'username text',
                            transaction_name: 'transaction name text',
                            transaction_type: 'transaction type text',
                            date: 'date text',
                            amount: 'amount text',
                        },
                        {
                            name: 'name text',
                            username: 'username text',
                            transaction_name: 'transaction name text',
                            transaction_type: 'transaction type text',
                            date: 'date text',
                            amount: 'amount text',
                        },
                        {
                            name: 'name text',
                            username: 'username text',
                            transaction_name: 'transaction name text',
                            transaction_type: 'transaction type text',
                            date: 'date text',
                            amount: 'amount text',
                        },
                    ],
                    total: 150330,
                    period_total: 1150330, 

                    subscriptions: {
                        items: [10, 20, 25, 15, 18, 12, 32, 22, 19, 8],
                        total: 219
                    },

                    donations: {
                        items: [100, 50, 220, 180, 70, 160, 90, 130, 66, 23],
                        total: 1319,
                    },

                    media: {
                        items: [130, 220, 120, 176, 72, 121, 68, 142, 26, 230],
                        total: 2319,
                    }
                };

                commit('SET_DATA_ITEMS', data.items);
                commit('SET_TOTAL_VALUE', data.total);
                commit('SET_PERIOD_TOTAL_VALUE', data.period_total);
                commit('SET_SUBSCRIPTIONS_DATA', data.subscriptions);
                commit('SET_DONATIONS_DATA', data.donations);
                commit('SET_MEDIA_DATA', data.media);
            break;
        }


        
    }
});
