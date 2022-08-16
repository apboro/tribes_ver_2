<template>
    <div>
        <button @click="$alertSuccess('dfdfdfdfdf')">toast</button>
        <span v-long-date="'10.11.2020'"></span>
        <span v-short-date="'10.11.2020'"></span>


        <ul
            class="analytics-community__card-list"
        >
            <chart-card
                class="analytics-community__card-item"
                v-for="(data, index) in cardsData"
                :key="index"
                :data="data"
            />
        </ul>
    </div>
</template>

<script>
    import { mapGetters, mapMutations, mapActions } from 'vuex';
    import ChartCard from '../../../../components/pages/community/analytics/ChartCard.vue';
    import { numberFormatting } from '../../../../core/functions';

    export default {
        name: 'AnalyticsList',

        components: {
            ChartCard,
        },

        props: {
            period: {
                type: String,
                default: () => '',
            }
        },

        computed: {
            ...mapGetters('community_analytics', ['GET_DATA_LIST']),

            cardsData() {
                return this.convertData();
            },
        },

        watch: {
            period() {
                this.filter();
            }
        },

        methods: {
            ...mapMutations('toast', ['INFO']),
            ...mapActions('community_analytics', ['LOAD_DATA_LIST']),
            
            filter() {
                this.$emit('filter', { name: 'list', period: this.period });
            },

            convertData() {
                return Object.entries(this.GET_DATA_LIST).map(([category, data]) => {
                    let obj = {};
                    switch (category) {
                        case 'subscribers':
                            obj.title = 'Подписчики';
                            obj.data = data.items;
                            obj.infoLeft = {
                                text: 'Прирост',
                                value: `+${ numberFormatting(data.growth) }`
                            };
                            obj.infoRight = {
                                text: 'Полезных',
                                value: `+${ numberFormatting(data.useful) }`    
                            };
                            break;
                        
                        case 'messages':
                            obj.title = 'Сообщения';
                            obj.data = data.items;
                            obj.infoLeft = {
                                text: 'Отправлено',
                                value: `+${ numberFormatting(data.sent) }`
                            };
                            obj.infoRight = {
                                text: 'Полезных',
                                value: `+${ numberFormatting(data.useful) }`    
                            };
                            break;

                        case 'payments':
                            obj.title = 'Финансы';
                            obj.data = data.items;
                            obj.infoLeft = {
                                text: 'Приход',
                                value: `+${ numberFormatting(data.income) }`
                            };
                            obj.infoRight = {
                                text: 'Можно вывести',
                                value: `+${ numberFormatting(data.available) }`    
                            };
                            break;
                    }

                    return obj;
                });
            },
        },

        mounted() {
            this.LOAD_DATA_LIST();

            this.filter();       
        }
    }
</script>
