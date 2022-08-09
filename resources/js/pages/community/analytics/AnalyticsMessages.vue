<template>
    <div >
        <messages-chart
            :data="data2.data"
        />

        <messages-table
            class="analytics-community__table"
            :subscribers="data2.subscribers"
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

                data2: {}
            }
        },

        computed: {
            ...mapGetters('community_analytics', ['GET_DATA_ITEM', 'GET_MESSAGES_DATA']),
        },

        watch: {
            period() {
                this.filter();
            },

            GET_DATA_ITEM() {
                this.data2 = this.GET_DATA_ITEM;
            }
        },

        methods: {
            ...mapActions('community_analytics', ['LOAD_DATA_ITEM', 'LOAD_MESSAGES_DATA']),

            filter() {
                this.$emit('filter', { name: this.name, period: this.period });
            }
        },

        async mounted() {
            await this.LOAD_DATA_ITEM(this.name);
            console.log();

            this.filter();
        }
    }
</script>

<style lang="scss" scoped>

</style>