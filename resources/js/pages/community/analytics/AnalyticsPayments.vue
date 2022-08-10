<template>
    <div >
        <payments-chart
            :data="GET_PAYMENTS_CHART_DATA"
        />

        <payments-table
            class="analytics-community__table"
            :payments="GET_TABLE_DATA"
        />
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import PaymentsChart from '../../../components/pages/Community/Analytics/payments/PaymentsChart.vue';
    import PaymentsTable from '../../../components/pages/Community/Analytics/payments/PaymentsTable.vue';

    export default {
        name: 'AnalyticsPayments',

        components: {
            PaymentsChart,
            PaymentsTable,
        },

        props: {
            period: {
                type: String,
                default: () => '',
            }
        },

        data() {
            return {
                name: 'payments',
            }
        },

        computed: {
            ...mapGetters('community_analytics', [
                'GET_TABLE_DATA',
                'GET_PAYMENTS_CHART_DATA',
            ]),
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
