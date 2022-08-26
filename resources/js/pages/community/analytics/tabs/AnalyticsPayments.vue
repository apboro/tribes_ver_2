<template>
    <div >
        <payments-chart
            :data="GET_PAYMENTS_CHART_DATA"
            :chartOptions="chartOptions"
        />

        <payments-table
            class="analytics-community__table"
            :payments="GET_TABLE_DATA"
        />

         <!-- Pagination -->
        <v-pagination
            v-if="GET_TABLE_DATA && GET_TABLE_DATA.length && !IS_LOADING"
            class="analytics-community__pagination"
            :paginateData="GET_PAGINATE_DATA"
            :selectOptions="paginationSelectedOptions"
            @onPageClick="setPage"
            @onChangePerPage="setPerPage"
        />
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import PaymentsChart from '../../../../components/pages/community/analytics/payments/PaymentsChart.vue';
    import PaymentsTable from '../../../../components/pages/community/analytics/payments/PaymentsTable.vue';
    import VPagination from '../../../../components/ui/pagination/VPagination.vue';

    export default {
        name: 'AnalyticsPayments',

        components: {
            PaymentsChart,
            PaymentsTable,
            VPagination,
        },

        props: {
            period: {
                type: String,
                default: () => '',
            },

            chartOptions: {
                type: Object,
                default: () => {},
            }
        },

        data() {
            return {
                name: 'payments',

                paginationSelectedOptions: [
                    { label: 15, value: 15 },
                    { label: 30, value: 30 },
                    { label: 45, value: 45 }
                ],
            }
        },

        computed: {
            ...mapGetters('community_analytics', [
                'GET_TABLE_DATA',
                'GET_PAYMENTS_CHART_DATA',
                'IS_LOADING',
                'GET_PAGINATE_DATA'
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
            },

            // переключение страницы пагинации
            setPage(value) {
                this.SET_PAGINATION({ page: value });
                //this.LOAD_QUESTIONS();
            },

            // изменение количества просматриваемых страниц
            setPerPage(value) {
                this.SET_PAGINATION({
                    per_page: value,
                    page: 1 
                });
                //this.LOAD_DATA_ITEM(this.name);
            },
        },

        mounted() {
            this.LOAD_DATA_ITEM(this.name);

            this.filter();
        }
    }
</script>
