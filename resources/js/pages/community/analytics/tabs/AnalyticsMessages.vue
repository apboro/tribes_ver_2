<template>
    <div >
        <messages-chart
            :data="GET_MESSAGES_CHART_DATA"
        />

        <messages-table
            class="analytics-community__table"
            :messages="GET_TABLE_DATA"
        />
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import MessagesChart from '../../../../components/pages/Community/Analytics/messages/MessagesChart.vue';
    import MessagesTable from '../../../../components/pages/Community/Analytics/messages/MessagesTable.vue';

    export default {
        name: 'AnalyticsMessages',

        components: {
            MessagesChart,
            MessagesTable,
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
            }
        },

        computed: {
            ...mapGetters('community_analytics', [
                'GET_TABLE_DATA',
                'GET_MESSAGES_CHART_DATA',
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
