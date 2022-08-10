<template>
    <div v-if="data" class="chart-analytics-community">
        <div class="chart-analytics-community__header">
            <div class="chart-analytics-community__label">
                <span class="chart-analytics-community__label-text">
                    Всего подписчиков в сообществе
                </span>

                <span class="chart-analytics-community__label-value">
                    {{ data.total }}
                </span>
            </div>

            <button
                class="chart-analytics-community__label chart-analytics-community__label--pointer"
                @click="toggleData('joined')"
            >
                <span class="chart-analytics-community__label-text">
                    Вступили в сообщество
                </span>

                <span
                    class="chart-analytics-community__label-value"
                    :style="{ color: joined.color }"
                >
                    {{ data.joined.total }}
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
                class="chart-analytics-community__label chart-analytics-community__label--pointer"
                @click="toggleData('useful')"
            >
                <span
                    class="chart-analytics-community__label-value"
                    :style="{ color: useful.color }"
                >
                    {{ data.useful.total }}
                </span>
             
                <span class="chart-analytics-community__label-text">
                    Полезных сообщений
                </span>
            </button>
        </div>
    </div>
</template>

<script>
    import LineChart from '../../../ui/chart/LineChart.vue';

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
