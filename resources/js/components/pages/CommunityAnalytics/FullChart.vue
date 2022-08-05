<template>
    <div>
        <div>
            <span>
                {{ data.common.text }}
            </span>

            <span>
                {{ data.common.value }}
            </span>
        </div>

        <button
            style="padding: 10px;"
            :style="{ backgroundColor: joined.color }"
            @click="toggleData('joined')"
        >
            <span>
                {{ data.joined.legend.text }}
            </span>

            <span>
                {{ data.joined.legend.value }}
            </span>
        </button>

        <line-chart
            class="card-analytics__chart"
            :chartData="chartData"
            :chartOptions="chartOptions"
        />

        <button
            style="padding: 10px;"
            :style="{ backgroundColor: left.color }"
            @click="toggleData('left')"
        >
            <span>
                {{ data.left.legend.text }}
            </span>

            <span>
                {{ data.left.legend.value }}
            </span>
        </button>
    </div>
</template>

<script>
    import LineChart from '../../ui/chart/LineChart.vue';

    export default {
        name: 'FullChart',
        
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
                    labels: this.data.joined.data,
                    datasets: [
                        {
                            data: this.data.joined.data,                           
                            borderColor: this.joined.color,
                            
                            hidden: this.joined.isVisible,
                        },

                        {
                            data: this.data.left.data,                           
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
                    tension: 0.5,
                    
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
