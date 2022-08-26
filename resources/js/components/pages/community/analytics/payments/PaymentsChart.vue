<template>
    <div v-if="data && data.all_payments.total" class="chart-analytics-community">
        <div class="chart-analytics-community__header">
            <button
                class="chart-analytics-community__label chart-analytics-community__label--pointer chart-analytics-community__label--right"
                @click="toggleData('donations')"
            >
                <span
                    class="chart-analytics-community__text"
                    :style="{ color: donations.color }"
                >
                    Донаты
                </span>

                <span
                    class="chart-analytics-community__value"
                    :style="{ color: donations.color }"
                >
                    <span class="chart-analytics-community__currency">₽</span>{{ numberFormat(data.donations.total) }}
                </span>
            </button>

            <button
                class="chart-analytics-community__label chart-analytics-community__label--pointer chart-analytics-community__label--right"
                @click="toggleData('subscriptions')"
            >
                <span
                    class="chart-analytics-community__text"
                    :style="{ color: subscriptions.color }"
                >
                    Подписки
                </span>
                
                <span
                    class="chart-analytics-community__value"
                    :style="{ color: subscriptions.color }"
                >
                    <span class="chart-analytics-community__currency">₽</span>{{ numberFormat(data.subscriptions.total) }}
                </span>
            </button>

            <button
                class="chart-analytics-community__label chart-analytics-community__label--pointer chart-analytics-community__label--right"
                @click="toggleData('media')"
            >
                <span
                    class="chart-analytics-community__text"
                    :style="{ color: media.color }"
                >
                    Медиатовары
                </span>
                
                <span
                    class="chart-analytics-community__value"
                    :style="{ color: media.color }"
                >
                    <span class="chart-analytics-community__currency">₽</span>{{ numberFormat(data.media.total) }}
                </span>
            </button>
        </div>

        <line-chart
            class="chart-analytics-community__chart"
            :chartData="chartData"
            :chartOptions="chartOptionsData"
        />

        <div class="chart-analytics-community__footer">
            <div class="chart-analytics-community__label">
                <span class="chart-analytics-community__value">
                    <span class="chart-analytics-community__currency">₽</span>{{ numberFormat(data.total) }}
                </span>

                <span class="chart-analytics-community__text">
                    Всего заработано
                </span>
            </div>

            <div class="chart-analytics-community__label chart-analytics-community__label--right">
                <span class="chart-analytics-community__value">
                    <span class="chart-analytics-community__currency">₽</span>{{ numberFormat(data.all_payments.total) }}
                </span>
             
                <span class="chart-analytics-community__text">
                    Поступления  за период
                </span>
            </div>
        </div>
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
                type: Object,
                default: () => {}
            },

            chartOptions: {
                type: Object,
                default: () => {}
            }
        },

        data() {
            return {
                subscriptions: {
                    isVisible: false,
                    color: '#E24041',
                },
                
                donations: {
                    isVisible: false,
                    color: '#363440',
                },

                media: {
                    isVisible: false,
                    color: '#FF9F43',
                }
            }
        },

        computed: {
            chartData() {
                return {
                    labels: this.data.subscriptions.items,
                    datasets: [
                        {
                            data: this.data.subscriptions.items,                           
                            borderColor: this.subscriptions.color,
                            hidden: this.subscriptions.isVisible,
                        },

                        {
                            data: this.data.donations.items,                           
                            borderColor: this.donations.color,
                            hidden: this.donations.isVisible,
                        },

                        {
                            data: this.data.media.items,                           
                            borderColor: this.media.color,
                            hidden: this.media.isVisible,
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

            numberFormat(value) {
                return numberFormatting(value);
            },
        }
    }
</script>
