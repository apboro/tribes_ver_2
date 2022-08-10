<template>
    <div >
        <subscribers-chart
            :data="GET_SUBSCRIBERS_CHART_DATA"
        />
        
        <progress-list
            class="analytics-community__progress"
            :progressItems="progressList"
        />

        <subscribers-table
            class="analytics-community__table"
            :subscribers="GET_TABLE_DATA"
        />
    </div>
</template>

<script>
    import { mapGetters, mapActions, mapMutations } from 'vuex';
    import SubscribersChart from '../../../../components/pages/Community/Analytics/subscribers/SubscribersChart.vue';
    import SubscribersTable from '../../../../components/pages/Community/Analytics/subscribers/SubscribersTable.vue';
    import ProgressList from '../../../../components/pages/Community/Analytics/ProgressList.vue';

    export default {
        name: 'AnalyticsSubscribers',

        components: {
            SubscribersChart,
            SubscribersTable,
            ProgressList,
        },

        props: {
            period: {
                type: String,
                default: () => '',
            }
        },

        data() {
            return {
                name: 'subscribers',
            }
        },

        computed: {
            ...mapGetters('community_analytics', [
                'GET_TABLE_DATA',
                'GET_SUBSCRIBERS_CHART_DATA',
                'GET_SUBSCRIBERS_PROGRESS_DATA'
            ]),

            progressList() {
                return [
                    {
                        text: 'Не заходили в чат',
                        value: Math.floor(this.GET_SUBSCRIBERS_PROGRESS_DATA.no_visit_chat / this.GET_SUBSCRIBERS_PROGRESS_DATA.total * 100),
                    },
                    {
                        text: 'Ни одного сообщения и реакции',
                        value: Math.floor(this.GET_SUBSCRIBERS_PROGRESS_DATA.no_activity / this.GET_SUBSCRIBERS_PROGRESS_DATA.total * 100),
                    }
                ];
            }
        },

        watch: {
            period() {
                this.filter();
            },
        },

        methods: {
            ...mapActions('community_analytics', ['LOAD_DATA_ITEM']),

            filter() {
                this.$emit('filter', { name: this.name, period: this.period });
            }
        },

        mounted() {
            this.LOAD_DATA_ITEM(this.name);

            this.filter();
        }
    }
</script>

