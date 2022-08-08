<template>
    <div >
        <subscribers-chart
            
            :data="data"
        />

        <div class="progress-analytics-community analytics-community__progress">
            <ul class="progress-analytics-community__list">
                <li class="progress-analytics-community__item">
                    <span class="progress-analytics-community__title">Lorem, ipsum dolor.</span>

                    <span class="progress-analytics-community__value">20%</span>

                    <v-progress value="20" />
                </li>

                <li class="progress-analytics-community__item">
                    <span class="progress-analytics-community__title">Lorem, ipsum dolor.</span>

                    <span class="progress-analytics-community__value">40%</span>

                    <v-progress value="40" />
                </li>
            </ul>
        </div>


        <subscribers-table
            class="subs-table"
            :subscribers="subscribers"
        />
    </div>
</template>

<script>
    import SubscribersChart from '../../../components/pages/Community/Analytics/SubscribersChart.vue';
    import SubscribersTable from '../../../components/pages/Community/Analytics/SubscribersTable.vue';
    import VProgress from '../../../components/ui/progress/VProgress.vue';

    export default {
        name: 'AnalyticsSubscribers',

        components: {
            SubscribersChart,
            SubscribersTable,
            VProgress,
        },

        props: {
            data: {
                type: Object,
                default: () => {},
            },

            period: {
                type: String,
                default: () => '',
            }
        },

        data() {
            return {
                subscribers: [
                    {
                        message: 'message text',
                        username: 'username text',
                        date: 'date text',
                        reaction: 'reaction text',
                        answer: 'answer text',
                        utility: 'utility text',
                    }
                ]
            }
        },

        watch: {
            period() {
                this.filter();
            }
        },

        methods: {
            filter() {
                this.$emit('filter', { name: 'subscribers', period: this.period });
            }
        },

        mounted() {
            this.filter();
        }
    }
</script>

