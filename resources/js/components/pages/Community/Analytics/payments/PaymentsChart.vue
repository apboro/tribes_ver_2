<template>
    <div v-if="data && data.all_payments.total" class="chart-analytics-community">
        <div class="chart-analytics-community__header">
            <div class="chart-analytics-community__label">
                <span class="chart-analytics-community__text">
                    Всего заработано
                </span>

                <span class="chart-analytics-community__value">
                    <span class="chart-analytics-community__currency">₽</span>{{ numberFormat(data.total) }}
                </span>
            </div>

            <button
                class="chart-analytics-community__label chart-analytics-community__label--pointer chart-analytics-community__label--right"
                @click="toggleData('all_payments')"
            >
                <span class="chart-analytics-community__text">
                    Поступления  за период
                </span>

                <span
                    class="chart-analytics-community__value"
                    :style="{ color: all_payments.color }"
                >
                    <span class="chart-analytics-community__currency">₽</span>{{ numberFormat(data.all_payments.total) }}
                </span>
             
            </button>
        </div>

        <line-chart
            class="chart-analytics-community__chart"
            :chartData="chartData"
            :chartOptions="chartOptions"
        />

        <div class="chart-analytics-community__footer">
            <button
                class="chart-analytics-community__label chart-analytics-community__label--pointer chart-analytics-community__label--right"
                @click="toggleData('subscriptions')"
            >
                <span
                    class="chart-analytics-community__value"
                    :style="{ color: subscriptions.color }"
                >
                    <span class="chart-analytics-community__currency">₽</span>{{ numberFormat(data.subscriptions.total) }}
                </span>
             
                <span
                    class="chart-analytics-community__text"
                    :style="{ color: subscriptions.color }"
                >
                    Подписки
                </span>
            </button>

            <button
                class="chart-analytics-community__label chart-analytics-community__label--pointer chart-analytics-community__label--right"
                @click="toggleData('donations')"
            >
                <span
                    class="chart-analytics-community__value"
                    :style="{ color: donations.color }"
                >
                    <span class="chart-analytics-community__currency">₽</span>{{ numberFormat(data.donations.total) }}
                </span>
             
                <span
                    class="chart-analytics-community__text"
                    :style="{ color: donations.color }"
                >
                    Донаты
                </span>
            </button>

            <button
                class="chart-analytics-community__label chart-analytics-community__label--pointer chart-analytics-community__label--right"
                @click="toggleData('media')"
            >
                <span
                    class="chart-analytics-community__value"
                    :style="{ color: media.color }"
                >
                    <span class="chart-analytics-community__currency">₽</span>{{ numberFormat(data.media.total) }}
                </span>
             
                <span
                    class="chart-analytics-community__text"
                    :style="{ color: media.color }"
                >
                    Медиатовары
                </span>
            </button>
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
            }
        },

        data() {
            return {
                all_payments: {
                    isVisible: false,
                    color: '#21C169',
                },
                
                subscriptions: {
                    isVisible: false,
                    color: '#E24041',
                },
                
                donations: {
                    isVisible: false,
                    color: '#30AAF0',
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
                            data: this.data.all_payments.items,                           
                            borderColor: this.all_payments.color,
                            hidden: this.all_payments.isVisible,
                        },

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

            chartOptions() {
                return {
                    responsive: true,
                    maintainAspectRatio: false,
                    radius: 1,
                    hoverRadius: 0,
                    borderWidth: 4,
                    pointBorderColor: 'transparent',
                    tension: 0.1,
                    
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutCubic'
                    },
                    
                    scales: {
                        x: { display: false },
                        y: { display: false }
                    },

                    plugins: {
                        legend: { display: false },
                        title: { display: false },
                        tooltip: { enabled: false },
                    }
                    
                }
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
