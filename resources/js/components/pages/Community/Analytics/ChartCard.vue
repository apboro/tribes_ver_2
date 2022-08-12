<template>
    <li class="card-analytics-community">
        <div class="card-analytics-community__header">
            <h3 class="card-analytics-community__title">
                {{ data.title }}
            </h3>
            <span class="card-analytics-community__subtitle">за период</span>
        </div>

        <line-chart
            class="card-analytics-community__chart"
            :chartData="chartData"
            :chartOptions="chartOptions"
            :width="350"
            :height="110"
        />

        <div class="card-analytics-community__footer">
            <div class="card-analytics-community__info">
                <span class="card-analytics-community__info-label">
                    {{ data.infoLeft.text }}
                </span>

                <span class="card-analytics-community__info-value">
                    
                    {{ data.infoLeft.value }}
                </span>
            </div>

            <div class="card-analytics-community__info card-analytics-community__info--right">
                <span class="card-analytics-community__info-label">
                    {{ data.infoRight.text }}
                </span>

                <span class="card-analytics-community__info-value card-analytics-community__info-value--green">
                    {{ data.infoRight.value }}
                </span>
            </div>

            <button class="button-filled button-filled--primary card-analytics-community__link">
                Подробнее
            </button>
        </div>
    </li>
</template>

<script>
    import LineChart from '../../../ui/chart/LineChart.vue';

    export default {
        name: 'ChartCard',

        components: {
            LineChart,
        },

        props: {
            data: {
                type: Object,
                default: () => {}
            }
        },

        computed: {
            chartData() {
                return {
                    labels: this.data.data,
                    datasets: [
                        {
                            data: this.data.data,                           
                            borderColor: "#2AB0EE",
                            fill: {
                                target: 'origin',
                                above: '#2AB0EE10',
                            },
                            hidden: false,
                        }
                    ]
                }
            },

            chartOptions() {
                return {
                    responsive: true,
                    maintainAspectRatio: false,
                    radius: 0,
                    hoverRadius: 0,
                    borderWidth: 1,
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
    }
</script>
