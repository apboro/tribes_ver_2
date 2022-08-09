<template>
    <div>
        <ul
            class="analytics-community__card-list"
        >
            <chart-card
                class="analytics-community__card-item"
                v-for="(data, index) in GET_DATA_LIST"
                :key="index"
                :data="data"
            />
        </ul>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import ChartCard from '../../../components/pages/Community/Analytics/ChartCard.vue';

    export default {
        name: 'AnalyticsList',

        components: {
            ChartCard,
        },

        props: {
            period: {
                type: String,
                default: () => '',
            }
        },

        computed: {
            ...mapGetters('community_analytics', ['GET_DATA_LIST']),
        },

        watch: {
            period() {
                this.filter();
            }
        },

        methods: {
            ...mapActions('community_analytics', ['LOAD_DATA_LIST']),
            
            filter() {
                this.$emit('filter', { name: 'list', period: this.period });
            }
        },

        mounted() {
            this.LOAD_DATA_LIST();

            this.filter();

        }
    }
</script>
