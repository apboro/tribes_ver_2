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
                    +{{ data.joined.total }}
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
                @click="toggleData('left')"
            >
                <span
                    class="chart-analytics-community__label-value"
                    :style="{ color: left.color }"
                >
                    -{{ data.left.total }}
                </span>
             
                <span class="chart-analytics-community__label-text">
                    Покинули сообщество
                </span>
            </button>
        </div>
    </div>
</template>

<script>
    import LineChart from '../../../ui/chart/LineChart.vue';

    export default {
        name: 'SubscribersChart',
        
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
                
                left: {
                    isVisible: false,
                    color: '#E24041',
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
                            data: this.data.left.items,                           
                            borderColor: this.left.color,
                            hidden: this.left.isVisible,
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
        },

        mounted() {
            console.log(this.data);
        }
    }
</script>
