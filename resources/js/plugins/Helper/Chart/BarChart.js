import { BaseChart } from "./BaseChart";
import { Chart } from "chart.js/dist/chart";

export class BarChart extends BaseChart {
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
            type: 'bar',
            
            data: {
                datasets: [{
                    data: this.dataValues,
                    barThickness: 15,
                    backgroundColor: this.lineColors ? this.lineColors[0] : this.labelBackground,
                    borderColor: 'transparent',
                    fill: true,
                }],
                
                labels: this.dataLabels,
            },

            options: {
                responsive: true,
                indexAxis: 'x',
                plugins: {
                    legend: {
                        display: false
                    },

                    tooltip: {
                        callbacks: {
                            label: (tooltipItem) => {
                                const label = tooltipItem.label || '';
                                let value = tooltipItem.formattedValue;
                                
                                if (this.currency) {
                                    value = new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB', minimumFractionDigits: 0 }).format(value)
                                }

                                return ` ${ label } : ${ value }`;
                            },
                            
                            title: function(context) {
                                return false;
                            },
                        },
                        // Updated default tooltip UI
                        backgroundColor: '#000',
                        titleFontColor: '#fff',
                        bodyFontColor: '#000',
                        
                    }
                },
                borderRadius: 10,
                responsive: true,
                maintainAspectRatio: false,
                responsiveAnimationDuration: 500,
                aspectRatio: 1,
                
                layout: {
                    padding: {
                        top: 0,
                        bottom: 0,
                        left: 10,
                        right: 10,
                    }
                },

                scales: {
                    x: {
                        
                        display: this.mini ? false : true,
                        gridLines: {
                            display: true,
                            /*color: this.themeColors.grid_line_color,
                            zeroLineColor: this.themeColors.grid_line_color*/
                        },
                        scaleLabel: {
                            display: false
                        },
                        ticks: {
                            //fontColor: this.themeColors.labelColor
                            //maxTicksLimit: 4
                            
                        },
                    },
                    
                    y: {
                        display: this.mini ? false : true,
                        gridLines: {
                            
                            /*color: this.themeColors.grid_line_color,
                            zeroLineColor: this.themeColors.grid_line_color*/
                        },
                        ticks: {
                            stepSize: 40,
                            
                            
                            //fontColor: this.themeColors.labelColor
                        }
                    }
                }
            },
        });
    }
}
