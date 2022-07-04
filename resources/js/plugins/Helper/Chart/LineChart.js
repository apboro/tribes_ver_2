import { BaseChart } from "./BaseChart";
import { Chart } from "chart.js/dist/chart";

export class LineChart extends BaseChart {
    constructor(options) {
        super(options);
    }

    updateChart(data) {
        this.changeData = data;
        this.updateChartInstance();
    }

    // определяем 1 цвет для всех данных
    getLabelsBackground() {
        return this.chartColors[0];
    }

    // инициализация диаграммы типа бар
    initChart() {
        this.chartInstance = new Chart(this.chartEx, {
            type: 'line',
            
            options: {
                indexAxis: 'x',
                plugins: {
                    
                    beforeEvent: function (chart) {
                        chart.legend.afterFit = function () {
                            this.height += 20;
                        };
                    },
    
                    tooltip: {
                        // Updated default tooltip UI
                        shadowOffsetX: 1,
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
                        }
                    },

                    legend: {
                        display: false,
                        position: 'top',
                        align: 'start',
                        labels: {
                            usePointStyle: true,
                            padding: 25,
                            boxWidth: 9
                        }
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
                backgroundColor: false,
                
            
                
                hover: {
                    mode: 'label'
                },
            
                layout: {
                    padding: {
                        top: 0,
                        bottom: 0,
                        left: 0
                    }
                },

                scales: {
                    x: {
                        display: this.mini ? false : true,
                        scaleLabel: {
                            display: true
                        },
                        gridLines: {
                            display: true,
                            color: 'rgb(100, 0, 0)',
                            zeroLineColor: 'rgb(0, 100, 0)'
                        },
                        ticks: {
                            fontColor: 'rgb(0, 0, 100)'
                        }
                    },
                    y: {
                        display: this.mini ? false : true,
                        
                        scaleLabel: {
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
                        }
                    }
                },
            },

            data: {
                labels: this.dataLabels,
                datasets: [
                    {
                        data: this.dataValues,
                        //label: 'Europe',
                        borderColor: this.lineColors ? this.lineColors[0] : this.labelBackground,
                        lineTension: 0.5,
                        pointStyle: 'circle',
                        cubicInterpolationMode: 'monotone',
                        
                        borderWidth: this.mini ? 3 : 5,
                        backgroundColor: this.lineColors ? this.lineColors[0] : this.labelBackground,
                        fill: {
                            target: this.fill ? 'origin' : false,
                            above: this.fill ? `${ this.lineColors[0] }50` : '',
                        },
                        
                        pointRadius: this.mini ? 2 : 5,
                        pointBorderWidth: 1,
                        pointBorderColor: '#000000',
                    },
                    /*{
                        data: [80, 125, 105, 130, 215, 195, 140, 160, 230, 300, 220, 170, 210, 200, 280],
                        label: 'Asia',
                        borderColor: 'rgb(100, 0, 0)',
                        lineTension: 0.5,
                        pointStyle: 'circle',
                        backgroundColor: 'rgb(100, 0, 0)',
                        fill: false,
                        pointRadius: 5,
                        pointBorderWidth: 1,
                        pointBorderColor: 'black',
                    },
                    {
                        data: [80, 99, 82, 90, 115, 115, 74, 75, 130, 155, 125, 90, 140, 130, 180],
                        label: 'Africa',
                        borderColor: 'rgb(0, 0, 100)',
                        lineTension: 0.5,
                        pointStyle: 'circle',
                        backgroundColor: 'rgb(0, 0, 100)',
                        fill: false,
                        pointRadius: 5,
                        pointBorderWidth: 1,
                        pointBorderColor: 'black',
                    }*/
                ]
            }
        })
    }
}
