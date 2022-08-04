<template>
    <LineChartGenerator
        :chart-options="options"
        :chart-data="chartData"
        :width="width"
        :height="height"
    />
</template>

<script>
    import { Line as LineChartGenerator } from 'vue-chartjs/legacy';
    import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement } from 'chart.js';

    ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement);

    export default {
        name: 'LineChart',
        components: { LineChartGenerator },
        props: {
            chartOptions: {
                type: [Object, null],
                default: null
            },

            chartData: {
                type: Object,
                default: () => [],
            },

            width: {
                type: Number,
                default: 400
            },

            height: {
                type: Number,
                default: 400
            },
        },

        data() {
            return {
                data: {},

                baseOptions: {
                    responsive: true,
                    maintainAspectRatio: false,
                    radius: 0,
                    hoverRadius: 0,
                    tension: 0.5, //скругление
                    // отключение анимации
                    animation: {
                        duration: 0 // general animation time
                    },

                    hover: {
                        animationDuration: 0 // duration of animations when hovering an item
                    },

                    responsiveAnimationDuration: 0,

                    layout: {
                        padding: 0
                    },
                    
                    /* scaleLabel: {
                        display: false
                    }, */
                    scales: {
                        x: {
                            display: false,
                            /* scaleLabel: {
                                display: true
                            },
                            gridLines: {
                                display: true,
                                color: 'rgb(100, 0, 0)',
                                zeroLineColor: 'rgb(0, 100, 0)'
                            },
                            ticks: {
                                fontColor: 'rgb(0, 0, 100)'
                            } */
                        },
                        y: {
                            display: false,
                            
                            /* scaleLabel: {
                                display: true,
                                
                            },
                            ticks: {
                                stepSize: 0,
                                min: 0,
                                max: 400,
                                fontColor: 'rgb(0, 0, 100)',
                                callback: (label, index, labels) => {
                                    return this.currency
                                    ?
                                        new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB', minimumFractionDigits: 0 }).format(label)
                                    :
                                        label 
                                }
                            },
                            gridLines: {
                                display: true,
                                color: 'rgb(0, 100, 0)',
                                zeroLineColor: 'rgb(0, 100, 0)'
                            } */
                        }
                    },

                    plugins: {
                        legend: {
                            display: false,
                            position: 'bottom',
                            align: 'end',

                            /* legendCallback: function(chart) {
                               console.log(13);
                            }, */
                            
                            labels: {
                                usePointStyle: true,
                                padding: 25,
                                boxWidth: 9
                            }
                        },
                        title: {
                            display: true,
                            text: "My Data"
                        },
                        
                        tooltip: {
                            enabled: false,
                            // Updated default tooltip UI
                           /*  shadowOffsetX: 1,
                            shadowOffsetY: 1,
                            shadowBlur: 8,
                            shadowColor: 'rgb(0, 0, 0)',
                            backgroundColor: 'rgb(0, 0, 0)',
                            titleFontColor: 'rgb(0, 0, 0)',
                            bodyFontColor: 'rgb(0, 0, 0)',
                            callbacks: {
                                label: (label) => {
                                    return this.currency
                                    ?
                                        new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB', minimumFractionDigits: 0 }).format(label.raw)
                                    :
                                        label.raw
                                }
                            } */
                        },
                    }
                    
                },

                
                
            }
        },

        computed: {
            options() {
                return this.chartOptions ?? this.baseOptions;
            }
        },

        mounted() {
            /* this.styles = "padding: 50px 0 0 0" */
        }
    }
</script>
