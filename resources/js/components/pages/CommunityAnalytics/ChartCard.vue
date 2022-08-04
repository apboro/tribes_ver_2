<template>
    <li class="card-analytics">
        <div class="card-analytics__header">
            <h3 class="card-analytics__title">
                {{ data.title }}
            </h3>
            <span class="card-analytics__subtitle">за период</span>
        </div>

        <line-chart
            class="card-analytics__chart"
            :chartData="chartData"
            :chartOptions="chartOptions"
            :width="350"
            :height="110"
        />

        <div class="card-analytics__footer">
            <div class="card-analytics__info">
                <span class="card-analytics__info-label">
                    {{ data.infoLeft.text }}
                </span>

                <span class="card-analytics__info-value">
                    {{ data.infoLeft.value }}
                </span>
            </div>

            <div class="card-analytics__info">
                <span class="card-analytics__info-label">
                    {{ data.infoRight.text }}
                </span>

                <span class="card-analytics__info-value card-analytics__info-value--green">
                    {{ data.infoRight.value }}
                </span>
            </div>

            <button class="button-filled button-filled--primary card-analytics__link">
                Подробнее
            </button>
        </div>
    </li>
</template>

<script>
    import LineChart from '../../ui/chart/LineChart.vue';

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

        data() {
            return {
                chartData: {
                    labels: this.data.data,
                    datasets: [
                        {
                            data: this.data.data,                           
                            borderColor: "#2AB0EE",
                            hidden: false,
                        }
                    ]
                },

                chartOptions: {
                    responsive: true,
                    maintainAspectRatio: false,
                    radius: 0,
                    hoverRadius: 0,
                    borderWidth: 4,
                    
                    scales: {
                        x: { display: false },
                        y: { display: false }
                    },

                    plugins: {
                        legend: { display: false },
                        title: { display: false },
                        tooltip: { enabled: false },
                    }
                    
                },
            }
        },
    }
</script>
