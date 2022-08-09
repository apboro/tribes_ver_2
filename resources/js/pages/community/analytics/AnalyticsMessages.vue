<template>
    <div >
        <messages-chart
            :data="GET_MESSAGES_DATA.data"
        />

        <messages-table
            class="analytics-community__table"
            :subscribers="GET_MESSAGES_DATA.subscribers"
        />
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import MessagesChart from '../../../components/pages/Community/Analytics/MessagesChart.vue';
    import MessagesTable from '../../../components/pages/Community/Analytics/MessagesTable.vue';

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
            ...mapGetters('community_analytics', ['GET_DATA_ITEM', 'GET_MESSAGES_DATA']),
        },

        watch: {
            period() {
                this.filter();
            }
        },

        methods: {
            ...mapActions('community_analytics', ['LOAD_DATA_ITEM', 'LOAD_MESSAGES_DATA']),

            filter() {
                this.$emit('filter', { name: this.name, period: this.period });
            }
        },

        async mounted() {
            await this.LOAD_MESSAGES_DATA();
            console.log();

            this.filter();
        }
    }
</script>

<style lang="scss" scoped>

</style>