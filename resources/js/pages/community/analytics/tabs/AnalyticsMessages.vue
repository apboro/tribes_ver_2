<template>
    <div >
        <messages-chart
            :data="GET_MESSAGES_CHART_DATA"
        />

        <messages-table
            class="analytics-community__table"
            :messages="GET_TABLE_DATA"
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
    import MessagesChart from '../../../../components/pages/community/analytics/messages/MessagesChart.vue';
    import MessagesTable from '../../../../components/pages/community/analytics/messages/MessagesTable.vue';
    import VPagination from '../../../../components/ui/pagination/VPagination.vue';
    import VExportData from '../../../../components/ui/table/VExportData.vue'

    export default {
        name: 'AnalyticsMessages',

        components: {
            MessagesChart,
            MessagesTable,
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
                name: 'messages',

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
                'GET_MESSAGES_CHART_DATA',
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
