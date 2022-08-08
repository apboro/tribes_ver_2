<template>
    <div>
        <full-chart
            :data="data"
        />

        <subscribers-table
            class="subs-table"
            :subscribers="subscribers"
        />
    </div>
</template>

<script>
    import FullChart from '../../../components/pages/CommunityAnalytics/FullChart.vue'
    import SubscribersTable from '../../../components/pages/CommunityAnalytics/SubscribersTable.vue';

    export default {
        name: 'AnalyticsSubscribers',

        components: {
            FullChart,
            SubscribersTable,
        },

        props: {
            data: {
                type: Object,
                default: () => {},
            },
        },

        data() {
            return {
                subscribers: [
                    {
                        message: 'message text',
                        username: 'username text',
                        date: 'date text',
                        reaction: 'reaction text',
                        answer: 'answer text',
                        utility: 'utility text',
                    }
                ]
            }
        }
    }
</script>

