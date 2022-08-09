<template>
    <div >
        <subscribers-chart
            
            :data="GET_SUBSCRIBERS_DATA.data"
        />
        
        
        <progress-list
            class="analytics-community__progress"
            :progressItems="GET_SUBSCRIBERS_DATA.progressItems"
        />

        <subscribers-table
            class="analytics-community__table"
            :subscribers="GET_SUBSCRIBERS_DATA.subscribers"
        />
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import SubscribersChart from '../../../components/pages/Community/Analytics/SubscribersChart.vue';
    import SubscribersTable from '../../../components/pages/Community/Analytics/SubscribersTable.vue';
    import ProgressList from '../../../components/pages/Community/Analytics/ProgressList.vue';

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
            ...mapGetters('community_analytics', ['GET_DATA_ITEM', 'GET_DATA', 'GET_SUBSCRIBERS_DATA']),
        },

        watch: {
            period() {
                this.filter();
            }
        },

        methods: {
            ...mapActions('community_analytics', ['LOAD_DATA_ITEM', "LOAD_SUBSCRIBERS_DATA"]),

            filter() {
                this.$emit('filter', { name: this.name, period: this.period });
            }
        },

        mounted() {
            this.LOAD_SUBSCRIBERS_DATA();

            this.filter();
        }
    }
</script>

