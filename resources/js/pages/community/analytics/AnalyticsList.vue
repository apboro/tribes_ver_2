<template>
    <div>
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
    import ChartCard from '../../../components/pages/Community/Analytics/ChartCard.vue';

    export default {
        name: 'AnalyticsList',

        components: {
            ChartCard,
        },

        props: {
            data: {
                type: Object,
                default: () => {},
            },

            period: {
                type: String,
                default: () => '',
            }
        },

        watch: {
            period() {
                this.filter();
            }
        },

        methods: {
            filter() {
                this.$emit('filter', { name: 'list', period: this.period });
            }
        },

        mounted() {
            this.filter();
        }
    }
</script>
