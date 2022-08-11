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
    import PaymentsChart from '../../../../components/pages/community/analytics/payments/PaymentsChart.vue';
    import PaymentsTable from '../../../../components/pages/community/analytics/payments/PaymentsTable.vue';

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

            const val = 11503300000;
            const decimal = 10;

            if (val === 0) {
                return n.toFixed(decimal);
            }

            const notations = ["", "K", "M", "B", "T", "P", "E", "Z", "Y"];
            const id = Math.floor(Math.log(val) / Math.log(1000));

            if (id < 0) {
                return val.toString();
            }


            console.log(`${parseFloat((val / Math.pow(1000, id)).toFixed(decimal))}${
                notations[id]
            }`);
        }
    }
</script>
