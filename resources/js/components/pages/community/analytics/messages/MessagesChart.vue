<template>
    <div
        v-if="data && data.total"
        class="chart-analytics-community"
    >
        <div class="chart-analytics-community__header">
            <div class="chart-analytics-community__label chart-analytics-community__label--right">
                <span class="chart-analytics-community__text">
                    Всего сообщений
                </span>

                <span class="chart-analytics-community__value">
                    {{ numberFormat(data.total) }}
                </span>
            </div>

            <button
                class="chart-analytics-community__label chart-analytics-community__label--pointer chart-analytics-community__label--right"
                @click="toggleData('useful')"
            >
                <span class="chart-analytics-community__text">
                    За период
                </span>

                <span
                    class="chart-analytics-community__value"
                    :style="{ color: useful.color }"
                >
                    +{{ numberFormat(data.useful.total) }}
                </span>
            </button>

            <button
                class="chart-analytics-community__label chart-analytics-community__label--pointer chart-analytics-community__label--right"
                @click="toggleData('joined')"
            >
                <span class="chart-analytics-community__text">
                    Полезных
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
        name: 'MessagesChart',
        
        components: {
            LineChart,
        },

        props: {
            data: {
                type: [Object, null],
                default: () => null
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
                
                useful: {
                    isVisible: false,
                    color: '#30AAF0',
                }
            }
        },

        computed: {
            chartData() {
                return {
                    labels: this.data.joined.items,
                    datasets: [
                        {
                            data: this.data.joined.items,                           
                            borderColor: this.joined.color,
                            hidden: this.joined.isVisible,
                        },

                        {
                            data: this.data.useful.items,                           
                            borderColor: this.useful.color,
                            hidden: this.useful.isVisible,
                        }
                    ]
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

            format(value) {
                return numberFormatting(value);
            },

            numberFormat(value) {
                return numberFormatting(value);
            },
        }
    }
</script>
