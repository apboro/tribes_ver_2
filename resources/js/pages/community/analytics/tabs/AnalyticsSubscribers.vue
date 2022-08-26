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

        <div class="analytics-community__footer">

            <v-export-data/>

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
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import SubscribersChart from '../../../../components/pages/community/analytics/subscribers/SubscribersChart.vue';
    import SubscribersTable from '../../../../components/pages/community/analytics/subscribers/SubscribersTable.vue';
    import ProgressList from '../../../../components/pages/community/analytics/ProgressList.vue';
    import VPagination from '../../../../components/ui/pagination/VPagination.vue';
    import VExportData from '../../../../components/ui/table/VExportData.vue'

    export default {
        name: 'AnalyticsSubscribers',

        components: {
            SubscribersChart,
            SubscribersTable,
            ProgressList,
            VPagination,
            VExportData
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
                'GET_SUBSCRIBERS_CHART_DATA',
                'GET_SUBSCRIBERS_PROGRESS_DATA',
                'IS_LOADING',
                'GET_PAGINATE_DATA',
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
            },
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

