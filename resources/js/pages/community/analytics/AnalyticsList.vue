<template>
    <div>
        <analytics-filter
            class="analytics-community__filter"
            @getPeriod="filter"
        />

        <ul
            class="analytics-community__card-list"
        >
            <chart-card
                class="analytics-community__card-item"
                v-for="(data, index) in data"
                :key="index"
                :data="data"
            />
        </ul>
    </div>
</template>

<script>
    import AnalyticsFilter from '../../../components/pages/CommunityAnalytics/AnalyticsFilter.vue';
    import ChartCard from '../../../components/pages/CommunityAnalytics/ChartCard.vue';

    export default {
        name: 'AnalyticsList',

        components: {
            AnalyticsFilter,
            ChartCard,
        },

        props: {
            data: {
                type: Object,
                default: () => {},
            }
        },

        methods: {
            filter(period) {
                if (period == 'week') {
                    this.data.subscribers.data = [10, 30, 40, 20, 25, 50, 10, 5, 15, 25, 10, 45];
                    this.data.messages.data = [3, 7, 25, 36, 42, 23, 31];
                    this.data.finance.data = [100, 250, 150, 300, 250, 400, 350, 450];
                } else if (period == 'month') {
                    this.data.subscribers.data = [100, 200, 300, 100, 50, 150, 420, 100, 200, 300, 100, 50, 150, 420, 100, 200, 300, 100, 50, 150, 420, 100, 200, 300, 100, 50, 150, 420];
                    this.data.messages.data = [100, 20, 300, 100, 20, 300, 100, 20, 300, 100, 20, 300, 100, 20, 300, 100, 20, 300, 100, 20, 300, 100, 20, 300, 300, 100, 20, 300];
                    this.data.finance.data = [100, 200, 300, 100, 200, 300, 200, 300, 100, 200, 300, 200, 300, 100, 200, 300, 200, 300, 100, 200, 300, 200, 300, 100, 200, 300, 200, 300];
                } else if (period == 'year') {
                    this.data.subscribers.data = [200, 100, 300, 420, 50, 100, 150];
                    this.data.messages.data = [10, 200, 30, 50, 20];
                    this.data.finance.data = [150, 20, 300, 200, 100];
                }
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>