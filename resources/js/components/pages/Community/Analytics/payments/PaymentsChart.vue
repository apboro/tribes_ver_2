<template>
    <div v-if="data" class="chart-analytics-community">
        <div class="chart-analytics-community__header">
            <div class="chart-analytics-community__label">
                <span class="chart-analytics-community__text">
                    Всего заработано
                </span>

                <span class="chart-analytics-community__value">
                    <span class="chart-analytics-community__currency">₽</span>{{ data.total }}
                </span>
            </div>

            <div class="chart-analytics-community__label chart-analytics-community__label--right">
                <span class="chart-analytics-community__text">
                    Поступления  за период
                </span>

                <span class="chart-analytics-community__value">
                    <span class="chart-analytics-community__currency">₽</span>{{ data.period_total }}
                </span>
            </div>
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
                    <span class="chart-analytics-community__currency">₽</span>{{ data.subscriptions.total }}
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
                    <span class="chart-analytics-community__currency">₽</span>{{ data.donations.total }}
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
                <p
                    class="chart-analytics-community__value"
                    :style="{ color: media.color }"
                >
                    <span class="chart-analytics-community__currency">₽</span>{{ data.media.total }}
                </p>
             
                <p
                    class="chart-analytics-community__text"
                    :style="{ color: media.color }"
                >
                    Медиатовары
                </p>
            </button>
        </div>
    </div>
</template>

<script>
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
                subscriptions: {
                    isVisible: false,
                    color: '#21C169',
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
                console.log(this.data);

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
        }
    }
</script>
