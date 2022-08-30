<template>
    <div v-if="data && data.total" class="chart-analytics-community">
        <div class="chart-analytics-community__header">
            <div class="chart-analytics-community__label chart-analytics-community__label--right">
                <span class="chart-analytics-community__text a">
                    Всего подписчиков
                </span>

                <span class="chart-analytics-community__value">
                    {{ numberFormat(data.total) }}
                </span>
            </div>

            <button
                class="chart-analytics-community__label chart-analytics-community__label--pointer chart-analytics-community__label--right"
                @click="toggleData('left')"
            >
                <span class="chart-analytics-community__text">
                    Покинули
                </span>
                
                <span
                    class="chart-analytics-community__value"
                    :style="{ color: left.color }"
                >
                    -{{ numberFormat(data.left.total) }}
                </span>
            </button>

            <button
                class="chart-analytics-community__label chart-analytics-community__label--pointer chart-analytics-community__label--right"
                @click="toggleData('joined')"
            >
                <span class="chart-analytics-community__text">
                    Вступили
                </span>

                <span
                    class="chart-analytics-community__value"
                    :style="{ color: joined.color }"
                >
                    +{{ numberFormat(data.joined.total) }}
                </span>
            </button>
        </div>

        <line-chart
            class="chart-analytics-community__chart"
            :chartData="chartData"
            :chartOptions="chartOptionsData"
        />
    </div>
</template>

<script>
    import { numberFormatting } from '../../../../../core/functions';
    import LineChart from '../../../../ui/chart/LineChart.vue';

    export default {
        name: 'SubscribersChart',
        
        components: {
            LineChart,
        },

        props: {
            data: {
                type: [Object, null],
                default: null
            },

            chartOptions: {
                type: Object,
                default: () => {}
            }
        },

        data() {
            return {
                joined: {
                    isVisible: false,
                    color: '#21C169',
                },
                
                left: {
                    isVisible: false,
                    color: '#E24041',
                },

                labels: ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье']
            }
        },

        computed: {
            chartData() {
                
                return {

                    labels: this.labels,
                    datasets: [
                        {
                            data: this.data.joined.items,                           
                            borderColor: this.joined.color,
                            hidden: this.joined.isVisible,
                        },

                        {
                            data: this.data.left.items,                           
                            borderColor: this.left.color,
                            hidden: this.left.isVisible,
                        }
                    ],
                    
                }
                
            },

            chartOptionsData() {
                return this.chartOptions;
            }
        },

        methods: {
            toggleData(value) {
                this[value].isVisible = !this[value].isVisible;
            },

            numberFormat(value) {
                return numberFormatting(value);
            },
        },
    }
</script>

<style lang="scss" scoped src="./s.scss">
    
</style>
